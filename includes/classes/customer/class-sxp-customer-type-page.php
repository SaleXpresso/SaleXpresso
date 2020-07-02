<?php
/**
 * SaleXpresso
 *
 * @package SaleXpresso\Customer
 * @version 1.0.0
 * @since   1.0.0
 */

namespace SaleXpresso\Customer;

use SaleXpresso\SXP_Admin_Menus;
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
class SXP_Customer_Type_Page extends SXP_Admin_Page {
	
	/**
	 * Add new button url for current page.
	 *
	 * @var string
	 */
	protected $add_new_url = '';
	
	private $list_table;
	
	/**
	 * SXP_Customer_List constructor.
	 *
	 * @param string $plugin_page Current page slug.
	 */
	public function __construct( $plugin_page = null ) {
		parent::__construct( $plugin_page );
//		$this->list_table = new SXP_Customer_List_Table();
		$this->list_table = SXP_Admin_Menus::get_instance()->get_list_table();
//		$this->list_table->old();
		$this->add_new_label = '';
		$this->js_tabs       = false;
	}
	
	public function page_actions() {
		// @TODO Save action for per_page.
		add_screen_option(
			'per_page',
			[
				'label'   => 'Number of items per page:',
				'option'  => 'customers_per_page',
			]
		);
	}
	
	/**
	 * Set Tabs and tab content.
	 */
	protected function set_tabs() {
		$tabs = [
			'customer-list'       => [
				'label'   => __( 'Customers', 'salexpresso' ),
				'content' => [ $this, 'render_customer_list' ],
			],
			'customer-group'      => [
				'label'   => __( 'Customer Groups', 'salexpresso' ),
				'content' => [ $this, 'render_customer_group' ],
			],
			'customer-group-rule' => [
				'label'   => __( 'Customer Group Rules', 'salexpresso' ),
				'content' => [ $this, 'render_customer_group_rule' ],
			],
			'customer-type'       => [
				'label'   => __( 'Customer Types', 'salexpresso' ),
				'content' => [ $this, 'render_customer_type' ],
			],
			'customer-type-rule'  => [
				'label'   => __( 'Customer Type Rules', 'salexpresso' ),
				'content' => [ $this, 'render_customer_type_rule' ],
			],
			'customer-tag'        => [
				'label'   => __( 'Customer Tags', 'salexpresso' ),
				'content' => [ $this, 'render_customer_tag' ],
			],
			'customer-tag-rule'   => [
				'label'   => __( 'Customer Tag Rules', 'salexpresso' ),
				'content' => [ $this, 'render_customer_tag_rule' ],
			],
			'customer-profile'    => [
				'label'   => __( 'Customer Profile', 'salexpresso' ),
				'content' => [ $this, 'render_customer_profile' ],
			],
		];
		
		// This filter documented in  includes/abstracts/class-sxp-admin-page.php.
		$this->tabs = apply_filters( "salexpresso_admin_{$this->hook_slug}_page_tabs", $tabs );
	}
	
	/**
	 * Render Filter section
	 */
	protected function render_page_filter() {
		?>
		<div class="sxp-filter-wrapper">
			<div class="sxp-filter-default">
				<nav class="vg-nav vg-nav-lg">
					<ul>
						<li class="dropdown">
							<a href="#">Sort by Name</a>
							<ul class="left">
								<li>
									<a href="#">Location</a>
								</li>
								<li class="dropdown">
									<a href="#">Customer Type</a>
									<ul class="left">
										<li>
											<a href="#">Another page</a>
										</li>
										<li>
											<a href="#">Any page</a>
										</li>
									</ul>
								</li>
								<li>
									<a href="#">Orders</a>
								</li>
								<li>
									<a href="#">Revenue</a>
								</li>
								<li>
									<a href="#">First Order</a>
								</li>
							</ul>
						</li>
					</ul>
				</nav>
			</div><!-- end .sxp-filter-default -->
			<div class="sxp-filter-date-range">
				<div id="sxp-date-range" tabindex="0" aria-label="filter by date">
					<span></span>
				</div>
			</div><!-- end .sxp-filter-date-range-->
			<div class="sxp-screen-options">
				<a href="#">
					<img src="data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iMjQiIGhlaWdodD0iMjQiIHZpZXdCb3g9IjAgMCAyNCAyNCIgZmlsbD0ibm9uZSIgeG1sbnM9Imh0dHA6Ly93d3cudzMub3JnLzIwMDAvc3ZnIj4KPHBhdGggZD0iTTEyIDEzQzEyLjU1MjMgMTMgMTMgMTIuNTUyMyAxMyAxMkMxMyAxMS40NDc3IDEyLjU1MjMgMTEgMTIgMTFDMTEuNDQ3NyAxMSAxMSAxMS40NDc3IDExIDEyQzExIDEyLjU1MjMgMTEuNDQ3NyAxMyAxMiAxM1oiIHN0cm9rZT0iIzdEN0RCMyIgc3Ryb2tlLXdpZHRoPSIyIiBzdHJva2UtbGluZWNhcD0icm91bmQiIHN0cm9rZS1saW5lam9pbj0icm91bmQiLz4KPHBhdGggZD0iTTE5IDEzQzE5LjU1MjMgMTMgMjAgMTIuNTUyMyAyMCAxMkMyMCAxMS40NDc3IDE5LjU1MjMgMTEgMTkgMTFDMTguNDQ3NyAxMSAxOCAxMS40NDc3IDE4IDEyQzE4IDEyLjU1MjMgMTguNDQ3NyAxMyAxOSAxM1oiIHN0cm9rZT0iIzdEN0RCMyIgc3Ryb2tlLXdpZHRoPSIyIiBzdHJva2UtbGluZWNhcD0icm91bmQiIHN0cm9rZS1saW5lam9pbj0icm91bmQiLz4KPHBhdGggZD0iTTUgMTNDNS41NTIyOCAxMyA2IDEyLjU1MjMgNiAxMkM2IDExLjQ0NzcgNS41NTIyOCAxMSA1IDExQzQuNDQ3NzIgMTEgNCAxMS40NDc3IDQgMTJDNCAxMi41NTIzIDQuNDQ3NzIgMTMgNSAxM1oiIHN0cm9rZT0iIzdEN0RCMyIgc3Ryb2tlLXdpZHRoPSIyIiBzdHJva2UtbGluZWNhcD0icm91bmQiIHN0cm9rZS1saW5lam9pbj0icm91bmQiLz4KPC9zdmc+Cg==" alt="more">
				</a>
			</div>
		</div><!-- end .sxp-filter-wrapper-->
		<div class="clearfix"></div>
		<?php
	}
	
	/**
	 * Render the customer list
	 */
	protected function render_customer_list() {
//		if ( ! class_exists( '\WC_Report_Customer_List', false ) ) {
//			require_once ABSPATH . 'wp-content/plugins/woocommerce/includes/admin/reports/class-wc-report-customer-list.php';
//		}
//		$this->list_table = new \WC_Report_Customer_List();
//		$this->list_table->output_report();
		$this->list_table->display();
	}

	/**
	 * Render the customer Group
	 */
	protected function render_customer_group() {
		$list = new SXP_Customer_Group_Table();
		$list->display();
	}

	/**
	 * Render the customer Group Rule
	 */
	protected function render_customer_group_rule() {
		$list = new SXP_Customer_Group_Rule();
	}

	/**
	 * Render the customer type
	 */
	protected function render_customer_type() {
		$list = new SXP_Customer_Type_Table();
	}

	/**
	 * Render the customer type Rule
	 */
	protected function render_customer_type_rule() {
		$list = new SXP_Customer_Type_Rule();
	}

	/**
	 * Render the customer type
	 */
	protected function render_customer_tag() {
		$list = new SXP_Customer_Tag_Table();
	}

	/**
	 * Render the customer type Rule
	 */
	protected function render_customer_tag_rule() {
		$list = new SXP_Customer_Tag_Rule();
	}

	/**
	 * Render the customer Profile
	 */
	protected function render_customer_profile() {
		$list = new SXP_Customer_Profile_Table();
	}
}

// End of file class-sxp-customer-type-page.php.
