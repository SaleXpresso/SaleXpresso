<?php
/**
 * SaleXpresso Action Rule
 *
 * @package SaleXpresso\Rules
 * @version 1.0.0
 * @since   1.0.0
 */

namespace SaleXpresso\Abstracts;

if ( ! defined( 'ABSPATH' ) ) {
	header( 'Status: 403 Forbidden' );
	header( 'HTTP/1.1 403 Forbidden' );
	die();
}

/**
 * Class SXP_Action_Rules
 */
abstract class SXP_Action_Rules {
	
	/**
	 * Singleton instance.
	 *
	 * @var SXP_Action_Rules
	 */
	protected static $instance;
	
	/**
	 * Comparison Operators.
	 * These are fixed.
	 *
	 * @var array
	 */
	protected $operators = [];
	
	/**
	 * Data object for this action.
	 *
	 * @var object
	 */
	protected $data;
	
	/**
	 * The left side condition to evaluate with.
	 * We can allow to add more conditions ...
	 *
	 * Schema
	 *
	 * @type array $condition = [
	 *      @type string $label    Condition Label for dropdown.
	 *      @type string $data     Data path to find the value to compare with
	 *      @type string $help     Help Tooltip
	 *      @type string[] $exclude Future use.
	 * ]
	 *
	 * @var array
	 */
	protected $conditions = [];
	
	/**
	 * Singleton instance.
	 */
	public static function get_instance() {
	}
	
	/**
	 * Initialize things.
	 */
	protected function __construct() {
		$this->operators  = [
			'=='          => [
				'label'   => __( 'Equal', 'salexpresso' ),
				'formula' => 'pos_0 == pos_1',
				'types'   => [ 'any' ],
			],
			'==='         => [
				'label'   => __( 'Identical', 'salexpresso' ),
				'formula' => 'pos_0 === pos_1',
				'types'   => [ 'any' ],
			],
			'!='          => [
				'label'   => __( 'Not Equal', 'salexpresso' ),
				'formula' => 'pos_0 != pos_1',
				'types'   => [ 'any' ],
			],
			'!=='         => [
				'label'   => __( 'Not Identical', 'salexpresso' ),
				'formula' => 'pos_0 !== pos_1',
				'types'   => [ 'any' ],
			],
			'<'           => [
				'label'   => __( 'Less Than', 'salexpresso' ),
				'formula' => 'pos_0 < pos_1',
				'types'   => [ 'any' ],
			],
			'>'           => [
				'label'   => __( 'Greater Than', 'salexpresso' ),
				'formula' => 'pos_0 > pos_1',
				'types'   => [ 'any' ],
			],
			'<='          => [
				'label'   => __( 'Less Than Or Equal', 'salexpresso' ),
				'formula' => 'pos_0 <= pos_1',
				'types'   => [ 'any' ],
			],
			'>='          => [
				'label'   => __( 'Greater Than Or Equal', 'salexpresso' ),
				'formula' => 'pos_0 >= pos_1',
				'types'   => [ 'any' ],
			],
			'matches'     => [
				'label'   => __( 'Matches', 'salexpresso' ),
				'formula' => 'pos_0 matches pos_1',
				'types'   => [ 'string' ],
			],
			'not-matches' => [
				'label'   => __( 'Not Natches', 'salexpresso' ),
				'formula' => 'not (pos_0 matches pos_1)',
				'types'   => [ 'string' ],
			],
			'in'          => [
				'label'   => __( 'In', 'salexpresso' ),
				'formula' => 'pos_0 in pos_1',
				'types'   => [ 'array' ],
			],
			'not-in'      => [
				'label'   => __( 'Not In', 'salexpresso' ),
				'formula' => 'pos_0 not in pos_1',
				'types'   => [ 'array' ],
			],
			'between'     => [
				'label'   => __( 'Between', 'salexpresso' ),
				'formula' => 'pos_0 in pos_1..pos_2',
				'types'   => [ 'int', 'int' ],
			],
		];
		$this->conditions = [
			'purchase-amount'    => [
				'label'   => __( 'Purchase Amount', 'salexpresso' ),
				'data'    => 'order.amount',
				'help'    => '',
				'exclude' => [],
			],
			'purchase-quantity'  => [
				'label'   => __( 'Purchase Quantity', 'salexpresso' ),
				'data'    => 'order.quantity',
				'help'    => '',
				'exclude' => [],
			],
			'recurring-purchase' => [
				'label'   => __( 'Recurring Purchase', 'salexpresso' ),
				'data'    => 'user.order_count',
				'help'    => '',
				'exclude' => [],
			],
			'total_spend'        => [
				'label'   => __( 'Customer Live Time Value', 'salexpresso' ),
				'data'    => 'user.total_spend',
				'help'    => '',
				'exclude' => [],
			],
			'product-bought'     => [
				'label'   => __( 'Products Bought', 'salexpresso' ),
				'data'    => 'order.items',
				'help'    => '',
				'exclude' => [],
			],
		];
		$this->hooks();
	}
	
	/**
	 * Bind the hooks.
	 *
	 * @return void
	 */
	protected function hooks() {
	}
	
	/**
	 * Evaluate Rules and execute the action.
	 *
	 * @param int $order_id the order id.
	 * @return void
	 */
	public function evaluate_rules( $order_id ) {
	}
	
	/**
	 * Compile rules to expression syntax.
	 *
	 * @param array $rules Rules.
	 *
	 * @return array Rules with compiled expression for evaluation.
	 */
	protected function compile_rules( $rules ) {
		$parsed = [
			'compiled' => '',
			'relation' => '',
			'rules'    => [],
		];
		if ( isset( $rules['rules'] ) && is_array( $rules['rules'] ) ) {
			$temp = [];
			$rel  = $this->get_relation_operator( $rules['relation'] );
			foreach ( $rules['rules'] as $k => $chunk ) {
				if ( empty( $chunk ) || ! isset( $chunk['relation'], $chunk['rules'] ) ) {
					continue;
				}
				
				$chunk_rel = $this->get_relation_operator( $chunk['relation'], '&&' );
				
				$expressions  = [];
				$parsed_rules = [];
				foreach ( $chunk['rules'] as $rule ) {
					$rule = $this->build_expression( $rule );
					if ( ! $rule ) {
						continue;
					}
					$parsed_rules[] = $rule;
					$expressions[]  = $rule['expression'];
				}
				if ( empty( $expressions ) ) {
					continue;
				}
				$temp[] = '( ' . implode( ' ' . $chunk_rel . ' ', $expressions ) . ' )';
				// save.
				$parsed['rules'][] = [
					'relation' => '&&' === $chunk_rel ? 'AND' : 'OR',
					'rules'    => $parsed_rules,
				];
				unset( $parsed_rules );
			}
			$parsed['compiled'] = implode( ' ' . $rel . ' ', $temp );
			unset( $temp );
		}
		return $parsed;
	}
	
	/**
	 * Get Relation Operator.
	 *
	 * @param string $relation Relation Lateral.
	 * @param string $default Default. OR.
	 *
	 * @return string
	 */
	protected function get_relation_operator( $relation, $default = '||' ) {
		$operator = '';
		$relation = strtoupper( $relation );
		if ( ! in_array( $relation, [ 'AND', 'OR', '&&', '||' ] ) ) {
			$operator = $default;
		}
		
		if ( 'AND' === $relation || '&&' === $relation ) {
			$operator = '&&';
		}
		if ( 'OR' === $relation || '||' === $relation ) {
			$operator = '||';
		}
		
		return $operator;
	}
	
	/**
	 * Build Expression String based on rules.
	 *
	 * @param array $rule raw rule.
	 *
	 * @return array|false rule array with sanitized data and expression.
	 */
	protected function build_expression( $rule ) {
		if ( ! isset( $rule['operator'], $rule['condition'], $rule['values'] ) ) {
			return false;
		}
		$operators  = $this->get_operators();
		$conditions = $this->get_conditions();
		
		if ( ! isset( $operators[ $rule['operator'] ] ) || ! isset( $conditions[ $rule['condition'] ] ) ) {
			return false;
		}
		
		$operator = $operators[ $rule['operator'] ];
		$formula  = $operator['formula'];
		$types    = $operator['types'];
		
		$args_count = count( $types );
		if ( count( $rule['values'] ) !== $args_count ) {
			return false;
		}
		$parsed = [];
		$search = [ 'pos_0' ]; // predefined for condition.
		$count  = count( $rule['values'] );
		for ( $i = 0; $i < $count; $i++ ) {
			$value    = sanitize_text_field( $rule['values'][ $i ] );
			$search[] = 'pos_' . ( $i + 1 );
			if ( empty( $value ) ) {
				$parsed[ $i ] = null;
				continue;
			}
			
			switch ( $types[ $i ] ) {
				case 'int':
					$parsed[ $i ] = is_numeric( $value ) ? (int) $value : null;
					break;
				case 'array':
					$value        = array_map( 'trim', explode( ',', $value ) );
					$value        = array_unique( $value );
					$parsed[ $i ] = '"' . implode( '","', $value ) . '"';
					break;
				case 'any':
				case 'string':
				default:
					// sanitized.
					$parsed[ $i ] = $value;
					break;
			}
		}
		$parsed = array_filter( $parsed );
		if ( count( $parsed ) !== $args_count ) {
			return false;
		}
		$rule['values'] = $parsed;
		array_unshift( $parsed, $conditions[ $rule['condition'] ]['data'] );
		/**
		 * The str_replace has issues with array.
		 *
		 * @see https://www.php.net/manual/en/function.str-replace.php#88569
		 */
		$rule['expression'] = strtr( $formula, array_combine( $search, $parsed ) );
		
		return $rule;
	}
	
	/**
	 * Get supported logic operators.
	 *
	 * @return array
	 */
	public function get_operators() {
		return $this->operators;
	}
	
	/**
	 * Get Conditions.
	 *
	 * @return array
	 */
	public function get_conditions() {
		return $this->conditions;
	}
	
	/**
	 * Get User Term Rules.
	 *
	 * @return array
	 */
	protected function get_rules() {
		return [];
	}
}
// End of file class-sxp-action-rules.php.
