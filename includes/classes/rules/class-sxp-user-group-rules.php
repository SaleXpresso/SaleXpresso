<?php
/**
 * SaleXpresso Rule Action
 *
 * @package SaleXpresso\Rules
 * @version 1.0.0
 * @since   1.0.0
 */

namespace SaleXpresso\Rules;

use Exception;
use SaleXpresso\Abstracts\SXP_Action_Rules;
use SaleXpresso\SXP_Expression;
use WC_Customer;

if ( ! defined( 'ABSPATH' ) ) {
	header( 'Status: 403 Forbidden' );
	header( 'HTTP/1.1 403 Forbidden' );
	die();
}

/**
 * Class SXP_User_Group_Rules
 */
class SXP_User_Group_Rules extends SXP_Action_Rules {
	
	/**
	 * Singleton Instance.
	 *
	 * @var SXP_User_Group_Rules
	 */
	protected static $instance;
	
	/**
	 * Singleton instance.
	 *
	 * @return SXP_User_Group_Rules
	 */
	public static function get_instance() {
		if ( is_null( self::$instance ) ) {
			self::$instance = new self();
		}
		
		return self::$instance;
	}
	
	/**
	 * Init Hooks
	 *
	 * @return void
	 */
	protected function hooks() {
		
		if ( is_admin() ) {
			add_action( 'wp_ajax_user_group_get_comparisons', [ $this, 'ajax_get_comparisons' ] );
			add_action( 'wp_ajax_user_group_get_conditions', [ $this, 'ajax_get_conditions' ] );
		}
		
		add_action( 'woocommerce_thankyou', [ $this, 'evaluate_rules' ], 10, 1 );
		add_action( 'woocommerce_order_status_changed', [ $this, 'evaluate_rules' ], 10, 1 );
	}
	
	/**
	 * Evaluate Rules and execute the action.
	 *
	 * @param int $order_id the order id.
	 *
	 * @return void
	 */
	public function evaluate_rules( $order_id ) {
		if ( ! is_user_logged_in() ) {
			return;
		}
		$rules = $this->get_rules();
		if ( empty( $rules ) ) {
			return;
		}
		try {
			$order    = wc_get_order( $order_id );
			$customer = new WC_Customer( $order->get_customer_id() );
			$tags     = sxp_get_user_tags( $customer->get_id() );
			if ( is_wp_error( $tags ) ) {
				$tags = [];
			} else {
				$tags = array_map( function ( $tag ) {
					return $tag->term_id;
				}, $tags );
			}
			$qty = 0;
			foreach ( $order->get_items() as $item ) {
				$qty += $item->get_quantity();
			}
			$coupons = $order->get_coupon_codes();
			$e       = new SXP_Expression();
			$e->get_engine()->set_data( [
				'order' => (object) [
					'amount'   => $order->get_amount(),
					'quantity' => $qty,
					'coupon'   => ! empty( $coupons ) ? $coupons[0] : '',
					'items'    => $order->get_item_count(),
				],
				'user'  => (object) [
					'tags'        => $tags,
					'order_count' => $customer->get_order_count(),
					'total_spend' => $customer->get_total_spent(),
				],
			] );
			
			foreach ( $rules as $term_id => $rule ) {
				if ( $e->set_expression( $rules['compiled'] )->execute()->get_result() ) {
					sxp_set_user_groups( $customer->get_id(), $term_id );
				}
			}
		} catch ( Exception $e ) {
			// This exception only get caught if the user id is wrong.
			// @TODO use wc logger.
			return;
		}
	}
	
	/**
	 * Get registered Conditions.
	 *
	 * @return array
	 */
	public function get_conditions() {
		/**
		 * Filters Group Rules.
		 *
		 * @param array $conditions.
		 */
		return apply_filters( 'salexpresso_user_group_rule_conditions', $this->conditions );
	}
	
	/**
	 * Ajax Response for user_group_get_comparisons action
	 *
	 * @return void
	 */
	public function ajax_get_comparisons() {
		wp_send_json_success( $this->get_comparisons() );
		die();
	}
	
	/**
	 * Ajax Response for user_group_get_conditions action
	 *
	 * @return void
	 */
	public function ajax_get_conditions() {
		wp_send_json_success( $this->get_conditions() );
		die();
	}
	
	/**
	 * Get Rules.
	 *
	 * @return array
	 */
	protected function get_rules() {
		if ( is_user_logged_in() ) {
			$groups = get_terms( [
				'taxonomy'   => 'user_group',
				'hide_empty' => false,
				'fields'     => 'ids',
			] );
			if ( ! is_wp_error( $groups ) ) {
				$rules = [];
				foreach ( $groups as $group ) {
					$_rules = sxp_get_term_rules( $group );
					if ( ! isset( $_rules['compiled'] ) ) {
						$_rules = $this->compile_rules( $_rules );
						sxp_save_term_rules( $group, $_rules );
					}
					if ( isset( $_rules['compiled'] ) ) {
						$rules[ $group->term_id ] = $_rules;
					}
				}
				return $rules;
			}
		}
		return [];
	}
}

SXP_User_Group_Rules::get_instance();
// End of file class-sxp-user-group-rules.php.
