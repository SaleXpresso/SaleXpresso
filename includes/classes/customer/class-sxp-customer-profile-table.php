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
 * Class SXP_Customer_Profile_Table
 *
 * @package SaleXpresso\Customer
 */
class SXP_Customer_Profile_Table {

	/**
	 * SXP_Customer_List_Table constructor.
	 */
	public function __construct() {
		// @TODO Extend WP_List_Table.

		?>
			<div class="sxp-profile-wrapper">
				<div class="sxp-profile-info">
					<div class="sxp-profile-profile">
						<div class="sxp-profile-type" style="background: #DAE4FF;">General</div>
						<div class="sxp-profile-info-wrapper">
							<div class="sxp-profile-thumb">
								<img src="<?php echo esc_url( plugin_dir_url( basename(__DIR__ )) . 'SaleXpresso/assets/images/profile.png' ); ?>" alt="Customer Thumbnail">
							</div>
							<h3>Norman Howard</h3>
							<p>normaflores@info.com</p>
							<p>Cairo, Egypt</p>
						</div><!-- end .sxp-profile-info-wrapper -->
						<div class="sxp-profile-info-history">
							<div class="history">
								<p>Acquired via</p>
								<p><span class="history-bold">Google</span></p>
							</div><!-- end .history -->
							<div class="history">
								<p>Revenue</p>
								<p><span class="history-bold">$70.75</span></p>
							</div><!-- end .history -->
							<div class="history">
								<p>Sessions</p>
								<p>2</p>
							</div><!-- end .history -->
							<div class="history">
								<p>Orders</p>
								<p>1</p>
							</div><!-- end .history -->
							<div class="history">
								<p>Actions</p>
								<p>32</p>
							</div><!-- end .history -->
							<div class="history">
								<p>First Order</p>
								<p>24 Jan, 2019</p>
							</div><!-- end .history -->
							<div class="history">
								<p>Last Order</p>
								<p>29 Mar, 2020</p>
							</div><!-- end .history -->
							<div class="history">
								<p>Last Active</p>
								<p>2 Apr, 2020</p>
							</div><!-- end .history -->
						</div><!-- end .profile-info-history -->
					</div>
				</div><!-- end .sxp-profile-info -->
				<div class="sxp-profile-details">
					<ul class="tabs">
						<li class="tab-link current" data-tab="activity">
							Activity
						</li>
						<li class="tab-link" data-tab="orders">
							Orders
						</li>
						<li class="tab-link" data-tab="products">
							Products
						</li>
						<li class="tab-link" data-tab="active-cart">
							Active Cart
						</li>
						<li class="tab-link" data-tab="searches">
							Searches
						</li>
						<li class="tab-link" data-tab="recommendation">
							Recommendation
						</li>
						<li class="tab-link" data-tab="discount">
							Discount
						</li>
						<li class="tab-link" data-tab="campaign">
							Campaign
							<div class="number-bubble">54</div>
						</li>
					</ul>

					<div id="activity" class="tab-content current">
						Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat.
					</div>
					<div id="orders" class="tab-content">
						Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.
					</div>
					<div id="products" class="tab-content">
						Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur.
					</div>
					<div id="active-cart" class="tab-content">
						Sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat.
					</div>
					<div id="searches" class="tab-content">
						Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat.
					</div>
					<div id="recommendation" class="tab-content">
						Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.
					</div>
					<div id="discount" class="tab-content">
						Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur.
					</div>
					<div id="campaign" class="tab-content current">
						Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat.
					</div>
				</div><!-- end .sxp-profile-details -->
			</div><!-- end .sxp-profile-wrapper -->
		<?php
	}

}

// End of file class-sxp-profile-list-table.php.
