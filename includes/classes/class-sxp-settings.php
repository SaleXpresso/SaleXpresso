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
 * Class SXP_Settings
 *
 * @package SaleXpresso
 */
final class SXP_Settings {
	
	/**
	 * Singleton instance
	 *
	 * @var SXP_Settings
	 */
	protected static $instance;
	
	/**
	 * Get Singleton instance of this class
	 *
	 * @return SXP_Settings
	 */
	public static function get_instance() {
		if ( is_null( self::$instance ) ) {
			self::$instance = new self();
		}
		return self::$instance;
	}
	
	/**
	 * SXP_Settings constructor.
	 * Private constructor for preventing from creating new instance of this class.
	 */
	private function __construct() {
		// @TODO make a global settings object which can be filter later to add more settings page.
		// Example Settings Object
		$settings = [
			'tab_slug' => [],
		];
	}
	
}

// End of file class-sxp-settings.php.
