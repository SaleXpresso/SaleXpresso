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
use WP_Taxonomy;
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
class SXP_Customer_Group_List_Table extends SXP_List_Table {
	
	/**
	 * Page Slug
	 * @var string
	 */
	protected $page;
	
	/**
	 * The Taxonomy.
	 *
	 * @var string
	 */
	protected $taxonomy_name = SXP_Post_Types::CUSTOMER_GROUP_TAX;
	
	/**
	 * The Taxonomy.
	 *
	 * @var WP_Taxonomy
	 */
	protected $taxonomy;
	
	/**
	 * SXP_Customer_Group_Table constructor.
	 *
	 * @param array $args
	 */
	public function __construct( $args = [] ) {
		global $plugin_page;
		$this->page = $plugin_page;
		
		parent::__construct( wp_parse_args( $args, [
			'singular' => __( 'Customer Group', 'salexpresso' ),
			'plural'   => __( 'Customer Groups', 'salexpresso' ),
			'ajax'     => true,
			'screen'   => isset( $args['screen'] ) ? $args['screen'] : null,
			'tab'      => '',
			'tfoot'    => false,
			'table_nav' => [
				'top'        => true,
				'pagination' => true,
				'bottom'     => true,
			],
		] ) );
		
		$this->taxonomy = get_taxonomy( $this->taxonomy_name );
		$this->items    = [];
	}
	
	protected function set_table_actions() {
		
		return [
			[
				'link'  => admin_url( 'admin.php?page=' . $this->page . '&action=add-new' ),
				'type'  => 'default',
				'icon'  => 'plus',
				'label' => $this->taxonomy->labels->add_new_item,
			],
		];
	}
	
	/**
	 * Check if current user can manage user groups.
	 *
	 * @return bool
	 */
	public function ajax_user_can() {
		return current_user_can( get_taxonomy( $this->taxonomy_name )->cap->manage_terms );
	}
	
	/**
	 * Fetch data and prepare the list.
	 * @return void
	 */
	public function prepare_items() {
		// using wc report api data store.
		$per_page   = $this->get_items_per_page( $this->taxonomy_name . '_per_page' );
		$order_by   = 'name';
		$sort_order = 'ASC';
		$search     = '';
		
		// set sorting.
		if ( isset( $_GET['orderby'] ) ) {
			$order_by = sanitize_text_field( $_GET['orderby'] );
		}
		
		if ( isset( $_GET['order'] ) ) {
			$sort_order = 'asc' === strtolower( $_GET['order'] ) ? 'ASC' : 'DESC';
		}
		
		if ( isset( $_GET['s'] ) ) {
			$search = sanitize_text_field( $_GET['s'] );
			$search = preg_replace( '/\s\s+/', ' ', $search );
			$search = trim( $search );
		}
		
		$term_args = [
			'taxonomy'   => $this->taxonomy_name,
			'hide_empty' => false,
			'fields'     => 'all',
			'count'      => true,
			'orderby'    => $order_by,
			'order'      => $sort_order,
			'search'     => $search,
			'number'     => $per_page,
			'offset'     => ( 1 - $this->get_pagenum() ) * $per_page,
		];
		
		$data = new WP_Term_Query( $term_args );
		global $wpdb;
		$sql = preg_replace( '/SELECT .+ FROM(.*)LIMIT.*/i', 'SELECT  COUNT(*) as total FROM$1', $data->request );
		$total = (array) $wpdb->get_row( $sql );
		$this->items = $data->get_terms();
		
		$this->set_pagination_args( [
			'total_items' => reset( $total ),
			'per_page'    => $per_page,
		] );
	}
	
	/**
	 * Set the bulk actions
	 * @return array
	 */
	protected function get_bulk_actions() {
		$actions = [];
		if ( current_user_can( get_taxonomy( $this->taxonomy_name )->cap->delete_terms ) ) {
			$actions['delete'] = [ __( 'Delete', 'salexpresso' ), 'trash' ];
		}
		
		return $actions;
	}
	
	/**
	 * CB Column Callback.
	 *
	 * @param WP_Term $item Term.
	 *
	 * @return string
	 */
	public function column_cb( $item ) {
		return sprintf(
			'<label class="screen-reader-text" for="term_%1$s">%2$s</label>' .
			'<input type="checkbox" name="term_ids[]" id="term_%1$s" class="%3$s" value="%1$s" />',
			$item->term_id,
			/* translators: %s: Taxonomy Term Name. */
			sprintf( __( 'Select %s', 'salexpresso' ), $item->name ),
			'select-term ' . esc_attr( $this->taxonomy_name )
		);
	}
	
	/**
	 * Default Column Callback.
	 *
	 * @param WP_Term $item Term.
	 * @param string $column_slug Column Slug.
	 *
	 * @return string
	 */
	public function column_default( $item, $column_slug ) {
		switch ( $column_slug ) {
			case 'name':
				return sprintf(
					'<a href="%s" style="background: %s">%s</a>',
					admin_url( 'admin.php?page=' . $this->page . '&action=edit&id=' . $item->term_id ),
					sxp_get_term_background_color( $item ),
					$item->name
				);
				break;
			case 'assigned':
				return $item->count;
				break;
			default:
				return apply_filters( "manage_{$this->screen->id}_{$column_slug}_column_content", '', $item );
				break;
		}
	}
	
	public function get_columns() {
		return [
			'cb'        => '<input type="checkbox" />',
			'name'      => __( 'Customer Group', 'salexpresso' ),
			'assigned'  => __( 'Assigned', 'salexpresso' ),
		];
	}
	
	/**
	 * Columns to make sortable.
	 *
	 * @return array
	 */
	public function get_sortable_columns() {
		return [
			'name'     => [ 'name', true ],
			'assigned' => [ 'count' ],
		];
	}
}
// End of file class-sxp-customer-group-list-table.php
