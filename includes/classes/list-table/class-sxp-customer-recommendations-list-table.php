<?php
/**
 * SaleXpresso Abandon Cart List Table.
 *
 * @package SaleXpresso\List_Table
 * @version 1.0.0
 * @since   SaleXpresso v1.0.0
 */

namespace SaleXpresso\List_Table;

use SaleXpresso\RecommendationsEngine\RecommendationEngineException;
use SaleXpresso\SXP_List_Table;
use SaleXpresso\RecommendationsEngine\SXP_Recommendation_Engine;
use WC_Product;

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
class SXP_Customer_Recommendations_List_Table extends SXP_List_Table {
	
	private $no_items;
	/**
	 * User ID.
	 * @var int
	 */
	private $user_id;
	
	/**
	 * SXP_Abandon_Cart_List_Table constructor.
	 *
	 * @param array|string $args Optional. List Table Options.
	 */
	public function __construct( $args = [] ) {
		parent::__construct(
			[
				'singular'         => __( 'recommendation', 'salexpresso' ),
				'plural'           => __( 'recommendations', 'salexpresso' ),
				'ajax'             => false,
				'screen'           => isset( $args['screen'] ) ? $args['screen'] : null,
				'tab'              => '',
				'tfoot'            => false,
				'table_top'        => false,
				'table_pagination' => true,
				'table_bottom'     => true,
			]
		);
	}
	
	public function set_data( $user_id ) {
		$this->user_id = $user_id;
	}
	
	public function prepare_items() {
		$per_page   = 20;
		$sort_order = 'DESC';
		// order by the post id sequence return from the recommendation engine.
		$order_by   = 'include';
		$meta_key = '';
		$meta_type = '';
		
		if ( isset( $_GET['orderby'] ) && ! empty( $_GET['orderby'] ) ) {
			$order_by = sanitize_text_field( $_GET['orderby'] );
			if ( 'regular-price' === $order_by ) {
				$order_by = 'meta_value_num';
				$meta_key = '_regular_price';
				$meta_type = 'NUMERIC';
			}
			
			// WC doesn't keep the _sale_price meta in some product that doesn't have the sale price.
			// So sorting by sale price in sql doesn't work all the time.
			
			if ( 'sale-price' === $order_by ) {
				$order_by = 'meta_value_num';
				$meta_key = '_sale_price';
				$meta_type = 'NUMERIC';
			}
			
			if ( 'status' === $order_by ) {
				$order_by = 'meta_value_num';
				$meta_key = '_stock_status';
			}
			
			if ( isset( $_GET['order'] ) && ! empty( $_GET['order'] ) ) {
				$sort_order = sanitize_text_field( $_GET['order'] );
			}
		}
		
		$this->no_items = esc_html__( 'No items found.', 'salexpresso' );
		try {
			$this->items = SXP_Recommendation_Engine::get_instance()->get_recommendation_for( $this->user_id );
			if ( ! empty( $this->items ) ) {
				$total = count( $this->items );
				$query = new \WC_Product_Query( [
					'limit'      => $per_page,
					'page'       => $this->get_pagenum(),
					'include'    => $this->items,
					'meta_key'   => $meta_key,
					'meta_type'  => $meta_type,
					'orderby'    => $order_by,
					'order'      => $sort_order,
				] );
				$this->items = $query->get_products();
				
				$this->set_pagination_args( [
					'total_items' => $total,
					'per_page'    => $per_page,
				] );
			} else {
				$this->items = [];
			}
		} catch ( RecommendationEngineException $e ) {
			$this->no_items = esc_html( $e->getMessage() );
		}
		
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
	 * Default Column Callback.
	 *
	 * @param WC_Product $item Cart data.
	 * @param string $column_name Column Slug.
	 *
	 * @return string
	 */
	public function column_default( $item, $column_name ) {
		switch ( $column_name ) {
			case 'product':
				return sprintf(
					'<div class="product-thumb">%s</div><div class="product-name">%s</div>',
					$item->get_image(),
					$item->get_name()
				);
				break;
			case 'regular-price':
				return wc_price( $item->get_regular_price() );
				break;
			case 'sale-price':
				return wc_price( $item->get_sale_price() );
				break;
			case 'date' :
				return $item->get_date_created()->format( 'M d, Y' );
				break;
			case 'status' :
				$status = $item->get_stock_status();
				return sprintf(
					'<div class="sxp-status sxp-status-%s">%s</div>',
					$this->status_class_map( $status ),
					$this->status_map( $status )
				);
				break;
			default:
				return $this->column_default_filtered( $item, $column_name );
		}
	}
	
	/**
	 * CB Column Renderer.
	 *
	 * @param WC_Product $item Data.
	 *
	 * @return string
	 */
	public function column_cb( $item ) {
		return sprintf(
			'<label class="screen-reader-text" for="product_%1$s">%2$s</label>' .
			'<input type="checkbox" name="products[]" id="product_%1$s" class="%3$s" value="%1$s" />',
			$item->get_id(),
			/* translators: %s: Product Display Name. */
			sprintf( __( 'Select %s', 'salexpresso' ), $item->get_name() ),
			'select-product'
		);
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
			'outofstock'  => 'danger',
			'onbackorder' => 'info',
			'instock'     => 'success',
		];
		$statuses = apply_filters( 'sxp_product_stock_status_classes', $statuses );
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
		$statuses = wc_get_product_stock_status_options();
		return isset( $statuses[ $status ] ) ? $statuses[ $status ] : '';
	}
	
	/**
	 * Message to be displayed when there are no items
	 */
	public function no_items() {
		echo $this->no_items; // PHPCS: XSS ok.
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
			'cb'            => '<input type="checkbox" />',
			'product'       => __( 'Product', 'salexpresso' ),
			'regular-price' => __( 'Regular Price', 'salexpresso' ),
			'sale-price'    => __( 'Sale Price', 'salexpresso' ),
			'date'          => __( 'Created', 'salexpresso' ),
			'status'        => __( 'Status', 'salexpresso' ),
		];
	}
	
	public function get_sortable_columns() {
		return [
			'product'       => [ 'title' ],
			'regular-price' => [ 'regular-price' ],
			'date'          => [ 'date' ],
			'status'        => [ 'status' ],
		];
	}
}
// End of file class-sxp-customer-recommendations-list-table.php.
