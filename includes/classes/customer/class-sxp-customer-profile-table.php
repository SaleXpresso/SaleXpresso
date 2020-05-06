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
            <div class="sxp-customer-wrapper">
	            <div class="sxp-customer-info">
		            <div class="sxp-customer-profile">
			            <div class="customer-type">General</div>
			            <div class="customer-info-wrapper">
				            <div class="customer-thumnb">
					            <img src="<?php echo esc_url( plugin_dir_url( basename(__DIR__ )). 'SaleXpresso/assets/images/customers/customer6.png' ); ?>" alt="Customer Thumbnail">
				            </div>
				            <h3>Norman Howard</h3>
				            <p>normaflores@info.com</p>
				            <p>Cairo, Egypt</p>
			            </div><!-- end .customer-info-wrapper -->
			            <div class="customer-info-history">
				            <div class="history">
					            <p>Acquired via</p>
					            <p>Google</p>
				            </div><!-- end .history -->
				            <div class="history">
					            <p>Revenue</p>
					            <p>$70.75</p>
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
			            </div><!-- end .customer-info-history -->
		            </div>
	            </div><!-- end .sxp-customer-info -->
	            <div class="sxp-customer-details">
		            <ul class="nav nav-tabs">
			            <li class="active"><a href="#">Activity</a></li>
			            <li><a href="#">Orders</a></li>
			            <li><a href="#">Products</a></li>
			            <li><a href="#">Active Cart</a></li>
			            <li><a href="#">Searches</a></li>
			            <li><a href="#">Recommendation</a></li>
			            <li><a href="#">Discount</a></li>
			            <li><a href="#">Campaign</a></li>
		            </ul>
		            <nav>
			            <div class="nav nav-tabs nav-fill" id="nav-tab" role="tablist">
				            <a class="nav-item nav-link active" id="nav-activity-tab" data-toggle="tab" href="#nav-activity" role="tab" aria-controls="nav-activity" aria-selected="true">Activity</a>
				            <a class="nav-item nav-link" id="nav-orders-tab" data-toggle="tab" href="#nav-orders" role="tab" aria-controls="nav-orders" aria-selected="false">Orders</a>
				            <a class="nav-item nav-link" id="nav-products-tab" data-toggle="tab" href="#nav-products" role="tab" aria-controls="nav-products" aria-selected="false">Products</a>
				            <a class="nav-item nav-link" id="nav-active-cart-tab" data-toggle="tab" href="#nav-active-cart" role="tab" aria-controls="nav-active-cart" aria-selected="false">Active Cart</a>
				            <a class="nav-item nav-link" id="nav-searches-tab" data-toggle="tab" href="#nav-searches" role="tab" aria-controls="nav-searches" aria-selected="false">Searches</a>
				            <a class="nav-item nav-link" id="nav-recommendation-tab" data-toggle="tab" href="#nav-recommendation" role="tab" aria-controls="nav-recommendationt" aria-selected="false">Recommendation</a>
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
	            </div><!-- end .sxp-customer-details -->
            </div><!-- end .sxp-customer-wrapper -->
		<?php
	}

}

// End of file class-sxp-customer-list-table.php.