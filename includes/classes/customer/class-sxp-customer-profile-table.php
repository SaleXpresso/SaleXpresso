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
						<table class="sxp-table sxp-customer-profile-table">
							<thead>
								<tr>
									<th scope="col" class="manage-column column-title column-primary sortable desc"><a href="#">All Sessions</a></th>
									<th scope="col" class="manage-column column-title column-primary sortable desc"><a href="#">Source</a></th>
									<th scope="col" class="manage-column column-title column-primary sortable desc"><a href="#">Actions</a></th>
									<th scope="col" class="manage-column column-title column-primary sortable desc"><a href="#">Timestamp</a></th>
								</tr>
							</thead>
							<tbody id="the-list">
								<tr>
									<td data-colname="All Sessions">
										<div class="session-name">Session no. 13</div>
										<ul class="sxp-tag-list">
											<li><a href="#">Did added anything to cart</a></li>
										</ul>
									</td>
									<td data-colname="Soruce">facebook</td>
									<td data-colname="Actions">5</td>
									<td data-colname="Timestamp">Jan 20, 2020</td>
								</tr>
								<tr>
									<td data-colname="All Sessions">
										<div class="session-name">Session no. 14</div>
										<ul class="sxp-tag-list">
											<li><a href="#">Purchased 1 item</a></li>
										</ul>
									</td>
									<td data-colname="Soruce">facebook</td>
									<td data-colname="Actions">5</td>
									<td data-colname="Timestamp">Jan 20, 2020</td>
								</tr>
								<tr class="has-fold">
									<td data-colname="All Sessions">
										<div class="session-name">Session no. 15</div>
										<ul class="sxp-tag-list">
											<li><a href="#">6 items added to cart</a></li>
										</ul>
									</td>
									<td data-colname="Soruce">facebook</td>
									<td data-colname="Actions">5</td>
									<td data-colname="Timestamp">Jan 20, 2020</td>
								</tr>
								<tr class="fold">
									<td colspan="8">
										<div class="sxp-fold-content">
											<div class="sxp-table-viewed">
												<i data-feather="eye"></i>
												<span class="serial">1.</span>Viewed
												<a href="#" class="product">Fresh Refined Sugar</a> Product
											</div>
											<div>5s later</div>
										</div><!-- end .sxp-fold-content -->
										<div class="sxp-fold-content">
											<div class="sxp-table-viewed">
												<i data-feather="eye"></i>
												<span class="serial">2.</span>Viewed
												<a href="#" class="product">Fresh Refined Sugar</a> Product
											</div>
											<div>5s later</div>
										</div><!-- end .sxp-fold-content -->
										<div class="sxp-fold-content">
											<div class="sxp-table-viewed">
												<i data-feather="plus-circle"></i>
												<span class="serial">3.</span>Added
												<a href="#" class="product">Mum Drinking Wather</a> to cart
											</div>
											<div>5s later</div>
										</div><!-- end .sxp-fold-content -->
										<div class="sxp-fold-content">
											<div class="sxp-table-viewed">
												<i data-feather="x-octagon"></i>
												<span class="serial">4.</span>Removed
												<a href="#" class="product">Mum Drinking Wather</a> from cart
											</div>
											<div>5s later</div>
										</div><!-- end .sxp-fold-content --><div class="sxp-fold-content">
											<div class="sxp-table-viewed">
												<i data-feather="shopping-cart"></i>
												<span class="serial">5.</span>Completed checkout with American Express
											</div>
											<div>5s later</div>
										</div><!-- end .sxp-fold-content -->

									</td>
								</tr>
								<tr>
									<td data-colname="All Sessions">
										<div class="session-name">Session no. 16</div>
										<ul class="sxp-tag-list">
											<li><a href="#">Did added anything to cart</a></li>
										</ul>
									</td>
									<td data-colname="Soruce">facebook</td>
									<td data-colname="Actions">5</td>
									<td data-colname="Timestamp">Jan 20, 2020</td>
								</tr>
								<tr>
									<td data-colname="All Sessions">
										<div class="session-name">Session no. 17</div>
										<ul class="sxp-tag-list">
											<li><a href="#">Purchased 6 items</a></li>
										</ul>
									</td>
									<td data-colname="Soruce">facebook</td>
									<td data-colname="Actions">5</td>
									<td data-colname="Timestamp">Jan 20, 2020</td>
								</tr>
							</tbody>
						</table>
					</div><!-- end .tab-content -->
					<div id="orders" class="tab-content">
						<table class="sxp-table sxp-customer-profile-table">
							<thead>
							<tr>
								<th scope="col" class="manage-column column-title column-primary sortable desc"><a href="#">Order Id</a></th>
								<th scope="col" class="manage-column column-title column-primary sortable desc"><a href="#">products</a></th>
								<th scope="col" class="manage-column column-title column-primary sortable desc"><a href="#">Date</a></th>
								<th scope="col" class="manage-column column-title column-primary sortable desc"><a href="#">Revenue</a></th>
								<th scope="col" class="manage-column column-title column-primary sortable desc"><a href="#">Net Profit</a></th>
								<th scope="col" class="manage-column column-title column-primary sortable desc"><a href="#">Status</a></th>
							</tr>
							</thead>
							<tbody id="the-list">
								<tr>
									<td data-colname="Order Id"><a href="£" class="order-number">200083726</a></td>
									<td data-colname="Products">
										<ul class="product-list multiple">
											<li><a href="#"><img src="https://via.placeholder.com/40" alt="Product thumb"></a></li>
											<li><a href="#"><img src="https://via.placeholder.com/40" alt="Product thumb"></a></li>
											<li><a href="#"><img src="https://via.placeholder.com/40" alt="Product thumb"></a></li>
											<li><a href="#"><img src="https://via.placeholder.com/40" alt="Product thumb"></a></li>
										</ul>
										<div class="product-number">9 Items</div>
									</td>
									<td data-colname="Date">Jan 20, 2020</td>
									<td data-colname="Revenue">$5739.2</td>
									<td data-colname="Net Profit">$87.03</td>
									<td data-colname="status">
										<div class="sxp-status sxp-status-success">Completed</div>
									</td>
								</tr>
								<tr>
									<td data-colname="Order Id"><a href="£" class="order-number">200083726</a></td>
									<td data-colname="Products">
										<ul class="product-list multiple">
											<li><a href="#"><img src="https://via.placeholder.com/40" alt="Product thumb"></a></li>
											<li><a href="#"><img src="https://via.placeholder.com/40" alt="Product thumb"></a></li>
											<li><a href="#"><img src="https://via.placeholder.com/40" alt="Product thumb"></a></li>
										</ul>
										<div class="product-number">9 Items</div>
									</td>
									<td data-colname="Date">Jan 20, 2020</td>
									<td data-colname="Revenue">$5739.2</td>
									<td data-colname="Net Profit">$87.03</td>
									<td data-colname="status">
										<div class="sxp-status sxp-status-info">Refunded</div>
									</td>
								</tr>
								<tr>
									<td data-colname="Order Id"><a href="£" class="order-number">200083726</a></td>
									<td data-colname="Products">
										<ul class="product-list">
											<li><a href="#"><img src="https://via.placeholder.com/40" alt="Product thumb"></a></li>
										</ul>
										<div class="product-number">9 Items</div>
									</td>
									<td data-colname="Date">Jan 20, 2020</td>
									<td data-colname="Revenue">$5739.2</td>
									<td data-colname="Net Profit">$87.03</td>
									<td data-colname="status">
										<div class="sxp-status sxp-status-danger">Canceled</div>
									</td>
								</tr>
							</tbody>

						</table>
					</div><!-- end .tab-content -->
					<div id="products" class="tab-content">
						Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur.
					</div><!-- end .tab-content -->
					<div id="active-cart" class="tab-content">
						Sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat.
					</div><!-- end .tab-content -->
					<div id="searches" class="tab-content">
						Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat.
					</div><!-- end .tab-content -->
					<div id="recommendation" class="tab-content">
						Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.
					</div><!-- end .tab-content -->
					<div id="discount" class="tab-content">
						Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur.
					</div><!-- end .tab-content -->
					<div id="campaign" class="tab-content">
						Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat.
					</div><!-- end .tab-content -->
				</div><!-- end .sxp-profile-details -->
			</div><!-- end .sxp-profile-wrapper -->
		<?php
	}

}

// End of file class-sxp-profile-list-table.php.
