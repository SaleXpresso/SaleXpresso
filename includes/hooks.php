<?php
/**
 * Default Hooks
 *
 * @author   SaleXpresso
 * @category Core
 * @package  SaleXpresso
 * @version  1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	header( 'Status: 403 Forbidden' );
	header( 'HTTP/1.1 403 Forbidden' );
	die();
}
// We're keeping log file at wc log dir now.
add_filter( 'salexpresso_install_skip_create_files', '__return_true' );

add_filter( 'set-screen-option', function ( $keep, $option, $value ) {
	
	if ( 'customers_per_page' === $option ) {
		$value = absint( $value );
		if ( $value < 0 || $value > 999 ) {
			$value = 20;
		}
		
		return $value;
	}
	return $keep;
}, 10, 3 );

add_filter( 'set_screen_option_customers_per_page', function ( $pre, $option, $value ) {
	if ( 'customers_per_page' === $option ) {
		$value = absint( $value );
		if ( $value < 0 || $value > 999 ) {
			$value = 20;
		}
		return $value;
	}
	return $pre;
}, 10, 3 );
// End of file hooks.php.
