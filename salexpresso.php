<?php
/**
 * SaleXpresso Main FIle
 *
 * PHP Version 5.6
 *
 * @category WordPressPlugin
 * @package SaleXpresso
 * @author SaleXpresso <support@salexpresso.com>
 * @license GPL-3.0-or-later https://www.gnu.org/licenses/gpl-3.0.en.html
 * @version GIT: <git_id>
 * @link https://salexpresso.com
 * @since SaleXpresso 1.0.0
 *
 * @wordpress
 * Plugin Name: SaleXpresso
 * Plugin URI: https://salexpresso.com/
 * Description: SaleXpresso is a flexible, open-source eCommerce accelerator built on WooCommerce. Organize, sales data, build campaign, dynamic discount, custom user group and many more.
 * Version: 1.0.0
 * Author: SaleXpresso
 * Author URI: https://salexpresso.com
 * Text Domain: sale_xpresso
 * Domain Path: /i18n/languages
 * WP Requirement & Test
 * Requires at least: 5.0
 * Tested up to: 5.4
 * Requires PHP: 5.6
 * WC Requirement & Test
 * WC requires at least: 3.2
 * WC tested up to: 4.0.1
 */

if ( ! defined( 'ABSPATH' ) ) {
	header( 'Status: 403 Forbidden' );
	header( 'HTTP/1.1 403 Forbidden' );
	die();
}

if ( ! defined( 'SXP_PLUGIN_FILE' ) ) {
	/**
	 * The full path and filename of this file (main file)
	 *
	 * @var string
	 */
	define( 'SXP_PLUGIN_FILE', __FILE__ );
}

// Include the main SaleXpresso class.
if ( ! class_exists( 'SaleXpresso', false ) ) {
	include_once dirname( SXP_PLUGIN_FILE ) . '/includes/class-salexpresso.php';
}

/**
 * Returns the instance of main class.
 *
 * @since  1.0.0
 * @return SaleXpresso\SaleXpresso
 */
function SXP() { // phpcs:ignore WordPress.NamingConventions
	return SaleXpresso\SaleXpresso::get_instance();
}

// @TODO remove namespace.
// @TODO remove autoloader, load plugin files manually.
// @TODO remove webpack use gulp.
SXP();
// End of file salexpresso.php .
