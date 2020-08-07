<?php
/**
 * Expression Engine.
 *
 * This class helps validate rules in backend and evaluate rules/expression and execute action for
 * user activity on the frontend.
 *
 * @link https://symfony.com/doc/current/components/expression_language/syntax.html
 * @package SaleXpresso\Rules
 * @version 1.0.0
 * @since   1.0.0
 */

namespace SaleXpresso;

use Symfony\Component\ExpressionLanguage\ExpressionLanguage;
use Symfony\Component\ExpressionLanguage\ParsedExpression;

if ( ! defined( 'ABSPATH' ) ) {
	header( 'Status: 403 Forbidden' );
	header( 'HTTP/1.1 403 Forbidden' );
	die();
}

/**
 * Class SXP_Rule
 *
 * @package SaleXpresso
 */
final class SXP_Expression {
	
	/**
	 * ExpressionLanguage Instance.
	 *
	 * @var ExpressionLanguage
	 */
	protected $engine;
	
	/**
	 * The Rule.
	 *
	 * @var string
	 */
	protected $expression = '';
	
	/**
	 * The Data.
	 *
	 * @var array
	 */
	protected $data = [];
	
	/**
	 * Parsed expression.
	 *
	 * @var ParsedExpression
	 */
	protected $parsed;
	
	/**
	 * Result.
	 *
	 * @var mixed
	 */
	protected $result;
	
	/**
	 * Cache key for saving parsed expression.
	 *
	 * @var string
	 */
	protected $cache_key = '';
	
	/**
	 * SXP_Expression constructor.
	 */
	public function __construct() {
		
		if ( is_null( $this->engine ) ) {
			$this->engine = new ExpressionLanguage();
		}
		
		$this->get_engine();
	}
	
	/**
	 * ExpressionLanguage Instance.
	 *
	 * @return ExpressionLanguage
	 */
	public function get_engine() {
		return $this->engine;
	}
	
	/**
	 * Set unparsed expression
	 *
	 * @param string|string[] $expression The unparsed Expression.
	 *
	 * @return self
	 */
	public function set_expression( $expression ) {
		$this->expression = $expression;
		return $this;
	}
	
	/**
	 * Get current expression.
	 *
	 * @return string
	 */
	public function get_expression() {
		return $this->expression;
	}
	
	/**
	 * Set the data array.
	 *
	 * @param array $data The Data array.
	 *
	 * @return self
	 */
	public function set_data( array $data ) {
		$this->data = $data;
		return $this;
	}
	
	/**
	 * Get the data.
	 *
	 * @return array
	 */
	public function get_data() {
		return $this->data;
	}
	
	/**
	 * Set Parsed Expression.
	 *
	 * @param ParsedExpression $expression Parsed expression to set.
	 *
	 * @return self
	 */
	public function set_parsed_expression( $expression ) {
		$this->parsed = $expression;
		return $this;
	}
	
	/**
	 * Parse the rule.
	 *
	 * @return self
	 */
	public function parse_expression() {
		if ( ! ( $this->parsed instanceof ParsedExpression ) && ! empty( $this->expression ) && ! empty( $this->data ) ) {
			$this->parsed = $this->engine->parse( $this->expression, array_keys( $this->data ) );
		}
		return $this;
	}
	
	/**
	 * Compile Expression.
	 * This should be using for debugging purpose.
	 *
	 * @return string
	 */
	public function compile() {
		return $this->engine->compile( $this->get_expression(), array_keys( $this->get_data() ) );
	}
	
	/**
	 * Get Parsed Expression.
	 *
	 * @return ParsedExpression|null
	 */
	public function get_parsed() {
		return $this->parsed;
	}
	
	/**
	 * Execute/Evaluate Current expression.
	 *
	 * @return self
	 */
	public function execute() {
		$this->parse_expression();
		$this->result = $this->engine->evaluate( $this->get_parsed(), $this->get_data() );
		return $this;
	}
	
	/**
	 * Get The Result.
	 *
	 * @return mixed
	 */
	public function get_result() {
		$this->execute();
		return $this->result;
	}
	
	/**
	 * Reset Properties for next use.
	 *
	 * @return void
	 */
	public function reset() {
		$this->result = null;
		$this->set_expression( '' );
		$this->set_data( [] );
		$this->set_parsed_expression( null );
	}
}

// add_action( 'init', function () {
// new SXP_Expression();
// }, 10 );
// End of file class-sxp-expression.php.
