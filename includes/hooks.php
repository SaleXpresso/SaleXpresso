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

add_action( 'woocommerce_new_order', 'sxp_update_user_order_date_on_new_order', 10, 1 );
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

add_filter( 'woocommerce_loop_add_to_cart_link', 'sxp_loop_restrict_add_to_cart', 10, 3 );
if ( ! function_exists( 'sxp_loop_restrict_add_to_cart' ) ) {
	/**
	 * Add's restriction to add to cart button on loop page.
	 *
	 * @param string     $button  Add to cart button html.
	 * @param WC_Product $product The Product.
	 * @param array      $args    Args array.
	 *
	 * @return string
	 */
	function sxp_loop_restrict_add_to_cart( $button, $product, $args ) {
		if ( ! is_user_logged_in() ) {
			return $button;
		}
		
		

		try {
			$customer   = new SXP_Customer( sxp_get_the_user() );
			if ( $customer->can_buy( $product ) ) {
				$qty = $customer->get_purchase_restrictions( $product );
				return sprintf(
					'<a href="%s" data-quantity="%s" class="%s" %s>%s</a>',
					esc_url( $product->add_to_cart_url() ),
					esc_attr( $qty['min_qty'] ),
					esc_attr( isset( $args['class'] ) ? $args['class'] : 'button' ),
					isset( $args['attributes'] ) ? wc_implode_html_attributes( $args['attributes'] ) : '',
					esc_html( $product->add_to_cart_text() )
				);
			} else {
				if ( ! isset( $args['class'] ) ) {
					$args['class'] = '';
				}
				$args['class'] .= 'sxp-tooltip';
				$message = sprintf(
					/* translators: 1. Customer Group Name. */
					__( 'Customer of %s group can not buy this item.', 'salexpresso' ),
					$customer->get_group()->name
				);
				
				return sprintf(
					'<a href="#" class="%s" data-tooltip="%s">%s</a>',
					esc_attr( $args['class'] ),
					esc_attr( $message ),
					esc_html( $product->add_to_cart_text() )
				);
			}
		} catch ( Exception $e ) {
			return $button;
		}
	}
}

add_filter( 'woocommerce_quantity_input_min', 'sxp_set_product_min_quantity', 10, 2 );
if ( ! function_exists( 'sxp_set_product_min_quantity' ) ) {
	/**
	 * Set max value for product quantity input field.
	 *
	 * @param int        $quantity Max Quantity.
	 * @param WC_Product $product  Product.
	 *
	 * @return int
	 */
	function sxp_set_product_min_quantity( $quantity, $product ) {
		if ( ! is_user_logged_in() ) {
			return $quantity;
		}
		
		try {
			$customer = new SXP_Customer( sxp_get_the_user() );
			$min_qty  = $customer->get_purchase_restrictions( $product );
			
			return $min_qty['min_qty'] ? $min_qty['min_qty'] : $quantity;
		}
		catch ( Exception $e ) {
			return $quantity;
		}
	}
}

add_filter( 'woocommerce_quantity_input_max', 'sxp_set_product_max_quantity', 10, 2 );
if ( ! function_exists( 'sxp_set_product_max_quantity' ) ) {
	/**
	 * Set max value for product quantity input field.
	 *
	 * @param int        $quantity Max Quantity.
	 * @param WC_Product $product  Product.
	 *
	 * @return int
	 */
	function sxp_set_product_max_quantity( $quantity, $product ) {
		if ( ! is_user_logged_in() ) {
			return $quantity;
		}
		
		try {
			$customer = new SXP_Customer( sxp_get_the_user() );
			$max_qty  = $customer->get_purchase_restrictions( $product );
			
			return $max_qty['max_qty'] ? $max_qty['max_qty'] : $quantity;
		}
		catch ( Exception $e ) {
			return $quantity;
		}
	}
}

add_action('woocommerce_single_product_summary', 'sxp_disable_single_add_to_cart', 1 );
if ( ! function_exists( 'sxp_disable_single_add_to_cart' ) ) {
	/**
	 * Removes' add to cart button if user doesn't have permission.
	 *
	 * @global WC_Product $product
	 */
	function sxp_disable_single_add_to_cart() {
		global $product;
		if ( ! is_user_logged_in() ) {
			return;
		}
		
		try {
			$customer = new SXP_Customer( sxp_get_the_user() );
			if ( ! $customer->can_buy( $product ) ) {
				remove_all_actions( 'woocommerce_' . $product->get_type() . '_add_to_cart' );
			}
		}
		catch ( Exception $e ) {
		}
	}
}

add_filter( 'woocommerce_add_to_cart_quantity', 'sxp_restrict_customer_add_to_cart', 10, 2 );
if ( ! function_exists( 'sxp_restrict_customer_add_to_cart' ) ) {
	/**
	 * Check blocks users from adding items to the cart if they doesn't have permission
	 * or quantity doesn't meet group settings.
	 *
	 * @param int $quantity contains the quantity of the item to add.
	 * @param int $product  contains the id of the product to add to the cart.
	 *
	 * @return int|void return the quantity if everything well or throw exception.
	 *                  WC()->cart->add_to_cart() catches this exception and
	 *                  show as a notice to the user.
	 * @throws Exception
	 */
	function sxp_restrict_customer_add_to_cart( $quantity, $product ) {
		if ( ! is_user_logged_in() ) {
			return $quantity;
		}
		
		try {
			$customer = new SXP_Customer( sxp_get_the_user() );
		}
		catch ( Exception $e ) {
			return $quantity;
		}
		
		if ( ! $customer->can_buy( $product ) ) {
			// user can't buy.
			throw new Exception( esc_html__( 'If you can not buy', 'salexpresso' ) );
		}
		
		if ( $customer->can_buy( $product ) ) {
			$qty = $customer->get_purchase_restrictions( $product );
			if ( $qty['min_qty'] > 0 && $quantity < $qty['min_qty'] ) {
				throw new Exception(
					sprintf(
					/* translators: 1. User Group Name, 2. Minimum purchase Quantity */
						esc_html__( 'Sorry, minimum quantity is %d to buy this product by a customer of %s group.',
							'salexpresso' ),
						$qty['min_qty'],
						'<strong><em>' . $customer->get_group()->name . '</strong></em>'
					)
				);
			}
			if ( $qty['max_qty'] > 0 && $quantity > $qty['max_qty'] ) {
				throw new Exception(
					sprintf(
					/* translators: 1. User Group Name, 2. Minimum purchase Quantity */
						esc_html__( 'Sorry, maximum quantity %d exceed for a customer of %s group.',
							'salexpresso' ),
						$qty['max_qty'],
						'<strong><em>' . $customer->get_group()->name . '</strong></em>'
					)
				);
			}
			
			return $quantity;
		}
	}
}

// End of file hooks.php.
