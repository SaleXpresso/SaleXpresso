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
class SXP_Customer_List_Table extends SXP_List_Table {
	
	/**
	 * SXP_Customer_List_Table constructor.
	 *
	 * @param array|string $args List table params.
	 */
	public function __construct( $args = array() ) {
		
		parent::__construct(
			[
				'singular' => __( 'Customer', 'salexpresso' ),
				'plural'   => __( 'Customers', 'salexpresso' ),
				'ajax'     => false,
				'screen'   => isset( $args['screen'] ) ? $args['screen'] : null,
				'tab'      => '',
				'tfoot'    => false,
				'table_top' => false,
			]
		);
		
		$this->items = [];
	}
	
	/**
	 * Prepares the list of items for displaying.
	 *
	 * @return void
	 */
	public function prepare_items() {
		// using wc report api data store.
		global $wpdb;
		
		$per_page     = $this->get_items_per_page( 'customers_per_page' );
		$order_by     = 'date_last_order';
		$sort_order   = 'DESC';
		
		// Set sorting.
		if ( isset( $_REQUEST['orderby'] ) ) { // phpcs:ignore WordPress.Security.NonceVerification.Recommended, WordPress.Security.ValidatedSanitizedInput.InputNotSanitized
			$_order_by = sanitize_text_field( $_REQUEST['orderby'] ); // phpcs:ignore WordPress.Security.NonceVerification.Recommended, WordPress.Security.ValidatedSanitizedInput.InputNotSanitized
			if ( ! empty( $_order_by ) ) {
				$order_by = esc_sql( $_order_by );
			}
		}
		
		if ( isset( $_REQUEST['order'] ) ) { // phpcs:ignore WordPress.Security.NonceVerification.Recommended, WordPress.Security.ValidatedSanitizedInput.InputNotSanitized
			$sort_order = 'asc' === strtolower( $_REQUEST['order'] ) ? 'ASC' : 'DESC'; // phpcs:ignore WordPress.Security.NonceVerification.Recommended, WordPress.Security.ValidatedSanitizedInput.InputNotSanitized
		}
		
		$count_query = "
		SELECT
		    COUNT(*) as total
		FROM {$wpdb->prefix}wc_customer_lookup
		    LEFT JOIN {$wpdb->prefix}wc_order_stats
		        ON {$wpdb->prefix}wc_customer_lookup.customer_id = {$wpdb->prefix}wc_order_stats.customer_id
	        AND (
                {$wpdb->prefix}wc_order_stats.status NOT IN ( 'wc-trash','wc-pending','wc-cancelled','wc-failed' )
            )
		WHERE
	      1=1
		GROUP BY {$wpdb->prefix}wc_customer_lookup.customer_id
		";
		$sql = "
		SELECT
		       {$wpdb->prefix}wc_customer_lookup.customer_id as id,
		       user_id,
		       username,
		       CONCAT_WS( ' ', first_name, last_name ) as name,
		       email, country, city, state, postcode, date_registered,
		       IF( date_last_active <= \"0000-00-00 00:00:00\", NULL, date_last_active ) AS date_last_active,
		       MAX( wp_wc_order_stats.date_created ) as date_last_order,
		       SUM( CASE WHEN parent_id = 0 THEN 1 ELSE 0 END ) as orders_count,
		       SUM( total_sales ) as total_spend,
		       CASE
		           WHEN SUM( CASE WHEN parent_id = 0 THEN 1 ELSE 0 END ) = 0
		               THEN NULL
		           ELSE
		               SUM( total_sales ) / SUM( CASE WHEN parent_id = 0 THEN 1 ELSE 0 END )
		           END AS avg_order_value
		FROM {$wpdb->prefix}wc_customer_lookup
		    LEFT JOIN {$wpdb->prefix}wc_order_stats
		        ON {$wpdb->prefix}wc_customer_lookup.customer_id = {$wpdb->prefix}wc_order_stats.customer_id
	        AND (
                {$wpdb->prefix}wc_order_stats.status NOT IN ( 'wc-trash','wc-pending','wc-cancelled','wc-failed' )
            )
		WHERE
	      1=1
		GROUP BY {$wpdb->prefix}wc_customer_lookup.customer_id
		ORDER BY {$order_by} {$sort_order}
		LIMIT 0, 10
		";
		
		$db_records_count = (int) $wpdb->get_var(
			$count_query // phpcs:ignore WordPress.DB.PreparedSQL.NotPrepared
		);
		
		$customer_data = $wpdb->get_results(
			$sql, // phpcs:ignore WordPress.DB.PreparedSQL.NotPrepared
			ARRAY_A
		);
		
		
		// Use status_is to filter by order status.
		// Use status_is_not to filter out specific order by status.
		// Valid order status are: trash, pending, failed, cancelled, etc.
		
		$this->items = $customer_data;
		$this->set_pagination_args( [
			'total_items' => $db_records_count,
			'total_pages' => (int) ceil( $db_records_count / $per_page ),
			'per_page'    => $per_page,
		] );
	}
	
	/**
	 * CB Column Renderer.
	 *
	 * @param array|object $item User Data.
	 *
	 * @return string
	 */
	public function column_cb( $item ) {
		return sprintf(
			'<label class="screen-reader-text" for="customer_%1$s">%2$s</label>' .
			'<input type="checkbox" name="users[]" id="customer_%1$s" class="%3$s" value="%1$s" />',
			$item['id'],
			/* translators: %s: User Display Name. */
			sprintf( __( 'Select %s', 'salexpresso' ), $item['name'] ),
			'select-customer'
		);
	}
	
	/**
	 * Default Column Callback.
	 *
	 * @param array  $item Term.
	 * @param string $column_name Column Slug.
	 *
	 * @return string
	 */
	public function column_default( $item, $column_name ) {
		switch ( $column_name ) {
			case 'name':
				$address     = WC()->countries->get_formatted_address(
					[
						'state'   => $item['state'],
						'country' => $item['country'],
					],
					' '
				);
				$profile_tab = '#';
				if ( $item['user_id'] ) {
					$profile_tab = esc_url_raw( admin_url( 'admin.php?page=sxp-customer&tab=customer-profile&customer=' . $item['user_id'] ) );
				}
				
				return sprintf(
					'<div class="sxp-customer-desc">
							<div class="sxp-customer-desc-thumbnail">%s</div><!-- end .sxp-customer-desc-thumbnail -->
							<div class="sxp-customer-desc-details">
								<a href="%s">%s</a>
								<p class="sxp-customer-desc-details-location">%s</p>
							</div><!-- end .sxp-customer-desc-detaisl -->
						</div><!-- end .sxp-customer-desc -->',
					sprintf( '<a href="%s">%s</a>', $profile_tab, get_avatar( $item['email'], 40, '', $item['name'] ) ),
					$profile_tab,
					$item['name'],
					$address
				);
			case 'customers-group':
				$group = sxp_get_user_group( $item['user_id'] );
				if ( ! empty( $group ) && ! is_wp_error( $group ) ) {
					$color = sxp_get_term_background_color( $group );
					
					return sprintf( '<a href="#%s"  style="background: %s">%s</a>', esc_url( $group->term_id ), esc_attr( $color ), esc_html( $group->name ) );
				}
				return '';
			case 'customers-type':
				$type = sxp_get_user_types( $item['user_id'] );
				if ( ! empty( $type ) && ! is_wp_error( $type ) ) {
					$type  = $type[0];
					$color = sxp_get_term_background_color( $type );
					
					return sprintf( '<a href="#%s"  style="background: %s">%s</a>', esc_url( $type->term_id ), esc_attr( $color ), esc_html( $type->name ) );
				}
				return '';
			case 'customer-tag':
				$output = '<ul class="sxp-tag-list">';
				if ( $item['user_id'] ) {
					$tags = sxp_get_user_tags( $item['user_id'] );
					if ( ! empty( $tags ) && ! is_wp_error( $tags ) ) {
						$output .= sprintf( '<li><a href="#%s">%s</a></li>', esc_url( $tags[0]->term_id ), esc_html( $tags[0]->name ) );
						array_shift( $tags );
						if ( ! empty( $tags ) ) {
							$output .= sprintf( '<li><a href="#">%d</a></li>', count( $tags ) );
						}
					}
				} else {
					$output .= sprintf( '<li><a href="#">%s</a></li>', esc_html_x( 'Guest', 'Guest Customer', 'salexpresso' ) );
				}
				$output .= '</ul>';
				return $output;
			case 'orders_count':
				return $item['orders_count'];
			case 'total_spend':
				return wc_price( $item['total_spend'], [ 'ex_tax_label' => false ] );
			case 'date_last_order':
				if ( '0000-00-00 00:00:00' === $item['date_last_order'] || is_null( $item['date_last_order'] ) ) {
					$t_time = '';
					$h_time = '–';
				} else {
					$time      = wc_string_to_timestamp( $item['date_last_order'] );
					$t_time    = gmdate( __( 'Y/m/d g:i:s a', 'salexpresso' ), $time );
					$time_diff = time() - $time;
					
					if ( $time && $time_diff > 0 && $time_diff < DAY_IN_SECONDS ) {
						/* translators: %s: Human-readable time difference. */
						$h_time = sprintf( __( '%s ago', 'salexpresso' ), human_time_diff( $time ) );
					} else {
						$h_time = gmdate( __( 'Y/m/d', 'salexpresso' ), $time );
					}
				}
				
				return '<span title="' . esc_attr( $t_time ) . '">' . esc_html( $h_time ) . '</span>';
			default:
				return $this->column_default_filtered( $item, $column_name );
		}
	}
	
	/**
	 * Get Default Columns.
	 *
	 * @return array
	 */
	public function get_columns() {
		return [
			'cb'              => '<input type="checkbox" />',
			'name'            => __( 'Customers', 'salexpresso' ),
			'customers-group'  => __( 'Customers Group', 'salexpresso' ),
			'customers-type'  => __( 'Customers Type', 'salexpresso' ),
			'customer-tag'    => __( 'Customers Tag', 'salexpresso' ),
			'orders_count'    => __( 'Orders', 'salexpresso' ),
			'total_spend'     => __( 'Revenue', 'salexpresso' ),
			'date_last_order' => __( 'Last Order', 'salexpresso' ),
		];
	}
	
	/**
	 * Columns to make sortable.
	 *
	 * @return array
	 */
	public function get_sortable_columns() {
		return [
			'name'            => [ 'name' ],
			'orders_count'    => [ 'orders_count' ],
			'total_spend'     => [ 'total_spend' ],
			'date_last_order' => [ 'date_last_order', true ],
		];
	}
	
	/**
	 * Old Static Markup.
	 *
	 * @TODO Delete After Customer List Table gets completed.
	 * @return void
	 */
	public function old() {
		// phpcs:disable
		?>
		<div class="sxp-customer-list-wrapper">
			<div class="sxp-customer-top-wrapper">
				<div class="sxp-customer-search">
					<label for="sxp-customer-search" class="screen-reader-text"><?php __( 'Search Customer', 'salexpresso' ); ?></label>
					<input type="text" id="sxp-customer-search" placeholder="Search Customers">
				</div><!-- end .sxp-customer-search -->
				<div class="sxp-customer-btn-wrapper">
					<a href="#" class="sxp-customer-type-btn sxp-btn sxp-btn-default"><i data-feather="plus"></i> Customer Type Rules</a>
					<a href="#" class="sxp-customer-add-btn sxp-btn sxp-btn-primary"><i data-feather="plus"></i> Add New Customer</a>
				</div>
			</div><!-- end .sxp-customer-top-wrapper -->
			<table class="wp-list-table widefat sxp-table">
				<thead>
				<tr>
					<td id="cb" class="manage-column column-cb check-column">
						<label class="screen-reader-text" for="cb-select-all-1">Select All</label>
						<input id="cb-select-all-1" type="checkbox">
					</td>
					<th scope="col" id="sxp-customer-customers" class="manage-column column-categories"><a href="#">Customers</a></th>
					<th scope="col" id="sxp-customer" class="manage-column column-title column-primary sortable desc"><a href="#">Customer Type</a></th>
					<th scope="col" id="sxp-customer-tag" class="manage-column column-author"><a href="#">Customer Tag</a></th>
					<th scope="col" id="sxp-customer-order" class="manage-column column-categories"><a href="#">Orders</a></th>
					<th scope="col" id="sxp-customer-revenue" class="manage-column column-categories"><a href="#">Revenue</a></th>
					<th scope="col" id="sxp-customer-last-order" class="manage-column column-categories"><a href="#">Last Order</a></th>
				</tr>
				</thead>
				<tbody id="the-list">
				<tr id="sxp-customer-list-1" class="sxp-customer-list">
					<th scope="row" class="check-column">
						<label class="screen-reader-text" for="cb-select-1"></label>
						<input id="cb-select-1" type="checkbox" name="post[]" value="1">
					</th>
					<td class="title column-title has-row-actions column-primary page-title sxp-customers-column"
						data-colname="sxp-customer-customers">
						<div class="sxp-customer-desc">
							<div class="sxp-customer-desc-thumbnail">
								<img src="<?php echo esc_url( sxp_get_plugin_uri( 'assets/images/customers/customer1.png' ) ); ?>" alt="Customer Thumbnail">
							</div><!-- end .sxp-customer-desc-thumbnail -->
							<div class="sxp-customer-desc-details">
								<p class="sxp-customer-desc-details-name">Wendy Bell</p>
								<p class="sxp-customer-desc-details-location">Vermont</p>
							</div><!-- end .sxp-customer-desc-detaisl -->
						</div><!-- end .sxp-customer-desc -->
						<button type="button" class="toggle-row"><span class="screen-reader-text">Show more details</span>
						</button>
					</td>
					<td class="sxp-customer-name" data-colname="Customer Type"><a href="#" style="background: #FFD0D0">VIP</a></td>
					<td class="sxp-customer-tag-column" data-colname="Customer Tag">
						<div class="sxp-customer-tag-container">
							<ul class="sxp-tag-list">
								<li><a href="#">Holiday Campaign</a></li>
								<li><a href="">+2</a></li>
							</ul>
						</div><!-- end .sxp-customer-compaign-container -->
					</td>
					<td class="sxp-customer-assigned-column" data-colname="Customer Order">799</td>
					<td class="sxp-customer-revenue-column" data-colname="Customer Revenue">$6910.60</td>
					<td class="sxp-customer-last-order-column" data-colname="Last Order">23 days ago</td>
				</tr><!-- end .sxp-customer-list -->
				<tr id="sxp-customer-list-2" class="sxp-customer-list">
					<th scope="row" class="check-column">
						<label class="screen-reader-text" for="cb-select-1"></label>
						<input id="cb-select-1" type="checkbox" name="post[]" value="1">
					</th>
					<td class="title column-title has-row-actions column-primary page-title sxp-customers-column" data-colname="sxp-customer-customers">
						<div class="sxp-customer-desc">
							<div class="sxp-customer-desc-thumbnail">
								<img src="<?php echo esc_url( sxp_get_plugin_uri( 'assets/images/customers/customer2.png' ) ); ?>" alt="Customer Thumbnail">
							</div><!-- end .sxp-customer-desc-thumbnail -->
							<div class="sxp-customer-desc-details">
								<p class="sxp-customer-desc-details-name">Jane Nguyen</p>
								<p class="sxp-customer-desc-details-location">Vermont</p>
							</div><!-- end .sxp-customer-desc-detaisl -->
						</div><!-- end .sxp-customer-desc -->
						<button type="button" class="toggle-row"><span class="screen-reader-text">Show more details</span>
						</button>
					</td>
					<td class="sxp-customer-name" data-colname="Customer Type"><a href="#" style="background: #E3FFDA">Gold</a></td>
					<td class="sxp-customer-tag-column" data-colname="Customer Tag">
						<div class="sxp-customer-tag-container">
							<ul class="sxp-tag-list">
								<li><a href="#">New Year</a></li>
								<li><a href="">+2</a></li>
							</ul>
						</div><!-- end .sxp-customer-compaign-container -->
					</td>
					<td class="sxp-customer-assigned-column" data-colname="Customer Order">727</td>
					<td class="sxp-customer-revenue-column" data-colname="Customer Revenue">$3535.92</td>
					<td class="sxp-customer-last-order-column" data-colname="Last Order">23 days ago</td>
				</tr><!-- end .sxp-customer-list -->
				<tr id="sxp-customer-list-3" class="sxp-customer-list">
					<th scope="row" class="check-column">
						<label class="screen-reader-text" for="cb-select-1"></label>
						<input id="cb-select-1" type="checkbox" name="post[]" value="1">
					</th>
					<td class="title column-title has-row-actions column-primary page-title sxp-customers-column"
						data-colname="sxp-customer-customers">
						<div class="sxp-customer-desc">
							<div class="sxp-customer-desc-thumbnail">
								<img src="<?php echo esc_url( sxp_get_plugin_uri( 'assets/images/customers/customer3.png' ) ); ?>" alt="Customer Thumbnail">
							</div><!-- end .sxp-customer-desc-thumbnail -->
							<div class="sxp-customer-desc-details">
								<p class="sxp-customer-desc-details-name">Jane Nguyen</p>
								<p class="sxp-customer-desc-details-location">Vermont</p>
							</div><!-- end .sxp-customer-desc-detaisl -->
						</div><!-- end .sxp-customer-desc -->
						<button type="button" class="toggle-row"><span class="screen-reader-text">Show more details</span>
						</button>
					</td>
					<td class="sxp-customer-name" data-colname="Customer Type"><a href="#" style="background: #FFCFB5">Gold</a></td>
					<td class="sxp-customer-tag-column" data-colname="Customer Tag">
						<div class="sxp-customer-tag-container">
							<ul class="sxp-tag-list">
								<li><a href="#">Sports Lover</a></li>
								<li><a href="">+4</a></li>
							</ul>
						</div><!-- end .sxp-customer-compaign-container -->
					</td>
					<td class="sxp-customer-assigned-column" data-colname="Customer Order">727</td>
					<td class="sxp-customer-revenue-column" data-colname="Customer Revenue">$3535.92</td>
					<td class="sxp-customer-last-order-column" data-colname="Last Order">23 days ago</td>
				</tr><!-- end .sxp-customer-list -->
				<tr id="sxp-customer-list-4" class="sxp-customer-list">
					<th scope="row" class="check-column">
						<label class="screen-reader-text" for="cb-select-1"></label>
						<input id="cb-select-1" type="checkbox" name="post[]" value="1">
					</th>
					<td class="title column-title has-row-actions column-primary page-title sxp-customers-column"
						data-colname="sxp-customer-customers">
						<div class="sxp-customer-desc">
							<div class="sxp-customer-desc-thumbnail">
								<img src="<?php echo esc_url( sxp_get_plugin_uri( 'assets/images/customers/customer4.png' ) ); ?>" alt="Customer Thumbnail">
							</div><!-- end .sxp-customer-desc-thumbnail -->
							<div class="sxp-customer-desc-details">
								<p class="sxp-customer-desc-details-name">Jane Nguyen</p>
								<p class="sxp-customer-desc-details-location">Vermont</p>
							</div><!-- end .sxp-customer-desc-detaisl -->
						</div><!-- end .sxp-customer-desc -->
						<button type="button" class="toggle-row"><span class="screen-reader-text">Show more details</span>
						</button>
					</td>
					<td class="sxp-customer-name" data-colname="Customer Type"><a href="#" style="background:  #FFCFB5">Gold</a></td>
					<td class="sxp-customer-tag-column" data-colname="Customer Tag">
						<div class="sxp-customer-tag-container">
							<ul class="sxp-tag-list">
								<li><a href="#">Birthday</a></li>
								<li><a href="">+2</a></li>
							</ul>
						</div><!-- end .sxp-customer-compaign-container -->
					</td>
					<td class="sxp-customer-assigned-column" data-colname="Customer Order">727</td>
					<td class="sxp-customer-revenue-column" data-colname="Customer Revenue">$3535.92</td>
					<td class="sxp-customer-last-order-column" data-colname="Last Order">23 days ago</td>
				</tr><!-- end .sxp-customer-list -->
				<tr id="sxp-customer-list-5" class="sxp-customer-list">
					<th scope="row" class="check-column">
						<label class="screen-reader-text" for="cb-select-1"></label>
						<input id="cb-select-1" type="checkbox" name="post[]" value="1">
					</th>
					<td class="title column-title has-row-actions column-primary page-title sxp-customers-column" data-colname="sxp-customer-customers">
						<div class="sxp-customer-desc">
							<div class="sxp-customer-desc-thumbnail">
								<img src="<?php echo esc_url( sxp_get_plugin_uri( 'assets/images/customers/customer5.png' ) ); ?>" alt="Customer Thumbnail">
							</div><!-- end .sxp-customer-desc-thumbnail -->
							<div class="sxp-customer-desc-details">
								<p class="sxp-customer-desc-details-name">Jane Nguyen</p>
								<p class="sxp-customer-desc-details-location">Vermont</p>
							</div><!-- end .sxp-customer-desc-detaisl -->
						</div><!-- end .sxp-customer-desc -->
						<button type="button" class="toggle-row"><span class="screen-reader-text">Show more details</span></button>
					</td>
					<td class="sxp-customer-name" data-colname="Customer Type"><a href="#" style="background: #DAE4FF">Gold</a>
					</td>
					<td class="sxp-customer-tag-column" data-colname="Customer Tag">
						<div class="sxp-customer-tag-container">
							<ul class="sxp-tag-list">
								<li><a href="#">Doctor</a></li>
								<li><a href="">+5</a></li>
							</ul>
						</div><!-- end .sxp-customer-compaign-container -->
					</td>
					<td class="sxp-customer-assigned-column" data-colname="Customer Order">727</td>
					<td class="sxp-customer-revenue-column" data-colname="Customer Revenue">
						$3535.92
					</td>
					<td class="sxp-customer-last-order-column" data-colname="Last Order">23 days
						ago
					</td>
				</tr><!-- end .sxp-customer-list -->
				<tr id="sxp-customer-list-6" class="sxp-customer-list">
					<th scope="row" class="check-column">
						<label class="screen-reader-text" for="cb-select-1"></label>
						<input id="cb-select-1" type="checkbox" name="post[]" value="1">
					</th>
					<td class="title column-title has-row-actions column-primary page-title sxp-customers-column"
						data-colname="sxp-customer-customers">
						<div class="sxp-customer-desc">
							<div class="sxp-customer-desc-thumbnail">
								<img src="<?php echo esc_url( sxp_get_plugin_uri( 'assets/images/customers/customer6.png' ) ); ?>" alt="Customer Thumbnail">
							</div><!-- end .sxp-customer-desc-thumbnail -->
							<div class="sxp-customer-desc-details">
								<p class="sxp-customer-desc-details-name">Jane Nguyen</p>
								<p class="sxp-customer-desc-details-location">Vermont</p>
							</div><!-- end .sxp-customer-desc-detaisl -->
						</div><!-- end .sxp-customer-desc -->
						<button type="button" class="toggle-row"><span class="screen-reader-text">Show more details</span>
						</button>
					</td>
					<td class="sxp-customer-name" data-colname="Customer Type"><a href="#" style="background: #CFFFF4">Gold</a>
					</td>
					<td class="sxp-customer-tag-column" data-colname="Customer Tag">
						<div class="sxp-customer-tag-container">
							<ul class="sxp-tag-list">
								<li><a href="#">New Year</a></li>
								<li><a href="">+2</a></li>
							</ul>
						</div><!-- end .sxp-customer-compaign-container -->
					</td>
					<td class="sxp-customer-assigned-column" data-colname="Customer Order">727</td>
					<td class="sxp-customer-revenue-column" data-colname="Customer Revenue">
						$3535.92
					</td>
					<td class="sxp-customer-last-order-column" data-colname="Last Order">23 days
						ago
					</td>
				</tr><!-- end .sxp-customer-list -->
				</tbody>
			</table><!-- end .sxp-customer-table -->
			<div class="sxp-pagination-wrapper">
				<ul class="sxp-pagination">
					<li><a href="#"><img alt="arrow-left.svg" src="data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iMTYiIGhlaWdodD0iMTYiIHZpZXdCb3g9IjAgMCAxNiAxNiIgZmlsbD0ibm9uZSIgeG1sbnM9Imh0dHA6Ly93d3cudzMub3JnLzIwMDAvc3ZnIj4KPHBhdGggZD0iTTE0IDhMMyA4IiBzdHJva2U9IiM3RDdEQjMiIHN0cm9rZS13aWR0aD0iMiIgc3Ryb2tlLWxpbmVjYXA9InJvdW5kIiBzdHJva2UtbGluZWpvaW49InJvdW5kIi8+CjxwYXRoIGQ9Ik02IDEyTDIgOEw2IDQiIHN0cm9rZT0iIzdEN0RCMyIgc3Ryb2tlLXdpZHRoPSIyIiBzdHJva2UtbGluZWNhcD0icm91bmQiIHN0cm9rZS1saW5lam9pbj0icm91bmQiLz4KPC9zdmc+Cg=="/></a>
					</li>
					<li><a href="#">1</a></li>
					<li><a href="#">2</a></li>
					<li><a href="#">3</a></li>
					<li><a href="#">4</a></li>
					<li>...</li>
					<li><a href="#">5</a></li>
					<li><a href="#">6</a></li>
					<li><a href="#">7</a></li>
					<li><a href="#">8</a></li>
					<li><a href="#"><img alt="arrow-right.svg" src="data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iMTYiIGhlaWdodD0iMTYiIHZpZXdCb3g9IjAgMCAxNiAxNiIgZmlsbD0ibm9uZSIgeG1sbnM9Imh0dHA6Ly93d3cudzMub3JnLzIwMDAvc3ZnIj4KPHBhdGggZD0iTTIgOEwxMyA4IiBzdHJva2U9IiM3RDdEQjMiIHN0cm9rZS13aWR0aD0iMiIgc3Ryb2tlLWxpbmVjYXA9InJvdW5kIiBzdHJva2UtbGluZWpvaW49InJvdW5kIi8+CjxwYXRoIGQ9Ik0xMCA0TDE0IDhMMTAgMTIiIHN0cm9rZT0iIzdEN0RCMyIgc3Ryb2tlLXdpZHRoPSIyIiBzdHJva2UtbGluZWNhcD0icm91bmQiIHN0cm9rZS1saW5lam9pbj0icm91bmQiLz4KPC9zdmc+Cg=="/></a>
					</li>
				</ul>
			</div><!-- end .sxp-paginaation-wrapper -->
			<div class="sxp-bottom-wrapper">
				<div class="sxp-selected-container">
					<div class="sxp-row-select">
						<a href="#" class="sxp-remove-select"><img
									src="data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iMzYiIGhlaWdodD0iMzYiIHZpZXdCb3g9IjAgMCAzNiAzNiIgZmlsbD0ibm9uZSIgeG1sbnM9Imh0dHA6Ly93d3cudzMub3JnLzIwMDAvc3ZnIj4KPHBhdGggZD0iTTIyIDE0TDE0IDIyIiBzdHJva2U9IiM3RDdEQjMiIHN0cm9rZS13aWR0aD0iMiIgc3Ryb2tlLWxpbmVjYXA9InJvdW5kIiBzdHJva2UtbGluZWpvaW49InJvdW5kIi8+CjxwYXRoIGQ9Ik0xNCAxNEwyMiAyMiIgc3Ryb2tlPSIjN0Q3REIzIiBzdHJva2Utd2lkdGg9IjIiIHN0cm9rZS1saW5lY2FwPSJyb3VuZCIgc3Ryb2tlLWxpbmVqb2luPSJyb3VuZCIvPgo8L3N2Zz4K"
									alt="Remove selection"></a>
						<a href="#" class="sxp-selected">2 Rows Selected</a>
					</div>
					<div class="sxp-remove-customer">
						<a href="#">Delete</a>
					</div>
				</div><!-- end .sxp-selected-container -->
			</div><!-- end .sxp-bottom-wrapper -->
		</div><!-- end .sxp-customer-list-wrapper -->
		<?php
		// phpcs:enable
	}
}

// End of file class-sxp-customer-list-table.php.
