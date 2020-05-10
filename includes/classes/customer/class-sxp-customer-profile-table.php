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
					<nav>
						<div class="nav nav-tabs nav-fill" id="nav-tab" role="tablist">
							<a class="nav-item nav-link active" id="nav-activity-tab" data-toggle="tab" href="#nav-activity" role="tab" aria-controls="nav-activity" aria-selected="true">Activity</a>
							<a class="nav-item nav-link" id="nav-orders-tab" data-toggle="tab" href="#nav-orders" role="tab" aria-controls="nav-orders" aria-selected="false">Orders</a>
							<a class="nav-item nav-link" id="nav-products-tab" data-toggle="tab" href="#nav-products" role="tab" aria-controls="nav-products" aria-selected="false">Products</a>
							<a class="nav-item nav-link" id="nav-active-cart-tab" data-toggle="tab" href="#nav-active-cart" role="tab" aria-controls="nav-active-cart" aria-selected="false">Active Cart</a>
							<a class="nav-item nav-link" id="nav-searches-tab" data-toggle="tab" href="#nav-searches" role="tab" aria-controls="nav-searches" aria-selected="false">Searches</a>
							<a class="nav-item nav-link" id="nav-recommendation-tab" data-toggle="tab" href="#nav-recommendation" role="tab" aria-controls="nav-recommendation" aria-selected="false">Recommendation</a>
							<a class="nav-item nav-link" id="nav-discount-tab" data-toggle="tab" href="#nav-discount" role="tab" aria-controls="nav-discount" aria-selected="false">Discount</a>
							<a class="nav-item nav-link" id="nav-campaign-tab" data-toggle="tab" href="#nav-campaign" role="tab" aria-controls="nav-campaign" aria-selected="false">Campaign</a>

						</div>
					</nav>
					<div class="tab-content py-3 px-3 px-sm-0" id="nav-tabContent">
						<div class="tab-pane fade show active" id="nav-activity" role="tabpanel" aria-labelledby="nav-activity-tab">
							activity tab
						</div>
						<div class="tab-pane fade" id="nav-orders" role="tabpanel" aria-labelledby="nav-orders-tab">
							orders tab
						</div>
						<div class="tab-pane fade" id="nav-products" role="tabpanel" aria-labelledby="nav-products-tab">
							products
						</div>
						<div class="tab-pane fade" id="nav-active-cart" role="tabpanel" aria-labelledby="nav-active-cart-tab">
							active cart
						</div>
						<div class="tab-pane fade" id="nav-searches" role="tabpanel" aria-labelledby="nav-searches-tab">
							searches tab
						</div>
						<div class="tab-pane fade" id="nav-recommendation" role="tabpanel" aria-labelledby="nav-recommendation-tab">
							Recommendation tab
						</div>
						<div class="tab-pane fade" id="nav-discount" role="tabpanel" aria-labelledby="nav-discount-tab">
							discount tab
						</div>
						<div class="tab-pane fade" id="nav-campaign" role="tabpanel" aria-labelledby="nav-campaign-tab">
							campaign tab
						</div>
					</div>
				</div><!-- end .sxp-profile-details -->
			</div><!-- end .sxp-profile-wrapper -->
		<?php
	}

}

// End of file class-sxp-profile-list-table.php.
