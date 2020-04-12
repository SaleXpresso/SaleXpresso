<?php
/**
 * SaleXpresso
 *
 * @package SaleXpresso
 * @version 1.0.0
 * @since   SaleXpresso v1.0.0
 */

namespace SaleXpresso\Interfaces;

if ( ! defined( 'ABSPATH' ) ) {
	header( 'Status: 403 Forbidden' );
	header( 'HTTP/1.1 403 Forbidden' );
	die();
}

/**
 * Interface SXP_Admin_Page
 *
 * @package SaleXpresso
 */
interface SXP_Admin_Page_Interface {
	
	/**
	 * SXP_Admin_Page constructor.
	 *
	 * @return void
	 */
	public function __construct();
	
	/**
	 * Init Actions
	 *
	 * @hooked admin_init
	 * @return void
	 */
	public function actions();
	
	/**
	 * Get page slug for current page
	 *
	 * @return string
	 */
	public function get_page_slug();
	
	/**
	 * Get Active Tab
	 *
	 * @return string
	 */
	public function get_active_tab();
	
	/**
	 * Check if current page is active.
	 *
	 * @return bool
	 */
	public function is_active_page();
	
	/**
	 * Check if tab is active.
	 *
	 * @param string $tab Tab slug to check.
	 *
	 * @return bool
	 */
	public function is_active_tab( $tab );
	
	/**
	 * Print page title for current admin page
	 *
	 * @return void
	 */
	public function render_page_title();
	
	/**
	 * Render page title action button
	 *
	 * @return void
	 */
	public function render_page_title_action();
	
	/**
	 * Prints html content for admin page
	 *
	 * @return void
	 */
	public function render();
}

// End of file interface-sxp-admin-page.php.
