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
 * Class SXP_Customer_Group_Table
 *
 * @package SaleXpresso
 */
class SXP_Customer_Group_Table {

	/**
	 * SXP_Customer_Group_Table constructor.
	 */
	public function __construct() {
		// @TODO Extend WP_List_Table.
		?>
		<div class="sxp-customer-wrapper">
			<div class="sxp-customer-top-wrapper">
				<div class="sxp-customer-search">
					<input type="text" placeholder="Search Customers">
				</div><!-- end .sxp-customer-search -->
				<div class="sxp-customer-btn-wrapper">
					<a href="#" class="sxp-customer-type-btn sxp-btn sxp-btn-default"><i class="fa fa-plus"></i> Customer Type Rules</a>
					<a href="#" class="sxp-customer-add-btn sxp-btn sxp-btn-primary"><i class="fa fa-plus"></i> Add New Customer</a>
				</div>
			</div><!-- end .sxp-customer-top-wrapper -->
			<table class="wp-list-table widefat sxp-table sxp-customer-table">
				<thead>
					<tr>
						<td id="cb" class="manage-column column-cb check-column">
							<label class="screen-reader-text" for="cb-select-all-1">Select All</label>
							<input id="cb-select-all-1" type="checkbox">
						</td>
						<th scope="col" id="sxp-customer" class="manage-column column-title column-primary sortable desc"><a href="#">Customer Type</a></th>
						<th scope="col" id="sxp-customer-campaign" class="manage-column column-author"><a href="#">Campaign Running</a></th>
						<th scope="col" id="sxp-customer-assigned" class="manage-column column-categories"><a href="#">Assigned</a></th>

					</tr>
				</thead>

				<tbody id="the-list">
					<tr id="sxp-customer-list-1" class="sxp-customer-list">
						<th scope="row" class="check-column">
							<label class="screen-reader-text" for="cb-select-1">
								Customer Group
							</label>
							<input id="cb-select-1" type="checkbox" name="post[]" value="1">
						</th>
						<td class="sxp-customer-name sxp-customer-vip" data-colname="sxp-customer"><a href="#" style="background: #FFD0D0">B2C</a></td>
						<td class="sxp-customer-campaign-column" data-colname="sxp-customer-campaign">
							<div class="sxp-customer-compaign-container">
								<ul class="sxp-customer-campaign-list">
									<li><a href="#">Holiday Campaign</a></li>
									<li><a href="">Vip Compaign</a></li>
									<li><a href="">+2</a></li>
								</ul>
							</div><!-- end .sxp-customer-compaign-container -->
						</td>
						<td class="sxp-customer-assigned-column" data-colname="sxp-customer-assigned">799</td>
					</tr><!-- end .sxp-customer-list -->

					<tr id="sxp-customer-list-2" class="sxp-customer-list">
						<th scope="row" class="check-column">
							<label class="screen-reader-text" for="cb-select-1">
								Customer Group
							</label>
							<input id="cb-select-1" type="checkbox" name="post[]" value="1">
						</th>
						<td class="sxp-customer-name sxp-customer-wholeseller" data-colname="sxp-customer"><a href="#" style="background: #E3FFDA">wholeseller</a></td>
						<td class="sxp-customer-campaign-column" data-colname="sxp-customer-campaign">
							<div class="sxp-customer-compaign-container">
								<ul class="sxp-customer-campaign-list">
									<li><a href="#">New Year</a></li>
									<li><a href="">+3</a></li>
								</ul>
							</div><!-- end .sxp-customer-compaign-container -->
						</td>
						<td class="sxp-customer-assigned-column" data-colname="sxp-customer-assigned">27</td>
					</tr><!-- end .sxp-customer-list -->

					<tr id="sxp-customer-list-3" class="sxp-customer-list">
						<th scope="row" class="check-column">
							<label class="screen-reader-text" for="cb-select-1">
								Customer Group
							</label>
							<input id="cb-select-1" type="checkbox" name="post[]" value="1">
						</th>
						<td class="sxp-customer-name sxp-customer-b2b" data-colname="sxp-customer"><a href="#" style="background: #FFCFB5">B2B</a></td>
						<td class="sxp-customer-campaign-column" data-colname="sxp-customer-campaign">
							<div class="sxp-customer-compaign-container">
								<ul class="sxp-customer-campaign-list">
									<li><a href="#">Sports lover</a></li>
									<li><a href="#">Creative People Campaign</a></li>
									<li><a href="#">Special Day Events</a></li>
									<li><a href="">+2</a></li>
								</ul>
							</div><!-- end .sxp-customer-compaign-container -->
						</td>
						<td class="sxp-customer-assigned-column" data-colname="sxp-customer-assigned">27</td>
					</tr><!-- end .sxp-customer-list -->

					<tr id="sxp-customer-list-5" class="sxp-customer-list">
						<th scope="row" class="check-column">
							<label class="screen-reader-text" for="cb-select-1">
								Customer Group
							</label>
							<input id="cb-select-1" type="checkbox" name="post[]" value="1">
						</th>
						<td class="sxp-customer-name sxp-customer-distributor" data-colname="sxp-customer"><a href="#" style="background: #DAE4FF">Distributor</a></td>
						<td class="sxp-customer-campaign-column" data-colname="sxp-customer-campaign">
							<div class="sxp-customer-compaign-container">
								<ul class="sxp-customer-campaign-list">
									<li><a href="#">Doctor</a></li>
									<li><a href="">+5</a></li>
								</ul>
							</div><!-- end .sxp-customer-compaign-container -->
						</td>
						<td class="sxp-customer-assigned-column" data-colname="sxp-customer-assigned">27</td>
					</tr><!-- end .sxp-customer-list -->

				</tbody>

			</table><!-- end .sxp-customer-table -->
		</div><!-- end .sxp-customer-wrapper -->
		<?php
	}
}