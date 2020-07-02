<?php
/**
 * SaleXpresso
 *
 * @package SaleXpresso\Customer
 * @version 1.0.0
 * @since   SaleXpresso v1.0.0
 */

namespace SaleXpresso\Customer;

use SaleXpresso\SXP_List_Table;
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
	private $page;
	
	/**
	 * SXP_Customer_Group_Table constructor.
	 */
	public function __construct() {
		global $plugin_page;
		$this->page = $plugin_page;
		parent::__construct(
			[
				'singular' => __( 'Customer Group', 'salexpresso' ),
				'plural'   => __( 'Customer Groups', 'salexpresso' ),
				'ajax'     => false,
				'screen'   => null,
				'tab'      => '',
				'tfoot'    => false,
				'table_nav' => [
					'top'        => true,
					'pagination' => true,
					'bottom'     => true,
				],
			]
		);
		
		$this->items = [];
		$this->prepare_items();
	}
	
	protected function set_table_actions() {
		
		return [
			[
				'link'  => admin_url( 'admin.php?page=' . $this->page . '&action=add-new' ),
				'type'  => 'default',
				'icon'  => 'plus',
				'label' => __( 'Add New Group', 'salexpresso' ),
			],
		];
	}
	
	public function prepare_items() {
		// using wc report api data store.
		$per_page   = $this->get_items_per_page( 'customer_groups_per_page' );
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
		
		$term_args = array(
			'taxonomy'               => 'user_group',
			'hide_empty'             => false,
			'fields'                 => 'all',
			'count'                  => true,
			'orderby'                => $order_by,
			'order'                  => $sort_order,
			'search'                 => $search,
		);
		$data = new WP_Term_Query( $term_args );
		global $wpdb;
		$total = $wpdb->get_row( preg_replace( '/SELECT .+ FROM/i', 'SELECT  COUNT(*) as total FROM', $data->request ) );
		$this->items = $data->get_terms();
		$this->set_pagination_args( [
			'total_items' => $total,
			'per_page'    => $per_page,
		] );
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
			'<label class="screen-reader-text" for="customer_%1$s">%2$s</label>' .
			'<input type="checkbox" name="groups[]" id="group_%1$s" class="%3$s" value="%1$s" />',
			$item->term_id,
			/* translators: %s: Taxonomy Term Name. */
			sprintf( __( 'Select %s', 'salexpresso' ), $item->name ),
			'select-group'
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
	
	protected function get_table_classes() {
		return [
			'sxp-table',
			'widefat',
			$this->_args['plural'],
			$this->_args['tab'],
			$this->screen_id,
		];
	}
	
	public function old() {
		?>
		<div class="sxp-list-table">
			<div class="sxp-list-table-top">
				<div class="sxp-customer-search">
					<label for="sxp-customer-search" class="screen-reader-text"><?php __('Search Customer', 'salexpresso'); ?></label>
					<input type="text" id="sxp-customer-search" placeholder="Search Customers">
				</div><!-- end .sxp-customer-search -->
				<div class="sxp-customer-btn-wrapper">
					<a href="#" class="sxp-customer-type-btn sxp-btn sxp-btn-default"><i data-feather="plus"></i> Customer Type Rules</a>
					<a href="#" class="sxp-customer-add-btn sxp-btn sxp-btn-primary"><i data-feather="plus"></i> Add New Customer</a>
				</div>
			</div><!-- end .sxp-customer-top-wrapper -->
			<table class="wp-list-table widefat sxp-table sxp-customer-table">
				<thead>
				<tr>
					<td id="cb" class="manage-column column-cb check-column">
						<label class="screen-reader-text" for="cb-select-all-1">Select All</label>
						<input id="cb-select-all-1" type="checkbox">
					</td>
					<th scope="col" id="sxp-customer" class="manage-column column-title column-primary sortable desc"><a href="#">Customer Type</a></th>
					<th scope="col" id="sxp-customer-campaign" class="manage-column column-author"><a href="#">Campaign Running</a></th>
					<th scope="col" id="sxp-customer-assigned" class="manage-column column-categories"><a href="#">Assigned</a></th>
				
				</tr>
				</thead>
				<tbody id="the-list">
				<tr id="sxp-customer-list-1">
					<th scope="row" class="check-column">
						<label class="screen-reader-text" for="cb-select-1">Customer Group</label>
						<input id="cb-select-1" type="checkbox" name="post[]" value="1">
					</th>
					<td class="title column-title has-row-actions column-primary page-title sxp-customer-name" data-colname="sxp-customer">
						<a href="#" style="background: #FFD0D0">B2C</a>
						<button type="button" class="toggle-row"><span class="screen-reader-text">Show more details</span></button>
					</td>
					<td class="sxp-customer-campaign-column" data-colname="Customer Campaign">
						<div class="sxp-customer-compaign-container">
							<ul class="sxp-campaign-list">
								<li><a href="#">Holiday Campaign</a></li>
								<li><a href="">Vip Compaign</a></li>
								<li><a href="">+2</a></li>
							</ul>
						</div><!-- end .sxp-customer-compaign-container -->
					</td>
					<td class="sxp-customer-assigned-column" data-colname="Customer Assigned">799</td>
				</tr><!-- end .sxp-customer-list -->
				
				<tr id="sxp-customer-list-2">
					<th scope="row" class="check-column">
						<label class="screen-reader-text" for="cb-select-1">
							Customer Group
						</label>
						<input id="cb-select-1" type="checkbox" name="post[]" value="1">
					</th>
					<td class="title column-title has-row-actions column-primary page-title sxp-customer-name" data-colname="sxp-customer">
						<a href="#" style="background: #E3FFDA">wholeseller</a>
						<button type="button" class="toggle-row"><span class="screen-reader-text">Show more details</span></button>
					</td>
					<td class="sxp-customer-campaign-column" data-colname="Customer Campaign">
						<div class="sxp-customer-compaign-container">
							<ul class="sxp-campaign-list">
								<li><a href="#">New Year</a></li>
								<li><a href="">+3</a></li>
							</ul>
						</div><!-- end .sxp-customer-compaign-container -->
					</td>
					<td class="sxp-customer-assigned-column" data-colname="Customer Assigned">27</td>
				</tr><!-- end .sxp-customer-list -->
				
				<tr id="sxp-customer-list-3">
					<th scope="row" class="check-column">
						<label class="screen-reader-text" for="cb-select-1">
							Customer Group
						</label>
						<input id="cb-select-1" type="checkbox" name="post[]" value="1">
					</th>
					<td class="title column-title has-row-actions column-primary page-title sxp-customer-name" data-colname="sxp-customer">
						<a href="#" style="background: #FFCFB5">B2B</a>
						<button type="button" class="toggle-row"><span class="screen-reader-text">Show more details</span></button>
					</td>
					<td class="sxp-customer-campaign-column" data-colname="Customer Campaign">
						<div class="sxp-customer-compaign-container">
							<ul class="sxp-campaign-list">
								<li><a href="#">Sports lover</a></li>
								<li><a href="#">Creative People Campaign</a></li>
								<li><a href="#">Special Day Events</a></li>
								<li><a href="">+2</a></li>
							</ul>
						</div><!-- end .sxp-customer-compaign-container -->
					</td>
					<td class="sxp-customer-assigned-column" data-colname="Customer Assigned">27</td>
				</tr><!-- end .sxp-customer-list -->
				
				<tr id="sxp-customer-list-5">
					<th scope="row" class="check-column">
						<label class="screen-reader-text" for="cb-select-1">
							Customer Group
						</label>
						<input id="cb-select-1" type="checkbox" name="post[]" value="1">
					</th>
					<td class="title column-title has-row-actions column-primary page-title sxp-customer-name" data-colname="sxp-customer">
						<a href="#" style="background: #DAE4FF">Distributor</a>
						<button type="button" class="toggle-row"><span class="screen-reader-text">Show more details</span></button>
					</td>
					<td class="sxp-customer-campaign-column" data-colname="Customer Campaign">
						<div class="sxp-customer-compaign-container">
							<ul class="sxp-campaign-list">
								<li><a href="#">Doctor</a></li>
								<li><a href="">+5</a></li>
							</ul>
						</div><!-- end .sxp-customer-compaign-container -->
					</td>
					<td class="sxp-customer-assigned-column" data-colname="Customer Assigned">27</td>
				</tr><!-- end .sxp-customer-list -->
				</tbody>
			</table><!-- end .sxp-customer-table -->
		</div><!-- end .sxp-customer-wrapper -->
		<?php
	}
}
