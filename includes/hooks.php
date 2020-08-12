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
global $sxp_screen_options;
$sxp_screen_options = [
	'per_page' => [
		'customers_per_page',
		'user_group_per_page',
		'user_tag_per_page',
		'user_type_per_page',
	],
];

add_filter( 'set-screen-option', 'sxp_set_screen_options', 10, 3 );

foreach ( $sxp_screen_options['per_page'] as $sxp_screen_option ) {
	add_filter( 'set_screen_option_' . $sxp_screen_option, 'sxp_set_screen_options', 10, 3 );
}
if ( ! function_exists( 'sxp_set_screen_options' ) ) {
	/**
	 * Set & Save User Specified Screen Options.
	 *
	 * @param bool   $keep   Whether to save or skip saving the screen option value.
	 *                       Default false.
	 * @param string $option The option name.
	 * @param int    $value  The number of rows to use.
	 *
	 * @return int
	 */
	function sxp_set_screen_options( $keep, $option, $value ) {
		global $sxp_screen_options;
		if ( in_array( $option, $sxp_screen_options['per_page'], true ) ) {
			$value = absint( $value );
			if ( $value < 0 || $value > 999 ) {
				$value = 20;
			}
			
			return $value;
		}
		
		return $keep;
	}
}

add_action( 'woocommerce_new_order', 'sxp_update_user_order_date_on_new_order', 10, 1 );
// End of file hooks.php.
