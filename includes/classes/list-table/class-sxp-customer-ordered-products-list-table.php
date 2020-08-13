<?php
/**
 * SaleXpresso
 *
 * @package SaleXpresso\Customer
 * @version 1.0.0
 * @since   SaleXpresso v1.0.0
 */

namespace SaleXpresso\List_Table;

use Exception;
use SaleXpresso\Analytics\SXP_Analytics_User_Data;
use SaleXpresso\SXP_List_Table;
use Automattic\WooCommerce\Admin\API\Reports\TimeInterval;
use Automattic\WooCommerce\Admin\API\Reports\Customers\DataStore as CustomerReportDataStore;
use WC_DateTime;

if ( ! defined( 'ABSPATH' ) ) {
	header( 'Status: 403 Forbidden' );
	header( 'HTTP/1.1 403 Forbidden' );
	die();
}

/**
 * Class SXP_Customer_List_Table
 *
 * @package SaleXpresso\Customer
 */
class SXP_Customer_Ordered_Product_List_Table extends SXP_List_Table {
	
	/**
	 * UserID.
	 * @var int
	 */
	protected $user_id;
	
	/**
	 * SXP_Customer_List_Table constructor.
	 *
	 * @param array|string $args List table params.
	 */
	public function __construct( $args = array() ) {
		
		parent::__construct(
			[
				'singular'  => __( 'customer orders', 'salexpresso' ),
				'plural'    => __( 'customer orders', 'salexpresso' ),
				'ajax'      => false,
				'screen'    => isset( $args['screen'] ) ? $args['screen'] : null,
				'tab'       => '',
				'tfoot'     => false,
				'table_top' => false,
				'table_pagination' => true,
				'table_bottom' => true,
			]
		);
		
		$this->items = [];
		$this->host = sxp_get_host_name( site_url() );
	}
	
	/**
	 * Get column info.
	 *
	 * @return array
	 */
	protected function get_column_info() {
		// $_column_headers is already set / cached.
		if ( isset( $this->_column_headers ) && is_array( $this->_column_headers ) ) {
			// Back-compat for list tables that have been manually setting $_column_headers for horse reasons.
			// In 4.3, we added a fourth argument for primary column.
			$column_headers = array( array(), array(), array(), $this->get_primary_column_name() );
			foreach ( $this->_column_headers as $key => $value ) {
				$column_headers[ $key ] = $value;
			}
			
			return $column_headers;
		}
		
		$columns = $this->get_columns();
		$hidden  = [];
		
		$_sortable = $this->get_sortable_columns();
		
		$sortable = [];
		foreach ( $_sortable as $id => $data ) {
			if ( empty( $data ) ) {
				continue;
			}
			
			$data = (array) $data;
			if ( ! isset( $data[1] ) ) {
				$data[1] = false;
			}
			
			$sortable[ $id ] = $data;
		}
		
		$this->_column_headers = [
			$columns,
			$hidden,
			$sortable,
			$this->get_primary_column_name(),
		];
		
		return $this->_column_headers;
	}
	
	/**
	 * Set Data.
	 *
	 * @param int $user_id User ID.
	 * @param SXP_Analytics_User_Data $analytics Analytics Class.
	 */
	public function set_data( $user_id ) {
		$this->user_id = $user_id;
	}
	
	/**
	 * Prepares the list of items for displaying.
	 *
	 * @param SXP_Analytics_User_Data
	 *
	 * @return void
	 */
	public function prepare_items() {
		$order_by   = 'quantity';
		$sort_order = 'DESC';
		$per_page   = 20;
		
		if ( isset( $_GET['orderby'] ) && ! empty( $_GET['orderby'] ) ) {
			$order_by = sanitize_text_field( $_GET['orderby'] );
			if ( isset( $_GET['order'] ) && ! empty( $_GET['order'] ) ) {
				$sort_order = sanitize_text_field( $_GET['order'] );
			}
		}
		
		$order = sxp_sql_order_by( $order_by, $sort_order );
		$limit = sxp_sql_limit_offset( $per_page, absint( ( 1 - $this->get_pagenum() ) * $per_page ) );
		global $wpdb;
		
		$sql         = "SELECT
			order_id, product_id as product, variation_id as variation, SUM(product_qty) as quantity, SUM(product_gross_revenue) as revenue, SUM(product_net_revenue) as net_profit
		FROM {$wpdb->prefix}wc_order_product_lookup as opl
		LEFT JOIN {$wpdb->prefix}wc_customer_lookup as cl ON opl.customer_id = cl.customer_id
		WHERE cl.user_id = %d
		GROUP by product_id, variation_id
		";
		$sql         = $wpdb->prepare( $sql, $this->user_id );
		$total       = $wpdb->get_var( "SELECT COUNT(*) FROM ( {$sql} ) AS tt" );
		$this->items = $wpdb->get_results(
			"
			{$sql}
			{$order}
			{$limit}
			"
		);
		
		$this->set_pagination_args( [
			'total_items' => $total,
			'per_page'    => $per_page,
		] );
	}
	
	/**
	 * Default Column Callback.
	 *
	 * @param object $item Data.
	 * @param string $column_name Column Slug.
	 *
	 * @return string
	 */
	public function column_default( $item, $column_name ) {
		switch ( $column_name ) {
			case 'product':
				$product = wc_get_product( $item->product );
				if ( $product ) {
					$variation = wc_get_product( $item->variation );
					return sprintf(
						'<div class="product-wrap">
						<div class="product-thumb">
							<a href="%3$s">%1$s</a>
						</div>
						<div class="product-name"><a href="%3$s">%2$s</a></div>
					</div><!--product-wrap -->',
						$product->get_image(),
						$variation ? $variation->get_name() : $product->get_name(),
						esc_url( get_edit_post_link( $product->get_id() ) )
					);
				}
				return $item->product;
				break;
			case 'quantity':
				return $item->quantity;
				break;
			case 'revenue':
				return wc_price( $item->revenue );
				break;
			case 'net_profit':
				return wc_price( $item->net_profit );
				break;
			default:
				return $this->column_default_filtered( $item, $column_name );
		}
	}
	
	/**
	 * Table classes.
	 *
	 * @return array|string[]
	 */
	protected function get_table_classes() {
		$parent_classes = parent::get_table_classes();
		$parent_classes[] = 'sxp-customer-profile-table';
		return $parent_classes;
	}
	
	/**
	 * Get Default Columns.
	 *
	 * @return array
	 */
	public function get_columns() {
		return [
			'product'    => __( 'Product', 'salexpresso' ),
			'quantity'   => __( 'Quantity', 'salexpresso' ),
			'revenue'    => __( 'Revenue', 'salexpresso' ),
			'net_profit' => __( 'Net Profit', 'salexpresso' ),
		];
	}
	
	/**
	 * Get Sortable Columns.
	 *
	 * @return array
	 */
	public function get_sortable_columns() {
		return [
			'product'  => [ 'product' ],
			'revenue'  => [ 'revenue' ],
			'quantity' => [ 'quantity', false ],
			'net_profit' => [ 'net_profit' ],
		];
	}
}
// End of file class-sxp-customer-ordered-products-list-table.php.
