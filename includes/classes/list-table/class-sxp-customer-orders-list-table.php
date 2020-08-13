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
class SXP_Customer_Orders_List_Table extends SXP_List_Table {
	
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
		$order_by   = 'date';
		$meta_order = '';
		$sort_order = 'DESC';
		$per_page   = 20;
		
		if ( isset( $_GET['orderby'] ) && ! empty( $_GET['orderby'] ) ) {
			$order_by = sanitize_text_field( $_GET['orderby'] );
			if ( 'revenue' === $order_by ) {
				$order_by   = 'meta_value_num';
				$meta_order = '_order_total';
			}
			if ( isset( $_GET['order'] ) && ! empty( $_GET['order'] ) ) {
				$sort_order = sanitize_text_field( $_GET['order'] );
			}
		}
		
		$query = new \WP_Query( [
			'posts_per_page' => $per_page,
			'paged'          => $this->get_pagenum(),
			'orderby'        => $order_by,
			'order'          => $sort_order,
			'meta_key'       => $meta_order,
			'post_type'      => wc_get_order_types( 'view-orders' ),
			'post_status'    => array_keys( wc_get_order_statuses() ),
			'fields'         => 'ids',
			'meta_query' => [
				'relation' => 'AND',
				[
					'key' => '_customer_user',
					'value' => $this->user_id,
				],
			],
		] );
		
		$this->items = array_map( 'wc_get_order', $query->get_posts() );
		
		$this->set_pagination_args( [
			'total_items' => $query->found_posts,
			'per_page'    => $per_page,
		] );
	}
	
	/**
	 * Default Column Callback.
	 *
	 * @param \WC_order $order Data.
	 * @param string $column_name Column Slug.
	 *
	 * @return string
	 */
	public function column_default( $order, $column_name ) {
		switch ( $column_name ) {
			case 'id':
				return sprintf(
					'<a href="%s" class="order-number">%s</a>',
					$order->get_edit_order_url(),
					$order->get_id()
				);
				break;
			case 'products':
				$output = '';
				$order_items = $order->get_items( 'line_item' );
				$item_count = $order->get_item_count( 'line_item' );
				$classes = 'product-list';
				if ( $item_count > 1 ) {
					$classes .= ' multiple';
				}
				$output .= '<ul class="' . $classes . '">';
				$i = 0;
				foreach ( $order_items as $item ) {
					if ( $i > 3 ) {
						break;
					}
					$item = $item->get_product();
					if ( $item ) {
						$output .= '<li>' . $item->get_image() .'</li>';
					}
					$i++;
				}
				$output .= '</ul>' . PHP_EOL;
				$output .= '<div class="product-number">';
				$output .= sprintf(
					esc_html( _n( '%s Product', '%s Products', $item_count, 'salexpresso' ) ),
					$item_count
				);
				$output .= '</div>';
				return $output;
				break;
			case 'date':
				$date = $order->get_date_created();
				return $date ? $date->format( 'M d, Y' ) : '';
				break;
			case 'revenue':
				return wc_price( $order->get_total() + $order->get_discount_total() );
				break;
			case 'net-profit':
				return wc_price( $order->get_total() - $order->get_shipping_total() );
				break;
			case 'status' :
				$status = 'wc-' . $order->get_status();
				return sprintf(
					'<div class="sxp-status sxp-status-%s">%s</div>',
					$this->status_class_map( $status ),
					$this->status_map( $status )
				);
				break;
			default:
				return $this->column_default_filtered( $order, $column_name );
		}
	}
	
	/**
	 * Css Class for status.
	 *
	 * @param string $status Status.
	 *
	 * @return mixed|string
	 */
	private function status_class_map( $status ) {
		$statuses = [
			'wc-on-hold'    => 'default',
			'wc-processing' => 'primary',
			'wc-failed'     => 'danger',
			'wc-cancelled'  => 'danger',
			'wc-pending'    => 'info',
			'wc-refunded'   => 'info',
			'wc-completed'  => 'success',
		];
		$statuses = apply_filters( 'sxp_order_status_classes', $statuses );
		return isset( $statuses[ $status ] ) ? $statuses[ $status ] : '';
	}
	
	/**
	 * Translated status.
	 *
	 * @param string $status Status.
	 *
	 * @return mixed|string
	 */
	private function status_map( $status ) {
		$statuses = wc_get_order_statuses();
		return isset( $statuses[ $status ] ) ? $statuses[ $status ] : '';
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
			'id'         => __( 'Order Id', 'salexpresso' ),
			'products'   => __( 'Products', 'salexpresso' ),
			'date'       => __( 'Date', 'salexpresso' ),
			'revenue'    => __( 'Revenue', 'salexpresso' ),
			'net-profit' => __( 'Net Profit', 'salexpresso' ),
			'status'     => __( 'Status', 'salexpresso' ),
		];
	}
	
	/**
	 * Get Sortable Columns.
	 *
	 * @return array
	 */
	public function get_sortable_columns() {
		return [
			'id'         => [ 'ID' ],
			'date'       => [ 'date', false ],
			'revenue'    => [ 'revenue' ],
		];
	}
}
// End of file class-sxp-customer-orders-list-table.php.
