<?php
/**
 * SaleXpresso
 *
 * @package SaleXpresso\Product
 * @version 1.0.0
 * @since   SaleXpresso v
 */

namespace SaleXpresso\Product;

use SaleXpresso\Abstracts\SXP_Admin_Page;

if ( ! defined( 'ABSPATH' ) ) {
	header( 'Status: 403 Forbidden' );
	header( 'HTTP/1.1 403 Forbidden' );
	die();
}

/**
 * Class SXP_Dashboard_Page
 *
 * @package SaleXpresso\Product
 */
class SXP_Product_page  extends SXP_Admin_Page{

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
			'products-lists' => [
				'label'   => __( 'Products', 'salexpresso' ),
				'content' => [$this, 'render_product_list'],
			],
			'product-profile' => [
				'label'   => __( 'Product Profile', 'salexpresso' ),
				'content' => [$this, 'render_product_profile'],
			],
		];

		// This filter documented in  includes/abstracts/class-sxp-admin-page.php.
		$this->tabs = apply_filters( "salexpresso_admin_{$this->hook_slug}_page_tabs", $tabs );
	}

	/**
	 * Render the Product list
	 */
	protected function render_product_list() {
		$list = new SXP_Product_List_Table();
	}

	/**
	 * Render the Product Profile
	 */
	protected function render_product_profile() {
		$list = new SXP_Product_Profile_Table();
	}
}

// End of file class-sxp-product-page.php.