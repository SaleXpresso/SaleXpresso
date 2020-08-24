<?php
/**
 * SaleXpresso
 *
 * @package SaleXpresso\Customer
 * @version 1.0.0
 * @since   1.0.0
 */

namespace SaleXpresso\AbandonCart;

use Exception;
use SaleXpresso\SXP_Tracker;
use WC_Customer;
use WC_Order;

if ( ! defined( 'ABSPATH' ) ) {
	header( 'Status: 403 Forbidden' );
	header( 'HTTP/1.1 403 Forbidden' );
	die();
}

/**
 * Class SXP_Abandon_Cart
 *
 * @package SaleXpresso\AbandonCart
 */
class SXP_Abandon_Cart {
	
	/**
	 * Singleton instance.
	 * @var SXP_Abandon_Cart
	 */
	protected static $instance;
	
	/**
	 * Get singleton instance.
	 *
	 * @return SXP_Abandon_Cart
	 */
	public static function get_instance() {
		if ( is_null( self::$instance ) ) {
			self::$instance = new self();
		}
		
		return self::$instance;
	}
	
	/**
	 * SXP_Abandon_Cart constructor.
	 */
	private function __construct() {
		
		/**
		 * Order.
		 * @link https://docs.woocommerce.com/document/managing-orders/
		 *
		 * For Order Status
		 * @see wc_get_order_statuses()
		 *
		 */
		
		/**
		 * Most of the plugin hookup multiple action-hooks (given below) to record the cart-items,
		 * which is totally overkill. WooCommerce always fires 'woocommerce_after_calculate_totals'
		 * after finalizing all things and store the data in current user's session.
		 * We should hook ours after all possible hooks executed so we can grab the final
		 * output of the cart data.
		 *
		 * woocommerce_add_to_cart,
		 * woocommerce_cart_item_removed,
		 * woocommerce_cart_item_restored,
		 * woocommerce_cart_item_set_quantity,
		 * woocommerce_calculate_totals,
		 *
		 */
		
		add_action( 'woocommerce_after_calculate_totals', [ $this, 'on_add_to_cart_action' ], PHP_INT_MAX );
		add_action( 'woocommerce_new_order', [ $this, 'on_new_order' ], 99999 );
		add_action( 'woocommerce_order_status_changed', [ $this, 'on_order_status_changed' ], 99999, 3 );
		
		add_action( 'wp_ajax_sxp_save_abandon_cart_data', [ $this, 'save_checkout_form_data' ] );
		add_action( 'wp_ajax_nopriv_sxp_save_abandon_cart_data', [ $this, 'save_checkout_form_data' ] );
		
		add_action( 'salexpresso_abandon_cart_saved', [ $this, 'schedule_abandonment' ], 1, 1 );
		add_action( 'sxp_mark_abandoned', [ $this, 'mark_cart_as_abandoned' ], -1, 1 );
		add_action( 'sxp_delete_abandoned', [ $this, 'delete_abandoned' ], -1, 1 );
		
		// Restore abandon car.
		add_action( 'wp_loaded', [ $this, 'maybe_restore_abandon_cart' ], PHP_INT_MAX );
	}
	
	/**
	 * Schedule event for flag a cart as abandoned.
	 *
	 * @param array $cart_data Cart Data.
	 *
	 * @return void
	 */
	public function schedule_abandonment( $cart_data ) {
		if ( 'processing' === $cart_data['status'] ) {
			if ( ! WC()->queue()->get_next( 'sxp_mark_abandoned', [ $cart_data['id'] ], 'sxp-ac' ) ) {
				$expire_after = sxp_get_expiration_time_of( 'salexpresso_ac_timeout', ( 15 * MINUTE_IN_SECONDS ) );
				WC()->queue()->schedule_single( time() + $expire_after, 'sxp_mark_abandoned', [ $cart_data['id'] ], 'sxp-ac' );
			}
		}
		
		if ( 'abandoned' === $cart_data['status'] ) {
			if ( ! WC()->queue()->get_next( 'sxp_delete_abandoned', [ $cart_data['id'] ], 'sxp-ac' ) ) {
				$expire_after = sxp_get_expiration_time_of( 'salexpresso_ac_expiration', ( 15 * DAY_IN_SECONDS ) );
				WC()->queue()->schedule_single( time() + $expire_after, 'sxp_delete_abandoned', [ $cart_data['id'] ], 'sxp-ac' );
			}
		}
	}
	
	/**
	 * Mark cart as abandoned.
	 *
	 * @param int $cart_id Abandon Cart ID
	 *
	 * @return void
	 */
	private function clear_countdown( $cart_id ) {
		
		// Clear Mark as abandoned.
		if ( WC()->queue()->get_next( 'sxp_mark_abandoned', [ $cart_id ], 'sxp-ac' ) ) {
			WC()->queue()->cancel( 'sxp_mark_abandoned', [ $cart_id ], 'sxp-ac' );
		}
		
		// Clear Delete abandoned.
		if ( WC()->queue()->get_next( 'sxp_delete_abandoned', [ $cart_id ], 'sxp-ac' ) ) {
			WC()->queue()->cancel( 'sxp_delete_abandoned', [ $cart_id ], 'sxp-ac' );
		}
	}
	
	/**
	 * Mark cart as abandoned.
	 *
	 * @param int $cart_id Abandon Cart ID
	 *
	 * @return void
	 */
	public function mark_cart_as_abandoned( $cart_id ) {
		$cart = $this->get_abandon_cart_by_id( $cart_id );
		if ( 'processing' === $cart['status'] ) {
			// dont' keep cart without email if not allowed in settings.
			if ( empty( $cart['email'] ) && 'yes' !== get_option( 'salexpresso_ac_keep_no_email', 'no' ) ) {
				$this->delete_abandon_cart( $cart_id );
			} else {
				$cart['status'] = 'abandoned';
				$this->maybe_update_cart( $cart );
			}
		}
	}
	
	/**
	 * Delete abandoned.
	 *
	 * @param int $cart_id Abandon Cart ID
	 *
	 * @return void
	 */
	public function delete_abandoned( $cart_id ) {
		$cart = $this->get_abandon_cart_by_id( $cart_id );
		if ( 'abandoned' === $cart['status'] ) {
			$this->delete_abandon_cart( $cart_id );
		}
	}
	
	/**
	 * Handle ajax request for saving checkout form data for abandon cart.
	 *
	 * @return void
	 */
	public function save_checkout_form_data() {
		check_ajax_referer( 'sxp-front-request' );
		$tracker = SXP_Tracker::get_instance();
		$data = sxp_deep_clean( $_POST );
		$email = isset( $data['email'] ) ? sanitize_email( $data['email'] ) : '';
		if ( is_email( $email ) ) {
			unset( $data['email'], $data['_wpnonce'] );
			$active_abandon_cart = $this->get_active_abandon_cart( $tracker->get_customer_id() );
			if ( $active_abandon_cart ) {
				$active_abandon_cart['email'] = $email;
				if ( ! empty( $active_abandon_cart['cart_meta'] ) ) {
					$active_abandon_cart['cart_meta'] = maybe_unserialize( $active_abandon_cart['cart_meta'] );
				} else {
					$active_abandon_cart['cart_meta'] = [];
				}
				$active_abandon_cart['cart_meta'] = wp_parse_args( $data, $active_abandon_cart['cart_meta'] );
				$this->maybe_update_cart( $active_abandon_cart );
			}
		}
		
		wp_send_json_success();
		die();
	}
	
	/**
	 * Handle add to cart, update cart item qty, remove from cart and undo remove from cart etc.
	 *
	 * @return void
	 */
	public function on_add_to_cart_action() {
		
		$tracker = SXP_Tracker::get_instance();
		$visitor_id = $tracker->get_customer_id();
		// don't capture if disabled of user or any cart calculation request from the admin (only run on frontend).
		if ( sxp_is_abandon_cart_disabled_for_user() || empty( $visitor_id ) || is_admin() ) {
			return;
		}
		
		$cart = $this->get_cart_data();
		
		if ( ! $cart ) {
			return;
		}
		
		$data = [
			'visitor_id'    => $visitor_id,
			'session_id'    => $tracker->get_session_id(),
			'cart_contents' => maybe_serialize( $cart['items'] ),
			'cart_count'    => count( $cart['items'] ),
			'cart_total'    => $cart['total'],
			'cart_meta'     => $cart['meta'],
			'status'        => 'processing',
		];
		
		$customer_meta = [];
		
		if ( is_user_logged_in() ) {
			$user_id = get_current_user_id();
		} else {
			$user_id = sxp_get_user_by_meta( '_sxp_cookie_id', $visitor_id, true );
		}
		
		if ( $user_id ) {
			try {
				$customer = new WC_Customer( $user_id );
				if ( $customer->get_id() ) {
					$data['email'] = $customer->get_billing_email();
					$meta = [
						'email',
						'first_name',
						'last_name',
						'company',
						'country',
						'address_1',
						'address_2',
						'city',
						'state',
						'postcode',
						'phone',
					];
					foreach ( $meta as $k ) {
						$method = 'get_billing_' . $k;
						if ( method_exists( $customer, $method ) ) {
							$customer_meta[ $k ] = $customer->$method();
						}
					}
				}
			} catch ( Exception $e ) {
				$user = get_user_by( 'id', $user_id );
				if ( $user->ID ) {
					$data['email'] = $user->user_email;
					$customer_meta = [
						'first_name' => $user->first_name,
						'last_name' => $user->last_name,
					];
				}
			}
		}
		
		$data['cart_meta'] = wp_parse_args( $customer_meta, $data['cart_meta'] );
		
		$active_abandon = $this->get_active_abandon_cart( $visitor_id, [ 'id', 'status', 'email', 'cart_meta' ] );
		if ( $active_abandon ) {
			// Set Refs.
			$data['id']     = $active_abandon['id'];
			$data['status'] = $active_abandon['status'];
			
			// Set missing data from previous capture.
			if ( ! isset( $data['email'] ) || ( isset( $data['email'] ) && empty( $data['email'] ) ) ) {
				$data['email'] = $active_abandon['email'];
			}
			if ( ! isset( $data['cart_meta'] ) || ( isset( $data['cart_meta'] ) && empty( $data['cart_meta'] ) ) ) {
				$data['cart_meta'] = maybe_unserialize( $active_abandon['cart_meta'] );
			}
			if ( 'processing' === $active_abandon['status'] ) {
				// Reset Countdown
				$this->clear_countdown( $active_abandon['id'] );
			}
		}
		
		$this->maybe_update_cart( $data );
	}
	
	public function maybe_restore_abandon_cart() {
		if ( is_admin() ) {
			return;
		}
		
		if ( isset( $_REQUEST['sxp_restore_ac'] ) ) {
			// @TODO decode restore token and get cart by the token
		} else {
			// restore in-case-of wc session cleared cart for the user due to inactivity but the user has abandon cart.
			$tracker = SXP_Tracker::get_instance();
			$visitor_id = $tracker->get_customer_id();
			// don't capture if disabled of user or any cart calculation request from the admin (only run on frontend).
			if ( sxp_is_abandon_cart_disabled_for_user() || empty( $visitor_id ) ) {
				return;
			}
			$active_abandon = $this->get_active_abandon_cart( $visitor_id, '' );
			if ( WC()->cart->is_empty() && $active_abandon && 'abandoned' === $active_abandon['status'] && $active_abandon['cart_contents'] ) {
				$active_abandon['cart_contents'] = maybe_unserialize( $active_abandon['cart_contents'] );
				$this->restore_abandon_cart( $active_abandon['cart_contents'] );
			}
		}
	}
	
	/**
	 * Delete or update abandon cart status on new order.
	 *
	 * @param int $order_id The Order ID.
	 *
	 * @return void
	 */
	public function on_new_order( $order_id ) {
		$tracker = SXP_Tracker::get_instance();
		if ( isset( WC()->session ) && $tracker->get_customer_id() && ! is_admin() ) {
			$order = wc_get_order( $order_id );
			$active_abandon = $this->get_active_abandon_cart( $tracker->get_customer_id() );
			// no abandon cart data don't continue.
			if ( $active_abandon ) {
				// if the cart isn't abandoned (active) just delete it.
				if ( 'abandoned' !== $active_abandon['status'] ) {
					$this->delete_abandon_cart( $active_abandon['id'] );
				}
				// reference abandon cart id for later use.
				update_post_meta( $order_id, '__sxp_abandon_id', $active_abandon['id'] );
				update_post_meta( $order_id, '__sxp_abandon_status', $active_abandon['status'] );
				// on new order order status can be completed, processing, on-hold
				// cod and other online payment method sets order status to 'processing' and
				// check payment-method sets order status to 'on-hold'.
				if(
					( 'completed' === $order->get_status() || 'processing' === $order->get_status() ) ||
					'check' === $order->get_payment_method() && 'on-hold' === $order->get_status()
				) {
					$this->handle_order_update( $order, $active_abandon, 'recovered' );
				}
			}
		}
	}
	
	/**
	 * Delete or update abandon cart status on order status changed.
	 *
	 * @param int    $order_id   The Order ID.
	 * @param string $old_status Old Status.
	 * @param string $new_status New Status.
	 *
	 * @return void
	 */
	public function on_order_status_changed( $order_id, $old_status, $new_status ) {
		if( 'completed' === $new_status || 'processing' === $new_status) {
			$order = wc_get_order( $order_id );
			$abandon_id = get_post_meta( $order_id, '__sxp_abandon_id', true );
			$abandon_cart = $this->get_abandon_cart_by_id( $abandon_id );
			if ( $abandon_cart ) {
				$this->handle_order_update( $order, $abandon_cart, 'completed' );
			}
		}
	}
	
	/**
	 * Change Abandon cart Status on order update.
	 *
	 * @param WC_Order $order
	 * @param array    $abandon_cart
	 * @param string   $status
	 *
	 * @return bool
	 */
	private function handle_order_update( $order, $abandon_cart, $status ) {
		if ( ! in_array( $status, array_keys( $this->get_cart_statuses() ), true ) ) {
			return false;
		}
		
		// update data so we can show some fancy chart for the recovered cart.
		$abandon_cart['order_id']   = $order->get_id();
		$abandon_cart['cart_total'] = $order->get_total();
		$abandon_cart['status']     = $status;
		update_post_meta( $order->get_id(), '__sxp_abandon_status', $abandon_cart['status'] );
		
		return false !== $this->maybe_update_cart( $abandon_cart );
	}
	
	/**
	 * Update Abandon cart data.
	 *
	 * @param array $data The data.
	 *
	 * @return bool|int
	 */
	private function maybe_update_cart( $data ) {
		global $wpdb;
		$old    = false;
		$update = false;
		$defaults = [
			'email'         => '',
			'visitor_id'    => '',
			'session_id'    => '',
			'cart_contents' => '',
			'order_id'      => '',
			'cart_total'    => '',
			'cart_meta'     => '',
			'status'        => 'processing',
			'coupon_code'   => '',
			'last_campaign' => '',
			'created'       => '',
			'updated'       => '',
			'unsubscribed'  => 0,
		];
		
		$data = wp_parse_args( $data, $defaults );
		
		$old_id = isset( $data['id'] ) && ! empty( $data['id'] ) ? absint( $data['id'] ) : false;
		
		if ( $old_id ) {
			/** @noinspection SqlResolve */
			$old    = $wpdb->get_row(
				$wpdb->prepare( "SELECT * FROM {$wpdb->sxp_abandon_cart} WHERE 1=1 AND id = %d;", $data['id'] ),
				ARRAY_A
			);
			$update = true;
			unset( $old['id'] );
			unset( $data['id'] );
		}
		
		if ( ! empty( $data['cart_meta'] ) && is_array( $data['cart_meta'] ) ) {
			$data['cart_meta'] = maybe_serialize( $data['cart_meta'] );
		}
		
		if ( ! empty( $data['cart_contents'] ) && is_array( $data['cart_contents'] ) ) {
			$data['cart_contents'] = maybe_serialize( $data['cart_contents'] );
		}
		
		if ( $old === $data ) {
			return false;
		}
		
		if ( empty( $data['created'] ) ) {
			$data['created'] = current_time( 'mysql' );
		}
		
		if ( $update || empty( $data['updated'] ) ) {
			$data['updated'] = current_time( 'mysql' );
		}
		
		$id = false;
		if ( $update ) {
			$saved = $wpdb->update( $wpdb->sxp_abandon_cart, $data, [ 'id' => $old_id ] );
			if ( $saved ) {
				$id         = (int) $old_id;
				$data['id'] = $id;
				do_action( 'salexpresso_abandon_cart_updated', $data );
			}
		} else {
			$saved = $wpdb->insert( $wpdb->sxp_abandon_cart, $data );
			if ( $saved ) {
				$id = (int) $wpdb->insert_id;
				$data['id'] = $id;
				do_action( 'salexpresso_abandon_cart_created', $data );
			}
		}
		
		if ( $saved ) {
			do_action( 'salexpresso_abandon_cart_saved', $data, $update );
		}
		
		return $id;
	}
	
	/**
	 * Get active abundant cart.
	 *
	 * @param int|string $visitor_id_or_email visitor cookie id or email.
	 * @param string|string[] $fields visitor cookie id or email.
	 *
	 * @return array|string|null
	 */
	public function get_active_abandon_cart( $visitor_id_or_email, $fields = '' ) {
		global $wpdb;
		
		$visitor = $this->get_where_user( $visitor_id_or_email );
		$status  = sxp_sql_where_in( 'status', [ 'processing', 'abandoned' ] );
		
		$fields = $this->select_fields( $fields );
		
		$sql = "SELECT {$fields['select']} FROM {$wpdb->sxp_abandon_cart} WHERE 1=1  {$visitor} {$status} ORDER BY id DESC LIMIT 1;";
		
		if ( $fields['single'] ) {
			return $wpdb->get_var( $sql );
		}
		return $wpdb->get_row( $sql, ARRAY_A );
	}
	
	/**
	 * Get abandon cart by id.
	 *
	 * @param int $id Abandon cart id.
	 *
	 * @return array|null
	 */
	public function get_abandon_cart_by_id( $id ) {
		global $wpdb;
		/** @noinspection SqlResolve */
		return $wpdb->get_row(
			$wpdb->prepare(
				"SELECT * FROM {$wpdb->sxp_abandon_cart} WHERE id = %d;",
				absint( $id )
			),
			ARRAY_A
		);
	}
	
	/**
	 * Generate column list for select query.
	 *
	 * @param string|string[] $fields Column names.
	 *
	 * @return array
	 */
	private function select_fields( $fields = '' ) {
		$single = false;
		if ( empty( $fields ) || 'all' === $fields ) {
			$fields = '*';
		} elseif ( is_array( $fields ) ) {
			$fields = array_map( 'esc_sql', $fields );
			$fields = implode( ',', $fields );
		} else {
			$fields = esc_sql( $fields );
			$single = true;
		}
		
		return [ 'select' => $fields, 'single' => $single ];
	}
	
	/**
	 * Delete Abandon cart by id.
	 * @param int $id cart id
	 *
	 * @return false|int
	 */
	private function delete_abandon_cart( $id ) {
		global $wpdb;
		return $wpdb->delete( $wpdb->sxp_abandon_cart, [ 'id' => absint( $id ) ] );
	}
	
	/**
	 * Get where clause for visitor cookie id or email.
	 *
	 * @param int|string $visitor_id_or_email visitor cookie id or email.
	 * @param bool $for_update return data as array or where clause string.
	 * @param string $relation Where clause relation.
	 *
	 * @return array|bool|string|void
	 */
	private function get_where_user( $visitor_id_or_email, $for_update = false, $relation = 'AND' ) {
		if ( is_email( $visitor_id_or_email ) ) {
			$field = 'email';
			$value = sanitize_email( $visitor_id_or_email );
		} else {
			$field = 'visitor_id';
			$value = esc_sql( $visitor_id_or_email );
		}
		if ( ! $value ) {
			return false;
		}
		
		if ( $for_update ) {
			return [ $field => $value ];
		}
		
		global $wpdb;
		
		$relation = 'AND' === strtoupper( $relation ) ? 'AND' : 'OR';
		
		return $wpdb->prepare( " {$relation} {$field} = %s", $value );
	}
	
	/**
	 * Possible Cart status list.
	 * @return array
	 */
	public function get_cart_statuses() {
		return [
			'processing' => esc_html_x( 'Processing', 'Abandon Cart Status', 'salexpresso' ),
			'abandoned'  => esc_html_x( 'Abandoned', 'Abandon Cart Status', 'salexpresso' ),
			'recovered'  => esc_html_x( 'Recovered', 'Abandon Cart Status', 'salexpresso' ),
			'completed'  => esc_html_x( 'Completed', 'Abandon Cart Status', 'salexpresso' ),
			'lost'       => esc_html_x( 'Lost', 'Abandon Cart Status', 'salexpresso' ),
		];
	}
	
	/**
	 * Get Cart Data.
	 * @return array|null
	 */
	private function get_cart_data() {
		if ( WC()->cart->is_empty() ) {
			return null;
		} else {
			$cart_data  = [];
			
			foreach ( WC()->cart->get_cart() as $key => $item ) {
				if ( ! $item['quantity'] || ! $item['data']->exists() ) {
					continue;
				}
				$item_data = [
					'key'               => $key,
					'product_id'        => $item['product_id'],
					'variation_id'      => $item['variation_id'],
					'variation'         => $item['variation'], // do we really need to keep variation array???
					'quantity'          => $item['quantity'],
					'data_hash'         => $item['data_hash'],
					'line_subtotal'     => $item['line_subtotal'],
					'line_subtotal_tax' => $item['line_subtotal_tax'],
					'line_total'        => $item['line_total'],
					'line_tax'          => $item['line_tax'],
				];
				
				// Product extra data, support for 3rdParty Ext.
				unset( $item['key'], $item['product_id'], $item['quantity'], $item['variation_id'] );
				unset( $item['variation'], $item['data_hash'], $item['line_tax_data'], $item['line_subtotal'] );
				unset( $item['line_subtotal_tax'], $item['line_total'], $item['line_tax'], $item['data'] );
				$item_data['extra_data'] = empty( $item ) ? [] : $item;
				
				// Add to the main list.
				$cart_data[] = $item_data;
			}
		}
		
		if ( ! empty( $cart_data ) ) {
			return [
				'items' => $cart_data,
				'total' => WC()->cart->get_total( 'calc' ),
				'meta'  => [
					'subtotal' => WC()->cart->get_subtotal(),
					'discount' => WC()->cart->get_discount_total(),
					'coupons'  => WC()->cart->get_applied_coupons(),
					'shipping' => WC()->cart->get_shipping_total(),
					'tax'      => WC()->cart->get_taxes_total(),
				],
			];
		}
		return  null;
	}
	
	/**
	 * Restore Cart data.
	 *
	 * @param array  $cart_items    Cart Items.
	 * @param string $coupon        Coupon code to apply.
	 * @param bool   $empty_current Empty current Cart.
	 *
	 * @return void
	 * @see SXP_Abandon_Cart::get_cart_data() for available cart item data.
	 */
	private function restore_abandon_cart( $cart_items = [], $coupon = '', $empty_current = true ) {
		global $woocommerce;
		if ( $empty_current ) {
			$woocommerce->cart->empty_cart();
		}
		wc_clear_notices();
		try {
			foreach ( $cart_items as $item ) {
				// WC_Cart doing necessary validation.
				WC()->cart->add_to_cart( $item['product_id'], $item['quantity'], $item['variation_id'], $item['variation'], $item['extra_data'] );
			}
			
			if ( ! empty( $coupon ) ) {
				WC()->cart->add_discount( $coupon );
			}
		} catch ( Exception $e ) {}
		
	}
}
SXP_Abandon_Cart::get_instance();
// End of file class-sxp-abandon-cart.php.
