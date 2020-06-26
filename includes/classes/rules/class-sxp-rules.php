<?php
/**
 * Rules Library
 * This class helps validate rules in backend and evaluate rules/expression and execute action for
 * user activity on the frontend.
 *
 * @link https://symfony.com/doc/current/components/expression_language/syntax.html
 * @package SaleXpresso\Rules
 * @version 1.0.0
 * @since   1.0.0
 */

namespace SaleXpresso\Rules;

use Symfony\Component\ExpressionLanguage\ExpressionLanguage;
use Symfony\Component\ExpressionLanguage\ParsedExpression;

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
	 * ExpressionLanguage Instance.
	 *
	 * @var ExpressionLanguage
	 */
	private $engine;
	
	/**
	 * Rules Storage.
	 * Rules will be stored as multi dimensional list with a group name.
	 *
	 * @var array
	 */
	protected $rules = [];
	
	/**
	 * SXP_Settings constructor.
	 * Private constructor for preventing from creating new instance of this class.
	 */
	public function __construct() {}
	
	/**
	 * ExpressionLanguage Instance.
	 *
	 * @return ExpressionLanguage
	 */
	private function get_engine() {
		if ( ! is_null( $this->engine ) ) {
			return $this->engine;
		}
		
		// @TODO implement a psr6 cache with wp cache or wp transient
		
		$this->engine = new ExpressionLanguage();
		
		return $this->engine;
	}
	
	
	public function set_rule( $group, $expression ) {
		if ( ! isset( $this->rules[ $group ] ) ) {
			$this->rules[ $group ] = [];
		}
	}
	
	/**
	 * @param $expression
	 * @param $keys
	 *
	 * @return ParsedExpression
	 */
	protected function parse_rules( $expression, $keys ) {
		return $this->engine->parse( $expression, $keys );
	}
	
	/**
	 * Get Rules
	 *
	 * @return array List of rules.
	 */
	protected function get_rules() {
		return [];
	}
	
	
}
// End of file class-sxp-rules.php.
