<?php
/**
 * SaleXpresso
 *
 * @package SaleXpresso\Customer
 * @version 1.0.0
 * @since   SaleXpresso v1.0.0
 */

namespace SaleXpresso\List_Table;

use SaleXpresso\SXP_List_Table;
use SaleXpresso\SXP_Post_Types;
use WP_Term;
use WP_Term_Query;

if ( ! defined( 'ABSPATH' ) ) {
	header( 'Status: 403 Forbidden' );
	header( 'HTTP/1.1 403 Forbidden' );
	die();
}

/**
 * Class SXP_Customer_Group_List_Table
 *
 * @package SaleXpresso
 */
class SXP_Customer_Type_List_Table extends SXP_Customer_Group_List_Table {
	
	/**
	 * The Taxonomy.
	 *
	 * @var string
	 */
	protected $taxonomy_name = SXP_Post_Types::CUSTOMER_TYPE_TAX;
	
	/**
	 * SXP_Customer_Group_Table constructor.
	 *
	 * @param array $args
	 */
	public function __construct( $args = [] ) {
		parent::__construct( wp_parse_args( $args, [
			'singular' => __( 'Customer Tag', 'salexpresso' ),
			'plural'   => __( 'Customer Tag', 'salexpresso' ),
		] ) );
	}
	
	public function get_columns() {
		return [
			'cb'        => '<input type="checkbox" />',
			'name'      => __( 'Customer Type', 'salexpresso' ),
			'assigned'  => __( 'Assigned', 'salexpresso' ),
		];
	}
}
// End of file class-sxp-customer-type-list-table.php
