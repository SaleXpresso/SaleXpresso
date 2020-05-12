<?php
/**
 * SaleXpresso
 *
 * @package SaleXpresso\Customer
 * @version 1.0.0
 * @since   SaleXpresso v1.0.0
 */

namespace SaleXpresso\Customer;

if ( ! defined( 'ABSPATH' ) ) {
	header( 'Status: 403 Forbidden' );
	header( 'HTTP/1.1 403 Forbidden' );
	die();
}

/**
 * Class SXP_Customer_Tag_Table
 *
 * @package SaleXpresso
 */
class SXP_Customer_Tag_Table {

	/**
	 * SXP_Customer_Tag_Table constructor.
	 */
	public function __construct() {
		// @TODO Extend WP_List_Table.
		?>
		<p>inside tag table</p>
		<?php
	}
}
