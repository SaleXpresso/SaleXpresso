<?php
/**
 * SaleXpresso
 *
 * @package SaleXpresso\Customer
 * @version 1.0.0
 * @since   1.0.0
 */

namespace SaleXpresso\Customer;

use Exception;
use WC_Customer;
use WC_Product;
use WP_Term;
use WP_User;

if ( ! defined( 'ABSPATH' ) ) {
	header( 'Status: 403 Forbidden' );
	header( 'HTTP/1.1 403 Forbidden' );
	die();
}

/**
 * Class SXP_Customers_Page
 *
 * @package SaleXpresso\Customer
 */
final class SXP_Customer extends WC_Customer {
	
	/**
	 * WC Customer Lookup table ID.
	 * @var int
	 */
	private $lookup_id;
	
	/**
	 * User's Group.
	 *
	 * @var WP_Term
	 */
	private $group;
	
	/**
	 * User's tags.
	 *
	 * @var WP_Term[]
	 */
	private $tags;
	
	/**
	 * User's Type.
	 *
	 * @var WP_Term[]
	 */
	private $types;
	
	/**
	 * SXP_Customer constructor.
	 *
	 * @param int|string|WP_User|WC_Customer $user User id, email or user object.
	 *
	 * @throws Exception
	 */
	public function __construct( $user ) {
		if ( ! ( $user instanceof WC_Customer ) ) {
			$user = sxp_get_user( $user );
			$user = $user->ID;
		}
		parent::__construct( $user, false );
		
		$this->lookup_id = sxp_get_user_customer_id( $this->get_id() );
		$group           = sxp_get_user_group( $this->get_id() );
		$types           = sxp_get_user_types( $this->get_id() );
		$tags            = sxp_get_user_tags( $this->get_id() );
		
		if ( $group && ! is_wp_error( $group ) ) {
			$this->group = $group;
		}
		
		if ( $tags && ! is_wp_error( $tags ) ) {
			$this->tags = $tags;
		}
		
		if ( $types && ! is_wp_error( $types ) ) {
			$this->types = $types;
		}
	}
	
	/**
	 * Get User's Group.
	 *
	 * @return WP_Term
	 */
	public function get_group() {
		return $this->group;
	}
	
	/**
	 * Get User's Tags.
	 *
	 * @return WP_Term[]
	 */
	public function get_tags() {
		return $this->tags;
	}
	
	/**
	 * Get User's Types.
	 *
	 * @return WP_Term[]
	 */
	public function get_types() {
		return $this->types;
	}
	
	/**
	 * Get Customer lookup id.
	 * @return bool|int
	 */
	public function get_lookup_id() {
		return $this->lookup_id;
	}
	
	/**
	 * Check if user can buy the product.
	 *
	 * @param int|WC_Product $product
	 *
	 * @return bool
	 */
	public function can_buy( $product ) {
		$product = wc_get_product( $product );
		if (! $product ) {
			return false;
		}
		$can_buy = true;
		if ( $this->get_group() ) {
			$no_purchase = sprintf( '_sxp_group_%s_no_purchase', $this->get_group()->term_id );
			$no_purchase = $product->get_meta( $no_purchase, true );
			$can_buy = ! ( $no_purchase && 'yes' === $no_purchase );
		}
		
		$can_buy = (bool) apply_filters( "salexpresso_customer_can_buy_{$product->get_id()}", $can_buy, $this->get_id(), $this->group );
		
		return (bool) apply_filters( 'salexpresso_customer_can_buy', $can_buy, $this->get_id(), $this->group, $product );
	}
	
	/**
	 * Check if user can buy the product.
	 *
	 * @param int|WC_Product $product
	 *
	 * @return array
	 */
	public function get_purchase_restrictions( $product ) {
		$product = wc_get_product( $product );

		// @TODO get product min/max set by WC as default value
		$restrictions = [
			'min_qty' => 0,
			'max_qty' => 0,
		];

		if ( ! $product ) {
			return $restrictions;
		}

		if ( $this->can_buy( $product ) ) {
			$min = sprintf( '_sxp_group_%s_purchase_min_quantity', $this->get_group()->term_id );
			$max = sprintf( '_sxp_group_%s_purchase_max_quantity', $this->get_group()->term_id );
			
			$restrictions = [
				'min_qty' => absint( $product->get_meta( $min, true ) ),
				'max_qty' => absint( $product->get_meta( $max, true ) ),
			];
		}
		
		$restrictions = apply_filters( "salexpresso_customer_purchase_restrictions_{$product->get_id()}", $restrictions, $this->get_id(), $this->group );
		
		return apply_filters( 'salexpresso_customer_purchase_restrictions', $restrictions, $this->get_id(), $this->group, $product );
	}
}

// End of file class-sxp-customers-page.php.
