<?php
/**
 * SaleXpresso
 *
 * @package SaleXpresso\Customer
 * @version 1.0.0
 * @since   1.0.0
 */

namespace SaleXpresso\Customer;

use SaleXpresso\SXP_Admin_Menus;
use SaleXpresso\Abstracts\SXP_Admin_Page;
use SaleXpresso\SXP_Post_Types;
use WP_User_Query;

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
class SXP_Customer_Tag_Page extends SXP_Customer_Group_Page {
	
	/**
	 * The Taxonomy.
	 *
	 * @var string
	 */
	protected $taxonomy_name = SXP_Post_Types::CUSTOMER_TAG_TAX;
}

// End of file class-sxp-customer-tag-page.php.
