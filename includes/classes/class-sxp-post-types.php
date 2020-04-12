<?php
/**
 * SaleXpresso
 *
 * @package SaleXpresso
 * @version 1.0.0
 * @since   1.0.0
 */

namespace SaleXpresso;

if ( ! defined( 'ABSPATH' ) ) {
	header( 'Status: 403 Forbidden' );
	header( 'HTTP/1.1 403 Forbidden' );
	die();
}

/**
 * Class SXP_Post_Types
 */
class SXP_Post_Types {
	
	/**
	 * Initialize things.
	 */
	public static function init() {
		add_action( 'init', array( __CLASS__, 'register_taxonomies' ), 5 );
	}
	
	/**
	 * Register Taxonomies
	 */
	public static function register_taxonomies() {
	
	}
}
SXP_Post_Types::init();
// End of file class-sxp-post-types.php.
