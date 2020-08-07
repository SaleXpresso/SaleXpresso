<?php
/**
 * API Request Handler
 *
 * @package SaleXpresso/API
 * @version 1.0.0
 * @since   1.0.0
 */

namespace SaleXpresso;

if ( ! defined( 'ABSPATH' ) ) {
	header( 'Status: 403 Forbidden' );
	header( 'HTTP/1.1 403 Forbidden' );
	die();
}

/**
 * Class SXP_Session.
 * @see \WC_API
 *
 */
class SXP_API {
	
	/**
	 * SXP_API constructor.
	 * Set-up action and filter hooks
	 */
	public function __construct() {
		add_action( 'init', array( $this, 'add_endpoint' ), 0 );
		add_filter( 'query_vars', array( $this, 'add_query_vars' ), 0 );
		add_action( 'parse_request', array( $this, 'handle_api_requests' ), 0 );
		flush_rewrite_rules();
	}
	
	/**
	 * Add new query vars.
	 *
	 * @param array $vars Query vars.
	 * @return string[]
	 */
	public function add_query_vars( $vars ) {
		$vars[] = 'sxp-api';
		return $vars;
	}
	
	/**
	 * Add Endpoints
	 *
	 * @return void
	 */
	public static function add_endpoint() {
		add_rewrite_endpoint( 'sxp-api', EP_ALL );
		add_action( 'wp_ajax_salexpresso_set_user_tag', [ __CLASS__, 'set_customer_tags' ] );
	}
	
	public static function set_customer_tags() {
		check_admin_referer( 'customer-profile-set-tag' );
		if ( ! isset( $_REQUEST['user_id'], $_REQUEST['tags'] ) && ! empty( $_REQUEST['user_id'] ) && empty( $_REQUEST['tags'] ) ) {
			wp_send_json_error( esc_html__( 'Invalid Request', 'salexpresso' ) );
			die();
		}
		if ( ! current_user_can( 'manage_options' ) ) {
			wp_send_json_error( esc_html__( 'You do not have enough permission to set user tag.', 'salexpresso' ) );
			die();
		}
		$data = wc_clean( $_REQUEST ); // phpcs:ignore
		$tags = array_filter( $data['tags'] );
		$tags = array_unique( $tags );
		
		if ( ! empty( $tags ) ) {
			$set = sxp_set_user_tags( absint( $data['user_id'] ), $tags, false );
			if ( false === $set ) {
				wp_send_json_error( esc_html__( 'Unable to save tags.', 'salexpresso' ) );
			} elseif ( is_wp_error( $set ) ) {
				wp_send_json_error( sprintf(
					esc_html__( 'Error while saving user tags. Error: %s', 'salexpresso' ),
					$set->get_error_message()
				) );
			} else {
				wp_send_json_success( esc_html__( 'Tags Saved. Click ok to reload.', 'salexpresso' ) );
			}
		} else {
			wp_send_json_error( esc_html__( 'Invalid Or Empty Tags.', 'salexpresso' ) );
		}
		
		die();
	}
	
	/**
	 * API request - Trigger any API requests.
	 */
	public function handle_api_requests() {
		global $wp;
		
		if ( isset( $_GET['sxp-api'] ) && ! empty( $_GET['sxp-api'] ) ) { // WPCS: input var okay, CSRF ok.
			$wp->query_vars['sxp-api'] = sanitize_key( wp_unslash( $_GET['sxp-api'] ) ); // WPCS: input var okay, CSRF ok.
		} else {
			if ( isset( $wp->query_vars['sxp-api'] ) && ! empty( $wp->query_vars['sxp-api'] ) ) {
				$wp->query_vars['sxp-api'] = sanitize_key( $wp->query_vars['sxp-api'] );
			}
		}
		
		// sxp-api endpoint requests.
		if ( ! empty( $wp->query_vars['sxp-api'] ) ) {
			
			// Buffer, we won't want any output here.
			ob_start();
			
			// No cache headers.
			wc_nocache_headers();
			
			// Clean the API request.
			$api_request = strtolower( wc_clean( $wp->query_vars['sxp-api'] ) );
			
			// Trigger generic action before request hook.
			do_action( 'salexpresso_api_request', $api_request );
			
			// Is there actually something hooked into this API request? If not trigger 400 - Bad request.
			status_header( has_action( "salexpresso_api_{$api_request}" ) ? 200 : 400 );
			
			// Trigger an action which plugins can hook into to fulfill the request.
			do_action( "salexpresso_api_{$api_request}" );
			
			// Done, clear buffer and exit.
			ob_end_clean();
			die( '-1' );
		}
	}
}
// End of file class-sxp-api.php.
