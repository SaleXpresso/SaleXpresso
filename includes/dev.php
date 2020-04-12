<?php
/**
 * Developer Helper Functions
 *
 * @author   SaleXpresso
 * @category DevOps
 * @package  SaleXpresso\DevOps
 * @version  1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	header( 'Status: 403 Forbidden' );
	header( 'HTTP/1.1 403 Forbidden' );
	die();
}

/**
 * Dump variable.
 */
if ( ! function_exists('d') ) {
	/**
	 * Pretty print_r
	 *
	 * @param mixed ...$vars vars to print.
	 *
	 * @return void
	 * @since 1.0.0
	 */
	function pp( ...$vars ) {
		echo '<pre>';
		print_r( $vars ); // phpcs:ignore WordPress.PHP.DevelopmentFunctions.error_log_print_r
		echo '</pre>';
	}
}

/**
 * Dump variables and die.
 */
if ( ! function_exists('dd') ) {
	/**
	 * Pretty print_r and exits process
	 *
	 * @param mixed ...$vars vars to print.
	 *
	 * @return void
	 * @since 1.0.0
	 */
	function ppd( ...$vars ) {
		pp( $vars );
		die();
	}
}
// End of file dev.php.
