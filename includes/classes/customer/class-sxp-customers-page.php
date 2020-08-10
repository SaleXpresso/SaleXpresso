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
use SaleXpresso\SXP_List_Table;

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
	 * Holds the list table instance for this class (page).
	 *
	 * @var SXP_List_Table
	 */
	private $list_table;
	
	/**
	 * SXP_Customer_List constructor.
	 *
	 * @param string $plugin_page Current page slug.
	 */
	public function __construct( $plugin_page = null ) {
		parent::__construct( $plugin_page );
		$this->list_table    = SXP_Admin_Menus::get_instance()->get_list_table();
		$this->add_new_label = '';
		$this->js_tabs       = false;
	}
	
	/**
	 * Set Screen Option
	 *
	 * @return void
	 */
	public function page_actions() {
		add_screen_option(
			'per_page',
			[
				'label'  => 'Number of items per page:',
				'option' => 'customers_per_page',
			]
		);
	}
	
	public function render_page_content() {
		
		if ( isset( $_GET['customer'] ) ) { // phpcs:ignore WordPress.Security.NonceVerification.Recommended
			try {
				$customer = new \WC_Customer( absint( $_GET['customer'] ) ); // phpcs:ignore WordPress.Security.NonceVerification.Recommended
				if ( $customer->get_id() ) {
					new SXP_Customer_Profile_Table( $customer );
					die();
				} else {
					$customer = false;
				}
			} catch ( \Exception $e ) {
				$customer = false;
			}
			if ( false === $customer ) {
				$this->set_flash_message( esc_html__( 'Invalid Customer', 'salexpresso' ), 'error', true );
				wp_safe_redirect( esc_url_raw( wp_get_referer() ) );
				die();
			}
		}
		
		$this->list_table->prepare_items();
		$this->list_table->display();
		
	}
	
	/**
	 * Render Filter section
	 */
	/*
	protected function render_page_filter() {
		?>
		<div class="sxp-filter-wrapper">
			<div class="sxp-filter-default">
				<nav class="vg-nav vg-nav-lg">
					<ul>
						<li class="dropdown">
							<a href="#">Filter by Name</a>
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
	*/
	/**
	 * Render WC Customer Report List.
	 * This is here for testing/debugging purpose only.
	 *
	 * @return void
	 */
	private function wc_customer_report() {
		if ( ! class_exists( '\WC_Report_Customer_List', false ) ) {
			require_once ABSPATH . 'wp-content/plugins/woocommerce/includes/admin/reports/class-wc-report-customer-list.php';
		}
		$this->list_table = new \WC_Report_Customer_List();
		$this->list_table->output_report();
	}
}

// End of file class-sxp-customers-page.php.
