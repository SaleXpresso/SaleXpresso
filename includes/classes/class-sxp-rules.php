<?php
/**
 * Rules Library
 * This class helps validate rules in backend and evaluate rules/expression and execute action for
 * user activity on the frontend.
 *
 * @link https://symfony.com/doc/current/components/expression_language/syntax.html
 * @package SaleXpresso
 * @version 1.0.0
 * @since   1.0.0
 */

namespace SaleXpresso;

use Symfony\Component\ExpressionLanguage\ExpressionLanguage;

if ( ! defined( 'ABSPATH' ) ) {
	header( 'Status: 403 Forbidden' );
	header( 'HTTP/1.1 403 Forbidden' );
	die();
}

/**
 * Class SXP_Rules
 *
 * @package SaleXpresso
 */
final class SXP_Rules {
	
	/**
	 * Singleton instance
	 *
	 * @var SXP_Rules
	 */
	protected static $instance;
	
	/**
	 * ExpressionLanguage Instance.
	 *
	 * @var ExpressionLanguage
	 */
	private $engine;
	
	/**
	 * Get Singleton instance of this class
	 *
	 * @return SXP_Rules
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

	}
	
	/**
	 * ExpressionLanguage Instance.
	 *
	 * @return ExpressionLanguage
	 */
	private function getEngine() {
		if ( ! is_null( $this->engine ) ) {
			return $this->engine;
		}
		$this->engine = new ExpressionLanguage();
		
		return $this->engine;
	}
}

SXP_Rules::get_instance();
// End of file class-sxp-rules.php.
