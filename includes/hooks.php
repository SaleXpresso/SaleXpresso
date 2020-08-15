<?php
/**
 * Default Hooks
 *
 * @author   SaleXpresso
 * @category Core
 * @package  SaleXpresso
 * @version  1.0.0
 */

use SaleXpresso\Customer\SXP_Customer;

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

// define the loop_add_to_cart_link callback
if ( ! function_exists( 'sxp_filter_loop_add_to_cart_link' ) ) {
	function sxp_filter_loop_add_to_cart_link( $button, $product, $args ) {
		if ( ! is_user_logged_in() ) {
			return $button;
		}

		try {
			$customer   = new SXP_Customer( sxp_get_the_user() );
			if ( $customer->can_buy( $product ) ) {
				return $button;
			} else {
				// @TODO RETURN BUTTON WITHOUT ADD TO CART LINK, ADD CLASS TO RENDER THE BUTTON AS DISABLED, USE JAVASCRIPT ALERT
				return '';
			}
		} catch ( Exception $e ) {
			// @TODO HANDLE EXCEPTION
			return $button;
		}
	}
}

add_filter( 'woocommerce_loop_add_to_cart_link', 'sxp_filter_loop_add_to_cart_link', 10, 3 );

// define the woocommerce_quantity_input_min callback
if ( ! function_exists( 'sxp_filter_woocommerce_quantity_input_min' ) ) {
	function sxp_filter_woocommerce_quantity_input_min( $int, $product ) {
		if ( ! is_user_logged_in() ) {
			return $int;
		}

		try {
			$customer = new SXP_Customer( sxp_get_the_user() );
			$min_qty = $customer-> get_purchase_restrictions( $product );
			return $min_qty['min_qty'] ? $min_qty['min_qty'] : $int;
		} catch ( Exception $e ) {
			return $int;
		}

	}
}

// add the filter
add_filter( 'woocommerce_quantity_input_min', 'sxp_filter_woocommerce_quantity_input_min', 10, 2 );

// define the woocommerce_quantity_input_max callback
if ( ! function_exists( 'sxp_filter_woocommerce_quantity_input_max' ) ) {
	function sxp_filter_woocommerce_quantity_input_max( $int, $product ) {
		if ( ! is_user_logged_in() ) {
			return $int;
		}

		try {
			$customer = new SXP_Customer( sxp_get_the_user() );
			$max_qty = $customer-> get_purchase_restrictions( $product );
			return $max_qty['max_qty'] ? $max_qty['max_qty'] : $int;
		} catch ( Exception $e ) {
			return $int;
		}

	}
}

// add the filter
add_filter( 'woocommerce_quantity_input_max', 'sxp_filter_woocommerce_quantity_input_max', 10, 2 );

add_action('woocommerce_single_product_summary', function () {
	if ( is_admin() || ! is_user_logged_in() ) {
		return;
	}
	global $product;

	try {
		$customer   = new SXP_Customer( sxp_get_the_user() );
		if ( ! $customer->can_buy( $product ) ) {
			remove_all_actions( 'woocommerce_' . $product->get_type() . '_add_to_cart' );
		}
	} catch ( Exception $e ) {
	}

}, 1 );

add_filter( 'woocommerce_add_to_cart_quantity', function ( $quantity, $product ) {
	if ( ! is_user_logged_in() ) {
		return $quantity;
	}

	try {
		$customer = new SXP_Customer( sxp_get_the_user() );
		if ( ! $customer->can_buy( $product ) ) {
			throw new Exception( esc_html__( 'If you can not buy', 'salexpresso' ) );
		}
		if ( $customer->can_buy( $product ) ) {
			$qty = $customer->get_purchase_restrictions( $product );
			if ( $qty['min_qty'] && $qty['min_qty'] > $quantity ) {
				throw new Exception( sprintf( esc_html__( 'Quantity must equal or greater than %d', 'salexpresso' ), $qty['min_qty'] ) );
			}
			if ( $qty['max_qty'] && $qty['max_qty'] < $quantity ) {
				throw new Exception( sprintf( esc_html__( 'Quantity must equal or less than %d', 'salexpresso' ), $qty['max_qty'] ) );
			}

			return $quantity;
		}
	} catch ( Exception $e ) {
		return $quantity;
	}

}, 10, 2 );