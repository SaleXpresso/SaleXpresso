<?php
/**
 * SaleXpresso
 *
 * @package SaleXpresso\Customer
 * @version 1.0.0
 * @since   1.0.0
 */

namespace SaleXpresso\AbandonCart;

use SaleXpresso\Abstracts\SXP_Admin_Page;
use SaleXpresso\SXP_Admin_Menus;
use SaleXpresso\SXP_List_Table;

if ( ! defined( 'ABSPATH' ) ) {
	header( 'Status: 403 Forbidden' );
	header( 'HTTP/1.1 403 Forbidden' );
	die();
}

/**
 * Class SXP_Abandon_Cart
 *
 * @package SaleXpresso\AbandonCart
 */
class SXP_Abandon_Cart_Page extends SXP_Admin_Page {
	
	/**
	 * Holds the list table instance for this class (page).
	 *
	 * @var SXP_List_Table
	 */
	private $list_table;
	
	/**
	 * SXP_Abandon_Cart_Page constructor.
	 *
	 * @param string $plugin_page Current page slug.
	 */
	public function __construct( $plugin_page = null ) {
		parent::__construct( $plugin_page );
		$this->list_table    = SXP_Admin_Menus::get_instance()->get_list_table();
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
				'option' => 'abandon_cart_per_page',
			]
		);
	}
	
	/**
	 * Render page content
	 *
	 * @return void
	 */
	public function render_page_content() {
		if ( isset( $_GET['cart'] ) ) { // phpcs:ignore WordPress.Security.NonceVerification.Recommended
			var_dump( $_GET );
		}
		$this->list_table->prepare_items();
		$this->list_table->display();
	}
	
}

// End of file class-sxp-abandon-cart.php.
