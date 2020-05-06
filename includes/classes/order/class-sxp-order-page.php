<?php
/**
 * SaleXpresso
 *
 * @package SaleXpresso\Order
 * @version 1.0.0
 * @since   SaleXpresso v
 */

namespace SaleXpresso\Order;

use SaleXpresso\Abstracts\SXP_Admin_Page;

if ( ! defined( 'ABSPATH' ) ) {
	header( 'Status: 403 Forbidden' );
	header( 'HTTP/1.1 403 Forbidden' );
	die();
}

/**
 * Class SXP_Dashboard_Page
 *
 * @package SaleXpresso\Order
 */
class SXP_Order_page  extends SXP_Admin_Page {

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
			'orders-lists'  => [
				'label'   => __( 'Orders', 'salexpresso' ),
				'content' => [ $this, 'render_order_list' ],
			],
			'orders-single' => [
				'label'   => __( 'Single Order', 'salexpresso' ),
				'content' => [ $this, 'render_order_single' ],
			],
		];

		// This filter documented in  includes/abstracts/class-sxp-admin-page.php.
		$this->tabs = apply_filters( "salexpresso_admin_{$this->hook_slug}_page_tabs", $tabs );
	}

	/**
	 * Render the Order list
	 */
	protected function render_order_list() {
		$list = new SXP_Order_List_Table();
	}

	/**
	 * Render the Single Order
	 */
	protected function render_order_single() {
		$list = new SXP_Order_Single_Table();
	}

}

// End of file class-sxp-order-page.php.
