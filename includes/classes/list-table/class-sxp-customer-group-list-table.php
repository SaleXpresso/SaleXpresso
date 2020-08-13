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
	 *
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
	 * @param array|string $args Optional. List Table Options.
	 */
	public function __construct( $args = [] ) {
		global $plugin_page;
		$this->page = $plugin_page;
		
		parent::__construct( wp_parse_args( $args, [
			'singular'  => __( 'Customer Group', 'salexpresso' ),
			'plural'    => __( 'Customer Groups', 'salexpresso' ),
			'ajax'      => true,
			'screen'    => isset( $args['screen'] ) ? $args['screen'] : null,
			'tab'       => '',
			'tfoot'     => false,
			'table_top' => true,
			'table_pagination' => true,
			'table_bottom' => true,
		] ) );
		
		$this->taxonomy = get_taxonomy( $this->taxonomy_name );
		$this->items    = [];
	}
	
	/**
	 * Table Action Buttons.
	 * 
	 * @return array
	 */
	protected function get_table_actions() {
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
	 *
	 * @return void
	 */
	public function prepare_items() {
		// Using wc report api data store.
		$per_page   = $this->get_items_per_page( $this->taxonomy_name . '_per_page' );
		$order_by   = 'name';
		$sort_order = 'ASC';
		$search     = '';
		$meta_key   = '';
		$meta_value = '';
		$meta_type  = '';
		
		// set sorting.
		if ( isset( $_GET['orderby'] ) ) { // phpcs:ignore WordPress.Security.NonceVerification.Recommended
			$_order_by = sanitize_text_field( $_GET['orderby'] ); // phpcs:ignore WordPress.Security.NonceVerification.Recommended
			if ( ! empty( $_order_by ) ) {
				$order_by = esc_sql( $_order_by );
				if ( 'level' === $order_by ) {
					$order_by = 'meta_value_num';
					$meta_key = '_sxp_group_level';
					$meta_type = 'NUMERIC';
				}
			}
		}
		
		if ( isset( $_GET['order'] ) ) { // phpcs:ignore WordPress.Security.NonceVerification.Recommended
			$sort_order = 'asc' === strtolower( $_GET['order'] ) ? 'ASC' : 'DESC'; // phpcs:ignore WordPress.Security.ValidatedSanitizedInput.InputNotSanitized, WordPress.Security.NonceVerification.Recommended
		}
		
		if ( isset( $_GET['s'] ) ) { // phpcs:ignore WordPress.Security.NonceVerification.Recommended
			$search = sanitize_text_field( $_GET['s'] ); // phpcs:ignore WordPress.Security.NonceVerification.Recommended
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
			'meta_key'   => $meta_key,
			'meta_value' => $meta_value,
			'meta_type'  => $meta_type,
		];
		
		$data = new WP_Term_Query( $term_args );
		global $wpdb;
		$sql         = preg_replace( '/SELECT .+ FROM(.*)LIMIT.*/i', 'SELECT  COUNT(*) as total FROM$1', $data->request );
		$total       = (array) $wpdb->get_row( $sql ); // phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery, WordPress.DB.DirectDatabaseQuery.NoCaching, WordPress.DB.PreparedSQL.NotPrepared
		$this->items = $data->get_terms();
		
		$this->set_pagination_args( [
			'total_items' => reset( $total ),
			'per_page'    => $per_page,
		] );
	}
	
	/**
	 * Set the bulk actions
	 *
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
	 * CB Column Renderer.
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
	 * @param string  $column_name Column Slug.
	 *
	 * @return string
	 */
	public function column_default( $item, $column_name ) {
		switch ( $column_name ) {
			case 'name':
				return sprintf(
					'<a href="%s" style="background: %s">%s</a>',
					admin_url( 'admin.php?page=' . $this->page . '&action=edit&id=' . $item->term_id ),
					sxp_get_term_background_color( $item ),
					$item->name
				);
			case 'level':
				return (int) get_term_meta( $item->term_id, '_sxp_group_level', true );
			case 'assigned':
				return $item->count;
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
		$parent_classes[] = 'taxonomy-table';
		return $parent_classes;
	}
	
	/**
	 * Get Default Columns.
	 *
	 * @return array
	 */
	public function get_columns() {
		return [
			'cb'       => '<input type="checkbox" />',
			'name'     => __( 'Customer Group', 'salexpresso' ),
			'level'    => __( 'Level', 'salexpresso' ),
			'assigned' => __( 'Assigned', 'salexpresso' ),
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
			'level'    => [ 'level' ],
		];
	}
}
// End of file class-sxp-customer-group-list-table.php.
