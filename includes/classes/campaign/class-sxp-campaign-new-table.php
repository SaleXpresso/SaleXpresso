<?php
/**
 * SaleXpresso
 *
 * @package SaleXpresso\Campaign
 * @version 1.0.0
 * @since   SaleXpresso v1.0.0
 */

namespace SaleXpresso\Campaign;

if ( ! defined( 'ABSPATH' ) ) {
	header( 'Status: 403 Forbidden' );
	header( 'HTTP/1.1 403 Forbidden' );
	die();
}

/**
 * Class SXP_Customer_New_Table
 *
 * @package SaleXpresso\Campaign
 */
class SXP_Campaign_New_Table {

	/**
	 * SXP_Customer_New_Table constructor.
	 */
	public function __construct() {
		// @TODO Extend WP_New_Table.

		?>
		<p>Campaign new table</p>
		<?php
	}

}

// End of file class-sxp-campaign-new-table.php.
