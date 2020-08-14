<?php
/**
 * SaleXpresso
 *
 * @package SaleXpresso\Customer
 * @version 1.0.0
 * @since   1.0.0
 */

namespace SaleXpresso\RecommendationsEngine;

use Exception;
use Phpml\Association\Apriori;
use WP_User;

class RecommendationEngineException extends Exception {}

if ( ! defined( 'ABSPATH' ) ) {
	header( 'Status: 403 Forbidden' );
	header( 'HTTP/1.1 403 Forbidden' );
	die();
}

/**
 * Class SXP_Recommendations
 *
 * @package SaleXpresso\RecommendationsEngine
 */
class SXP_Recommendation_Engine {
	
	/**
	 * Options.
	 * @var array
	 */
	private $options;
	
	/**
	 * AI Cache.
	 * @var SXP_Recommendation_Engine_Cache
	 */
	private $cache;
	
	/**
	 * Singleton instance.
	 * @var SXP_Recommendation_Engine
	 */
	protected static $instance;
	
	/**
	 * Get singleton instance.
	 *
	 * @return SXP_Recommendation_Engine
	 */
	public static function get_instance() {
		if ( is_null( self::$instance ) ) {
			self::$instance = new self();
		}
		
		return self::$instance;
	}
	
	/**
	 * SXP_Recommendation_Engine constructor.
	 */
	private function __construct() {
		$this->cache = new SXP_Recommendation_Engine_Cache();
//		add_action( 'salexpresso_recommendation_engine_schedule_training', [ $this, 'schedule_training' ] );
//		add_action( 'salexpresso_installed', [ $this, 'schedule_training' ] );
//		add_action( 'salexpresso_train_the_ai', [ $this, 'train_the_ai' ] );
		add_action( 'woocommerce_new_order', [ $this, 'invalidate_all_caches' ], 10, 1 );
	}
	
	public function invalidate_all_caches( $order_id ) {
		$order = wc_get_order( $order_id );
		if ( 'shop_order_refund' === $order->get_type() ) {
			return;
		}
		$user = $order->get_user();
		if ( $user ) {
			update_user_meta( $user->ID, '__sxp_retrain_ai', 'yes' );
		}
	}
	
	/**
	 * Schedule Training.
	 */
	public function schedule_training() {
		if ( ! $this->is_enabled() ) {
			return;
		}
		
		$ttl = time() + ( 2 * MINUTE_IN_SECONDS );
		WC()->queue()->schedule_single( $ttl, 'salexpresso_train_the_ai', [], 'sxp-ai' );
	}
	
	public function train_the_ai() {
		if ( ! $this->is_enabled() ) {
			return;
		}
	}
	
	/**
	 * Get Recommendation For User.
	 *
	 * @param int $user_id User Id.
	 *
	 * @return array Product Ids.
	 * @throws RecommendationEngineException
	 */
	public function get_recommendation_for( $user_id ) {
		$retrain = get_user_meta( $user_id, '__sxp_retrain_ai', true );
		if ( empty( $retrain ) ) {
			$retrain = 'yes';
		}
		$frequent = false;
		if ( 'yes' !== $retrain ) {
			$frequent = get_user_meta( $user_id, '__sxp_get_most_frequent', true );
		}
		if ( ! $frequent ) {
			$ai = $this->get_recommendation_ai_for( $user_id );
			// Frequent item sets
			$item_set = $ai->apriori();
			$frequent = $this->get_most_frequent( $item_set );
			if ( $frequent ) {
				update_user_meta( $user_id, '__sxp_get_most_frequent', $frequent );
			}
		}
		
		return $frequent ? $frequent : [];
	}
	
	/**
	 * Get AI for User's Recommendation Data.
	 *
	 * @param int $user_id User ID.
	 *
	 * @return bool|Apriori|\Phpml\Estimator
	 * @throws RecommendationEngineException
	 */
	public function get_recommendation_ai_for( $user_id ) {
		$user_id = absint( $user_id );
		if ( ! $user_id ) {
			return false;
		}
		$customer_id = sxp_get_user_customer_id( $user_id );
		if ( ! $customer_id ) {
			return false;
		}
		$cache = 'customer_ai_' . $customer_id;
		$ai = false;
		$retrain = get_user_meta( $user_id, '__sxp_retrain_ai', true );
		if ( empty( $retrain ) ) {
			$retrain = 'yes';
		}
		if ( 'yes' !== $retrain ) {
			try {
				$ai = $this->cache->restoreFromOption( $cache );
			} catch ( Exception $e ) {
				throw new RecommendationEngineException( $e->getMessage(), $e->getCode(), $e );
			}
		}
		if ( ! $ai ) {
			$data = $this->get_training_data_for_customer( $customer_id );
			if ( count( $data ) >= apply_filters( 'salexpresso_customer_minimum_order_count_for_recommendation', 5 ) ) {
				$this->cache->removeDBCache( $cache );
				$ai = $this->get_trained_apriori(
					$data,
					$this->get_options('support' ),
					$this->get_options( 'confidence' ),
					$cache,
					( 30 * DAY_IN_SECONDS )
				);
			} else {
				throw new RecommendationEngineException( __( 'Not enough data to train the AI.', 'salexpresso' ) );
			}
		}
		
		return $ai;
	}
	
	/**
	 * Get Trained Apriori AI.
	 * @param array|array[] $samples
	 * @param float $support
	 * @param float $confidence
	 * @param string $cache_name
	 * @param int $expiration
	 *
	 * @return Apriori The Trained AI.
	 */
	private function get_trained_apriori( $samples, $support, $confidence, $cache_name, $expiration = 0 ) {
		$associator = new Apriori( $support, $confidence );
		$associator->train( $samples, [] );
		$associator->getRules();
		try {
			$mdl = new SXP_Recommendation_Engine_Cache();
			$mdl->saveToOptions( $associator, $cache_name, $expiration );
		} catch ( Exception $exception ) {
			if ( defined( 'WP_DEBUG' ) && WP_DEBUG ) {
				trigger_error( $exception->getMessage() );
			}
		}
		return $associator;
	}
	
	/**
	 * Get Training Data for customer.
	 *
	 * @param $customer_id
	 *
	 * @return array
	 */
	private function get_training_data_for_customer( $customer_id ) {
		$customer_id = absint( $customer_id );
		if ( ! $customer_id ) {
			return [];
		}
		global $wpdb;
		$wpdb->query( 'SET @sql = NULL;' );
		$wpdb->query( "SELECT GROUP_CONCAT(DISTINCT CONCAT( 'MAX(case when product_id = ''', product_id, ''' then product_id else 0 end) AS prod_', product_id ) ) INTO @sql
		FROM ( SELECT product_id FROM `{$wpdb->prefix}wc_order_product_lookup` WHERE customer_id = {$customer_id} GROUP BY product_id ORDER BY product_id ) tt;" );
		$wpdb->query( "SET @sql = CONCAT('SELECT ', @sql, ' FROM {$wpdb->prefix}wc_order_product_lookup WHERE customer_id = {$customer_id} group by order_id');" );
		$wpdb->query( "PREPARE stmt FROM @sql;" );
		return $wpdb->get_results("EXECUTE stmt;", ARRAY_A );
	}
	
	private function xxx_get_training_data( $customer_id = null ) {
		if ( ! $this->is_enabled() ) {
			return;
		}
		
		$customer_id = absint( $customer_id );
		global $wpdb;
		$wpdb->query( 'SET @sql = NULL;' );
		$wpdb->query( "SELECT GROUP_CONCAT(DISTINCT CONCAT( 'MAX(case when product_id = ''', product_id, ''' then product_id else 0 end) AS prod_', product_id ) ) INTO @sql
		FROM ( SELECT product_id FROM `{$wpdb->prefix}wc_order_product_lookup` WHERE customer_id = {$customer_id} GROUP BY product_id ORDER BY product_id ) tt;" );
		$wpdb->query( "SET @sql = CONCAT('SELECT ', @sql, ' FROM {$wpdb->prefix}wc_order_product_lookup WHERE customer_id = {$customer_id} group by order_id');" );
		$wpdb->query( "PREPARE stmt FROM @sql;" );
		$samples = $wpdb->get_results("EXECUTE stmt;", ARRAY_A );
//		$samples = $wpdb->get_results("EXECUTE stmt;", ARRAY_N );
		$s = microtime( true );
		$associator = new Apriori( $support = 0.5, $confidence = 0.5 );
		$associator->train( $samples, [] );
		$associator->getRules();
		var_dump( $associator );
		var_dump( microtime( true ) - $s );
		die();
//		$mdl = new ModelManager();
		$s = [ '13', '17', '21', '22', '24' ];
//		$s = range( 12, 21 );
//		$prediction = $associator->predict( $s );
		$comb = $this->get_combinations( $s );
		$prediction = $associator->predict( $comb );
//		$prediction = $associator->predict( $s );
		$frequent = $this->get_most_frequent( $prediction );
//		$frequent = $this->get_most_frequent( $associator->apriori() );
//		var_dump( $prediction );
		var_dump( $frequent );
		die( );
//		var_dump(  );
		
//		$args = [
//			'posts_per_page' => $this->get_options( 'order_count'),
//			'orderby'        => 'date',
//			'order'          => 'DESC',
//			'post_type'      => wc_get_order_types( 'view-orders' ),
//			'post_status'    => (array) $this->get_options( 'order_status' ),
//			'fields'         => 'ids',
//		];
//
//		if ( $user_id ) {
//			$args['meta_query'] = [
//				'relation' => 'AND',
//				[
//					'key' => '_customer_user',
//					'value' => $user_id,
//				],
//			];
//		}
//		$query = new \WP_Query(  );
//
//		$orders = array_map( 'wc_get_order', $query->get_posts() );
	}
	
	/**
	 * Get possible all combination of flat array of data.
	 * @param array $items Data.
	 *
	 * @return array[]
	 */
	private function get_combinations( $items ) {
		// initialize by adding the empty set
		$results = [ [] ];
		
		foreach ( $items as $item ) {
			foreach ( $results as $combination ) {
				array_push( $results, array_merge( array( $item ), $combination ) );
			}
		}
		
		array_shift( $results );
		
		return $results;
	}
	
	/**
	 * Normalize Apriori Predict or Frequent item sets
	 * @param array $data
	 *
	 * @return array|bool
	 */
	public function get_most_frequent( $data ) {
		
		if ( empty( $data ) ) {
			return false;
		}
		
		$data = $this->flatten_array( $data );
		
		$data_freq = array_count_values( $data );
		arsort( $data_freq );
		// $sorted_data contains the keys of sorted array
		return array_keys( $data_freq );
	}
	
	/**
	 * Flatten Array.
	 *
	 * @param array|array[] $array Array to flat.
	 *
	 * @return array
	 */
	private function flatten_array( $array ) {
		$return = array();
		array_walk_recursive( $array,
			function ( $a ) use ( &$return ) {
				if ( $a ) {
					$return[] = $a;
				}
			} );
		return $return;
		
	}
	
	/**
	 * Is engine enabled.
	 *
	 * @return bool
	 */
	protected function is_enabled() {
		return $this->get_options( 'is_enabled' );
	}
	
	/**
	 * Get the engine options.
	 *
	 * @param string $option Option to get.
	 *
	 * @return bool|string|string[]|int|float
	 */
	protected function get_options( $option ) {
		if ( $this->options ) {
			return isset( $this->options[ $option ] ) ? $this->options[ $option ] : null;
		}
		$options = [
			'is_enabled'         => 'yes',
			'order_status'       => [ 'wc-processing', 'wc-completed' ],
			'order_count'        => 1000,
			'support'            => 0.6,
			'confidence'         => 0.4,
			'exclude_outofstock' => 'yes',
			'include_backorders' => 'yes',
		];
		foreach ( $options as $key => $default ) {
			$this->options[ $key ] = get_option( 'salexpresso_recommendation_engine_' . $key );
			if ( ! $this->options[ $key ] ) {
				$this->options[ $key ] = $default;
			}
		}
		$this->options['is_enabled']         = 'yes' === $this->options['is_enabled'];
		$this->options['exclude_outofstock'] = 'yes' === $this->options['exclude_outofstock'];
		$this->options['include_backorders'] = 'yes' === $this->options['include_backorders'];
		return isset( $this->options[ $option ] ) ? $this->options[ $option ] : null;
	}
}
SXP_Recommendation_Engine::get_instance();
// End of file class-sxp-recommendation-engine.php.
