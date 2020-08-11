<?php
/**
 * SaleXpresso Abundant Cart List Table.
 *
 * @package SaleXpresso\List_Table
 * @version 1.0.0
 * @since   SaleXpresso v1.0.0
 */

namespace SaleXpresso\List_Table;

use SaleXpresso\SXP_List_Table;

if ( ! defined( 'ABSPATH' ) ) {
	header( 'Status: 403 Forbidden' );
	header( 'HTTP/1.1 403 Forbidden' );
	die();
}

/**
 * Class SXP_Abundant_Cart_List_Table
 *
 * @package SaleXpresso
 */
class SXP_Abundant_Cart_List_Table extends SXP_List_Table {
	
	/**
	 * SXP_Abundant_Cart_List_Table constructor.
	 *
	 * @param array|string $args Optional. List Table Options.
	 */
	public function __construct( $args = [] ) {
		parent::__construct( wp_parse_args( $args, [
			'singular' => __( 'Abundant Cart', 'salexpresso' ),
			'plural'   => __( 'Abundant Carts', 'salexpresso' ),
		] ) );
	}
	
	public function prepare_items() {
		global $wpdb;
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
			'product_count' => __( 'No. of Product', 'salexpresso' ),
			'amount' => __( 'Amount', 'salexpresso' ),
			'status' => __( 'Status', 'salexpresso' ),
			'Date' => __( 'Date', 'salexpresso' ),
		];
	}
	
	public function get_sortable_columns() {
		return [
			'customer' => [ 'email' ],
			'product_count' => [ 'product_count' ],
			'amount' => [ 'amount' ] ,
			'status' => [ 'status' ],
			'Date' => [ 'date' ],
		];
	}
}
// End of file class-sxp-customer-type-list-table.php.
