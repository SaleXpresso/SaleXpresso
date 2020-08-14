<?php
/**
 * SaleXpresso Abandon Cart List Table.
 *
 * @package SaleXpresso\List_Table
 * @version 1.0.0
 * @since   SaleXpresso v1.0.0
 */

namespace SaleXpresso\List_Table;

use SaleXpresso\AbandonCart\SXP_Abandon_Cart;
use SaleXpresso\SXP_List_Table;

if ( ! defined( 'ABSPATH' ) ) {
	header( 'Status: 403 Forbidden' );
	header( 'HTTP/1.1 403 Forbidden' );
	die();
}

/**
 * Class SXP_Abandon_Cart_List_Table
 *
 * @package SaleXpresso
 */
class SXP_Abandon_Cart_List_Table extends SXP_List_Table {
	
	/**
	 * Cart Statuses list.
	 * @var array
	 */
	private $statuses = [];
	/**
	 * SXP_Abandon_Cart_List_Table constructor.
	 *
	 * @param array|string $args Optional. List Table Options.
	 */
	public function __construct( $args = [] ) {
		parent::__construct( wp_parse_args( $args, [
			'singular' => __( 'Abandon Cart', 'salexpresso' ),
			'plural'   => __( 'Abandon Carts', 'salexpresso' ),
		] ) );
	}
	
	public function prepare_items() {
		global $wpdb;
		
		$order_by   = 'ID';
		$sort_order = 'ASC';
		$per_page   = $this->get_items_per_page( 'abandon_cart_per_page' );
		$offset     = absint( ( 1 - $this->get_pagenum() ) * $per_page );
		
		if ( isset( $_GET['orderby'] ) && ! empty( $_GET['orderby'] ) ) {
			$order_by = sanitize_text_field( $_GET['orderby'] );
		}
		if ( isset( $_GET['order'] ) && ! empty( $_GET['order'] ) ) {
			$sort_order = sanitize_text_field( $_GET['order'] );
		}
		
		$limit = sxp_sql_limit_offset( $per_page, $offset );
		$order = sxp_sql_order_by( $order_by, $sort_order );
		
		$total = $wpdb->get_var( "SELECT count( * ) FROM {$wpdb->sxp_abandon_cart}" );
		$this->items = $wpdb->get_results(
			"
			SELECT * FROM {$wpdb->sxp_abandon_cart}
			{$order}
			{$limit}
			"
		);
		
		$this->statuses = SXP_Abandon_Cart::get_instance()->get_cart_statuses();
		
		$this->set_pagination_args( [
			'total_items' => $total,
			'per_page'    => $per_page,
		] );
	}
	
	/**
	 * Default Column Callback.
	 *
	 * @param object $item Cart data.
	 * @param string $column_name Column Slug.
	 *
	 * @return string
	 */
	public function column_default( $item, $column_name ) {
		$item->cart_meta = maybe_unserialize( $item->cart_meta );
		switch ( $column_name ) {
			case 'customer':
				
				$url = '#';
				$state   = isset( $item->cart_meta['state'] ) ? $item->cart_meta['state'] : '';
				$country = isset( $item->cart_meta['country'] ) ? $item->cart_meta['country'] : '';
				$name = '';
				$address = '';
				
				if  ( isset( $item->cart_meta['first_name'], $item->cart_meta['last_name'] ) ) {
					$name = trim( $item->cart_meta['first_name'] . ' ' . $item->cart_meta['last_name'] );
				}
				if ( $item->email ) {
					$user = get_user_by( 'email', $item->email );
				} else {
					$uid = sxp_get_user_by_meta( '_sxp_cookie_id', $item->visitor_id, true );
					$user = get_user_by( 'id', $uid );
				}
				if ( $user ) {
					$url = add_query_arg(
						[
							'page'     => 'sxp-customer',
							'customer' => $user->ID,
							'tab'      => 'active-cart',
						],
						admin_url( 'admin.php' )
					);
				} else {
					if ( $item->email ) {
						$url = 'mailto:' . $item->email;
					}
				}
				if ( ! empty( $state ) && ! empty( $country ) ) {
					$address = WC()->countries->get_formatted_address(
						[
							'state'   => $state,
							'country' => $country,
						],
						' '
					);
				}
				
				return sprintf(
					'<div class="sxp-customer-desc">
							<div class="sxp-customer-desc-thumbnail">%s</div><!-- end .sxp-customer-desc-thumbnail -->
							<div class="sxp-customer-desc-details">
								<a href="%s">%s</a>
								<p class="sxp-customer-desc-details-location">%s</p>
							</div><!-- end .sxp-customer-desc-detaisl -->
						</div><!-- end .sxp-customer-desc -->',
					sprintf( '<a href="%s">%s</a>', $url, get_avatar( $item->email, 40, '', $name ) ),
					$url,
					$name ? $name : $item->email,
					$address
				);
				break;
			case 'email':
				return $item->email;
				break;
			case 'count':
				$item->cart_contents = maybe_unserialize( $item->cart_contents );
				return count( $item->cart_contents );
				break;
			case 'amount':
				return wc_price( $item->cart_total, [] );
				break;
			case 'status' :
				return isset( $this->statuses[ $item->status ] ) ? $this->statuses[ $item->status ] : ucfirst( $item->status );
				break;
			case 'created' :
				return gmdate( 'm D, Y', strtotime( $item->created ) );
				break;
			default:
				return $this->column_default_filtered( $item, $column_name );
		}
	}
	
	/**
	 * CB Column Renderer.
	 *
	 * @param Object $item Data.
	 *
	 * @return string
	 */
	public function column_cb( $item ) {
		return sprintf(
			'<label class="screen-reader-text" for="abandon_cart_%1$s">%2$s</label>' .
			'<input type="checkbox" name="abandon_carts[]" id="abandon_cart_%1$s" class="%3$s" value="%1$s" />',
			$item->id,
			/* translators: %s: Abandon Cart ID. */
			sprintf( __( 'Select %s', 'salexpresso' ), $item->id ),
			'select-abandon-cart'
		);
	}
	
	/**
	 * Get Default Columns.
	 *
	 * @return array
	 */
	public function get_columns() {
		return [
			'cb'       => '<input type="checkbox" />',
			'customer' => __( 'Customer', 'salexpresso' ),
			'email'    => __( 'Email', 'salexpresso' ),
			'count'    => __( 'No. of Product', 'salexpresso' ),
			'amount'   => __( 'Amount', 'salexpresso' ),
			'status'   => __( 'Status', 'salexpresso' ),
			'created'  => __( 'Date', 'salexpresso' ),
		];
	}
	
	public function get_sortable_columns() {
		return [
			'email'   => [ 'email' ],
			'count'   => [ 'cart_count' ],
			'amount'  => [ 'cart_total' ],
			'status'  => [ 'status' ],
			'created' => [ 'created' ],
		];
	}
}
// End of file class-sxp-abandon-cart-list-table.php.
