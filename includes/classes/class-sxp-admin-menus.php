<?php
/**
 * SaleXpresso
 *
 * @package SaleXpresso
 * @version 1.0.0
 * @since   1.0.0
 */

namespace SaleXpresso;

use SaleXpresso\Abstracts\SXP_Admin_Page;

if ( ! defined( 'ABSPATH' ) ) {
	header( 'Status: 403 Forbidden' );
	header( 'HTTP/1.1 403 Forbidden' );
	die();
}

/**
 * Class SXP_Admin_Menus
 *
 * @package SaleXpresso
 */
final class SXP_Admin_Menus {
	
	/**
	 * Singleton instance
	 *
	 * @var SXP_Admin_Menus
	 */
	protected static $instance;
	
	/**
	 * Admin page renderer list
	 *
	 * @var SXP_Admin_Page[]
	 */
	private $renderer_map = [
		'sxp-customer'  => 'SaleXpresso\Customer\SXP_Customers_Page',
		'sxp-customer-group'  => 'SaleXpresso\Customer\SXP_Customer_Group_Page',
		'sxp-customer-type'  => 'SaleXpresso\Customer\SXP_Customer_Type_Page',
		'sxp-customer-tag'  => 'SaleXpresso\Customer\SXP_Customer_Tag_Page',
//		'sxp-dashboard' => 'SaleXpresso\Dashboard\SXP_Dashboard_Page',
//		'sxp-product'   => 'SaleXpresso\Product\SXP_Product_Page',
//		'sxp-order'     => 'SaleXpresso\Order\SXP_Order_Page',
//		'sxp-campaign'  => 'SaleXpresso\Campaign\SXP_Campaign_Page',
		'sxp-settings'  => 'SaleXpresso\Settings\SXP_Settings_Page',
		'sxp-status'    => 'SaleXpresso\Settings\SXP_Status_Page',
	];
	
	/**
	 * Admin page renderer instance
	 *
	 * @var SXP_Admin_Page[]
	 */
	protected $renderer = [];
	
	/**
	 * Admin List Table class map
	 *
	 * @see _get_list_table()
	 * @var SXP_List_Table[]
	 */
	private $list_tables = [
		'sxp-customer'       => 'SaleXpresso\List_Table\SXP_Customer_List_Table',
		'sxp-customer-group' => 'SaleXpresso\List_Table\SXP_Customer_Group_List_Table',
		'sxp-customer-type'  => 'SaleXpresso\List_Table\SXP_Customer_Type_List_Table',
		'sxp-customer-tag'   => 'SaleXpresso\List_Table\SXP_Customer_Tag_List_Table',
	];
	
	/**
	 * List table instance for pages.
	 * This holds the list table instance for currnet page (if needed)
	 *
	 * @var SXP_List_Table
	 */
	private $list_table;
	
	/**
	 * Get Singleton instance of this class
	 *
	 * @return SXP_Admin_Menus
	 */
	public static function get_instance() {
		if ( is_null( self::$instance ) ) {
			self::$instance = new self();
		}
		return self::$instance;
	}
	
	/**
	 * SXP_Admin_Menus constructor.
	 * Private constructor for preventing from creating new instance of this class.
	 */
	private function __construct() {
		
		// Add menus.
		add_action( 'admin_menu', array( $this, 'admin_menu' ), 9 );
		add_action( 'admin_menu', array( $this, 'customer_menu' ), 10 );
		
		add_action( 'admin_head', array( $this, 'menu_highlight' ) );
		add_action( 'admin_head', array( $this, 'menu_element_manipulation' ) );
		add_filter( 'menu_order', array( $this, 'menu_order' ) );
		add_filter( 'custom_menu_order', array( $this, 'custom_menu_order' ) );
		
		// Handle saving settings earlier than load-{page} hook to avoid race conditions in conditional menus.
		add_action( 'wp_loaded', array( $this, 'save_settings' ) );
		
		add_action( 'admin_init', [ $this, 'init' ] );
		
		add_action( 'wp_ajax_sxp_list_table', function () {
			if ( isset( $_REQUEST['list_args'], $_REQUEST['_action'], $_REQUEST['list_args']['class'] ) ) {
				$list_class     = wp_unslash( $_REQUEST['list_args']['class'] );
				$sxp_list_table = _sxp_get_list_table( $list_class, [ 'screen' => $_REQUEST['list_args']['screen']['id'] ] );
				check_ajax_referer( $list_class . '_' . $_REQUEST['_action'] );
				if ( ! $sxp_list_table ) {
					wp_die( 0 );
				}
				if ( ! $sxp_list_table->ajax_user_can() ) {
					wp_die( -1 );
				}
				
				$sxp_list_table->ajax_response();
			}
			wp_die( 0 );
		} );
	}
	
	/**
	 * Initialize Renderer
	 */
	public function init() {
		global $plugin_page, $hook_suffix, $current_screen;
		if ( is_null( $hook_suffix ) ) {
			return;
		}

		if ( false !== strpos( $plugin_page, 'sxp-' ) ) {
			/**
			 * Action before rendering plugin page.
			 *
			 * @param string $plugin_page
			 */
			do_action( 'sxp-before-page', $plugin_page );
			$action = isset( $_REQUEST['action'] ) ? sanitize_text_field( $_REQUEST['action'] ) : '';
			$disallowed_list_table = [ 'add-new', 'edit' ];
			// set user taxonomy to current screen taxonomy
			switch ( $plugin_page ) {
				case 'sxp-customer-group':
					$current_screen->taxonomy = SXP_Post_Types::CUSTOMER_GROUP_TAX;
					break;
				case 'sxp-customer-type':
					$current_screen->taxonomy = SXP_Post_Types::CUSTOMER_TYPE_TAX;
					break;
				case 'sxp-customer-tag':
					$current_screen->taxonomy = SXP_Post_Types::CUSTOMER_TAG_TAX;
					break;
				default:
					break;
			}
			
			if ( ! in_array( $action, $disallowed_list_table ) && isset( $this->list_tables[ $plugin_page ] ) && ! ( $this->list_table instanceof SXP_List_Table ) ) {
				$this->list_table = _sxp_get_list_table( $this->list_tables[ $plugin_page ] );
			}
			
			if ( isset( $this->renderer_map[ $plugin_page ] ) ) {
				if ( ! isset( $this->renderer[ $plugin_page ] ) || ( isset( $this->renderer[ $plugin_page ] ) && ! ( $this->renderer[ $plugin_page ] instanceof SXP_Admin_Page ) ) ) {
					$this->renderer[ $plugin_page ] = new $this->renderer_map[ $plugin_page ]( $plugin_page );
				}
			}
		}
	}
	
	public function get_list_table() {
		return $this->list_table;
	}
	
	/**
	 * Page Render Callback
	 */
	public function render_page() {
		global $plugin_page;
		if ( isset( $this->renderer[ $plugin_page ] ) && ( $this->renderer[ $plugin_page ] instanceof SXP_Admin_Page ) ) {
			$this->renderer[ $plugin_page ]->render();
			
			/**
			 * Action after rendering plugin page.
			 *
			 * @param string $plugin_page
			 */
			do_action( 'sxp-after-page', $plugin_page );
		}
	}
	
	/**
	 * Add menu items.
	 */
	public function admin_menu() {
		global $menu;
		
		if ( current_user_can( 'manage_woocommerce' ) ) {
			$menu[] = array( '', 'read', 'separator-salexpresso', '', 'wp-menu-separator salexpresso' ); // phpcs:ignore WordPress.WP.GlobalVariablesOverride.Prohibited
		}
		
		$hook = add_menu_page( __( 'SaleXpresso', 'salexpresso' ), __( 'SaleXpresso', 'salexpresso' ), 'manage_woocommerce', 'salexpresso', null, null, '55.5' );
		add_action( 'load-' . $hook, [ $this, 'init' ] );
		$hook = add_submenu_page( 'salexpresso', __( 'Dashboard', 'salexpresso' ), __( 'Dashboard', 'salexpresso' ), 'manage_woocommerce', 'sxp-dashboard', array( $this, 'render_page' ) );
		add_action( 'load-' . $hook, [ $this, 'init' ] );
		$hook = add_submenu_page( 'salexpresso', __( 'Customer List', 'salexpresso' ), __( 'Customer', 'salexpresso' ), 'manage_woocommerce', 'sxp-customer', array( $this, 'render_page' ) );
		add_action( 'load-' . $hook, [ $this, 'init' ] );
		$hook = add_submenu_page( 'salexpresso', __( 'Customer Group', 'salexpresso' ), __( 'Customer Group', 'salexpresso' ), 'manage_woocommerce', 'sxp-customer-group', array( $this, 'render_page' ) );
		add_action( 'load-' . $hook, [ $this, 'init' ] );
		$hook = add_submenu_page( 'salexpresso', __( 'Customer Type', 'salexpresso' ), __( 'Customer Type', 'salexpresso' ), 'manage_woocommerce', 'sxp-customer-type', array( $this, 'render_page' ) );
		add_action( 'load-' . $hook, [ $this, 'init' ] );
		$hook = add_submenu_page( 'salexpresso', __( 'Customer Tag', 'salexpresso' ), __( 'Customer Tag', 'salexpresso' ), 'manage_woocommerce', 'sxp-customer-tag', array( $this, 'render_page' ) );
		add_action( 'load-' . $hook, [ $this, 'init' ] );
//		$hook = add_submenu_page( 'salexpresso', __( 'Products', 'salexpresso' ), __( 'Products', 'salexpresso' ), 'manage_woocommerce', 'sxp-product', array( $this, 'render_page' ) );
//		add_action( 'load-' . $hook, [ $this, 'init' ] );
//		$hook = add_submenu_page( 'salexpresso', __( 'Orders', 'salexpresso' ), __( 'Orders', 'salexpresso' ), 'manage_woocommerce', 'sxp-order', array( $this, 'render_page' ) );
//		add_action( 'load-' . $hook, [ $this, 'init' ] );
//		$hook = add_submenu_page( 'salexpresso', __( 'Campaign', 'salexpresso' ), __( 'Campaign', 'salexpresso' ), 'manage_woocommerce', 'sxp-campaign', array( $this, 'render_page' ) );
//		add_action( 'load-' . $hook, [ $this, 'init' ] );
		
		$hook = add_submenu_page( 'salexpresso', __( 'SaleXpresso Settings', 'salexpresso' ), __( 'Settings', 'salexpresso' ), 'manage_woocommerce', 'sxp-settings', [ $this, 'render_page' ] );
		add_action( 'load-' . $hook, [ $this, 'init' ] );
		add_action( 'load-' . $hook, array( $this, 'settings_page_init' ) );
		
		$hook = add_submenu_page( 'salexpresso', __( 'SaleXpresso status', 'salexpresso' ), __( 'Status', 'salexpresso' ), 'manage_woocommerce', 'sxp-status', array( $this, 'render_page' ) );
		add_action( 'load-' . $hook, [ $this, 'init' ] );
	}
	
	/**
	 * Add customer menu item
	 */
	public function customer_menu() {
	}
	
	/**
	 * Loads gateways and shipping methods into memory for use within settings.
	 */
	public function settings_page_init() {
		do_action( 'salexpresso_settings_page_init' );
	}
	
	/**
	 * Handle saving of settings.
	 *
	 * @return void
	 */
	public function save_settings() {
		// We should only save on the settings page.
		if ( ! is_admin() || ! isset( $_GET['page'] ) || 'sxp-settings' !== $_GET['page'] ) { // phpcs:ignore WordPress.Security.NonceVerification.Recommended
			return;
		}
		// save settings.
	}
	
	/**
	 * Highlights the correct top level admin menu item for post type add screens.
	 */
	public function menu_highlight() {
		global $parent_file, $submenu_file, $post_type;
		// @TODO check current screen, check for user group taxonomy and highlight the main menu.
	}
	
	/**
	 * Adds the order processing count to the menu.
	 *
	 * @return void
	 * @see \WC_Admin_Menus::menu_order_count
	 */
	public function menu_element_manipulation() {
		global $submenu;
		
		if ( isset( $submenu['salexpresso'] ) ) {
			// Remove 'SaleXpresso' sub menu item.
			unset( $submenu['salexpresso'][0] );
		}
	}
	
	/**
	 * Reorder the SXP menu items in admin.
	 *
	 * @param array $menu_order Menu order.
	 * @return array
	 */
	public function menu_order( $menu_order ) {
		// Initialize our custom order array.
		$sxp_menu_order = array();
		
		// Get the index of our custom separator.
		$sxp_separator = array_search( 'separator-salexpresso', $menu_order, true );
		
		// Get index of product menu.
		$sxp_product = array_search( 'edit.php?post_type=product', $menu_order, true );
		
		// Loop through menu order and do some rearranging.
		foreach ( $menu_order as $index => $item ) {
			if ( 'salexpresso' === $item ) {
				$sxp_menu_order[] = 'separator-salexpresso';
				$sxp_menu_order[] = $item;
				$sxp_menu_order[] = 'edit.php?post_type=product';
				unset( $menu_order[ $sxp_separator ] );
				unset( $menu_order[ $sxp_product ] );
			} elseif ( ! in_array( $item, array( 'separator-salexpresso' ), true ) ) {
				$sxp_menu_order[] = $item;
			}
		}
		
		// Return order.
		return $sxp_menu_order;
	}
	
	/**
	 * Custom menu order.
	 *
	 * @param bool $enabled Whether custom menu ordering is already enabled.
	 * @return bool
	 */
	public function custom_menu_order( $enabled ) {
		return $enabled || current_user_can( 'manage_woocommerce' );
	}
	
	/**
	 * Init the settings page.
	 */
	public function settings_page() {
		SXP_Admin_Settings_Page::output();
	}
	
	/**
	 * Init the status page.
	 */
	public function status_page() {
	}
}

// End of file class-sxp-admin-menus.php.
