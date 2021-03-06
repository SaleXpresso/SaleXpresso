<?php
/**
 * SaleXpresso
 *
 * @package SaleXpresso\Customer
 * @version 1.0.0
 * @since   1.0.0
 */

namespace SaleXpresso\Customer;

use SaleXpresso\Abstracts\SXP_Admin_Page;
use WP_User_Query;

if ( ! defined( 'ABSPATH' ) ) {
	header( 'Status: 403 Forbidden' );
	header( 'HTTP/1.1 403 Forbidden' );
	die();
}

/**
 * Class SXP_Customers_Page
 *
 * @package SaleXpresso\Customer
 */
class SXP_Customers_Page extends SXP_Admin_Page {
	
	/**
	 * Add new button url for current page.
	 *
	 * @var string
	 */
	protected $add_new_url = '';
	
	/**
	 * SXP_Customer_List constructor.
	 *
	 * @param string $plugin_page Current page slug.
	 */
	public function __construct( $plugin_page = null ) {
		parent::__construct( $plugin_page );
		$this->add_new_label = '';
		$this->js_tabs       = false;
	}
	
	/**
	 * Set Tabs and tab content.
	 */
	protected function set_tabs() {
		$tabs = [
			'customer-list'  => [
				'label'   => __( 'Customers', 'salexpresso' ),
				'content' => [ $this, 'render_customer_list' ],
			],
			'customer-type'  => [
				'label'   => __( 'Customer Types', 'salexpresso' ),
				'content' => 'Customer Types List Table',
			],
			'customer-group' => [
				'label'   => __( 'Customer Groups', 'salexpresso' ),
				'content' => 'Customer Groups List Table',
			],
		];
		
		// This filter documented in  includes/abstracts/class-sxp-admin-page.php.
		$this->tabs = apply_filters( "salexpresso_admin_{$this->hook_slug}_page_tabs", $tabs );
	}
	
	/**
	 * Render the customer list
	 */
	protected function render_customer_list() {
		$list = new SXP_Customer_List_Table();
	}
	
	/**
	 * Get Customers.
	 *
	 * @param string $search Search Customer.
	 * @param int    $limit  Limit for pagination.
	 * @param int    $offset Offset for pagination.
	 *
	 * @return WP_User_Query
	 */
	private function get_customers( $search = '', $limit = 10, $offset = 0 ) {
		$args = [
			'fields'       => 'ID',
			'number'       => $limit,
			'offset'       => $offset,
			'role__not_in' => (array) apply_filters(
				'salexpresso_exclude_user_by_role',
				'administrator'
			),
		];
		if ( ! empty( $search ) ) {
			$args['search'] = '*' . esc_attr( $search ) . '*';
			if ( is_email( $search ) ) {
				$args['search_columns'] = [ 'user_login', 'user_email' ];
				$args['meta_query']     = [ // phpcs:ignore WordPress.DB.SlowDBQuery.slow_db_query_meta_query
					'relation' => 'OR',
					[
						'key'     => 'billing_email',
						'compare' => '=',
					],
					[
						'key'     => 'shipping_email',
						'value'   => $search,
						'compare' => '=',
					],
				];
			} else {
				$args['search_columns'] = [ 'user_login', 'user_url', 'user_email', 'user_nicename', 'display_name' ];
				$args['meta_query']     = [ // phpcs:ignore WordPress.DB.SlowDBQuery.slow_db_query_meta_query
					'relation' => 'OR',
					[
						'key'     => 'first_name',
						'value'   => $search,
						'compare' => 'LIKE',
					],
					[
						'key'     => 'last_name',
						'value'   => $search,
						'compare' => 'LIKE',
					],
				];
			}
		}
		return new WP_User_Query( apply_filters( 'salexpresso_customer_search', $args ) );
	}
}

// End of file SXP_Customer_List.php.
