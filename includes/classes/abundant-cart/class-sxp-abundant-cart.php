<?php
/**
 * SaleXpresso
 *
 * @package SaleXpresso\Customer
 * @version 1.0.0
 * @since   1.0.0
 */

namespace SaleXpresso\AbundantCart;

if ( ! defined( 'ABSPATH' ) ) {
	header( 'Status: 403 Forbidden' );
	header( 'HTTP/1.1 403 Forbidden' );
	die();
}

/**
 * Class SXP_Abundant_Cart
 *
 * @package SaleXpresso\AbundantCart
 */
class SXP_Abundant_Cart {
	
	protected static $instance;
	
	public static function get_instance() {
		if ( is_null( self::$instance ) ) {
			self::$instance = new self();
		}
		
		return self::$instance;
	}
	
	private function __construct() {
	}
}

// End of file class-sxp-abundant-cart.php.
