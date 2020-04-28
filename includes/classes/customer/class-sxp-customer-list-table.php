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
 * Class SXP_Customer_List_Table
 *
 * @package SaleXpresso\Customer
 */
class SXP_Customer_List_Table {
	
	/**
	 * SXP_Customer_List_Table constructor.
	 */
	public function __construct() {
		// @TODO Extend WP_List_Table.

        ?>
		<div class="sxp-customer-list-wrapper">
            <div class="sxp-customer-top-wrapper">
				<div class="sxp-customer-search">
					<input type="text" placeholder="Search Customers">
				</div><!-- end .sxp-customer-search -->
	            <div class="sxp-customer-btn-wrapper">
		            <a href="#" class="sxp-customer-type-btn sxp-btn sxp-btn-default">Customer Type Rules</a>
		            <a href="#" class="sxp-customer-add-btn sxp-btn sxp-btn-primary">Add New Customer</a>
	            </div>
            </div><!-- end .sxp-customer-top-wrapper -->
			<table class="wp-list-table widefat sxp-table sxp-customer-table">
				<thead>
					<tr>
						<td id="cb" class="manage-column column-cb check-column">
							<label class="screen-reader-text" for="cb-select-all-1">Select All</label>
							<input id="cb-select-all-1" type="checkbox">
						</td>
						<th scope="col" id="sxp-customer-customers" class="manage-column column-categories">Customers</th>
						<th scope="col" id="sxp-customer" class="manage-column column-title column-primary sortable desc"><a href="#"><span>Customer Type</span><span class="sorting-indicator"></span></a></th>
						<th scope="col" id="sxp-customer-tag" class="manage-column column-author">Customer Tag</th>
						<th scope="col" id="sxp-customer-order" class="manage-column column-categories">Orders</th>
						<th scope="col" id="sxp-customer-revenue" class="manage-column column-categories">Revenue</th>
						<th scope="col" id="sxp-customer-last-order" class="manage-column column-categories">Last Order</th>
					</tr>
				</thead>

				<tbody id="the-list">
                    <tr id="sxp-customer-list-1" class="sxp-customer-list">
	                    <th scope="row" class="check-column">
	                        <label class="screen-reader-text" for="cb-select-1"></label>
	                        <input id="cb-select-1" type="checkbox" name="post[]" value="1">
	                    </th>
	                    <td class="sxp-customers-column" data-colname="sxp-customer-customers">
	                        <div class="sxp-customer-desc">
	                            <div class="sxp-customer-desc-thumbnail">
	                                <img src="<?php echo esc_url( plugin_dir_url( basename(__DIR__ )). 'SaleXpresso/assets/images/customers/customer1.png' ); ?>" alt="Customer Thumbnail">
	                            </div><!-- end .sxp-customer-desc-thumbnail -->
	                            <div class="sxp-customer-desc-details">
	                                <p class="sxp-customer-desc-details-name">Wendy Bell</p>
	                                <p class="sxp-customer-desc-details-location">Vermont</p>
	                            </div><!-- end .sxp-customer-desc-detaisl -->
	                        </div><!-- end .sxp-customer-desc -->
	                    </td>
	                    <td class="sxp-customer-name" data-colname="sxp-customer"><a href="#">VIP</a></td>
	                    <td class="sxp-customer-tag-column" data-colname="sxp-customer-tag">
	                        <div class="sxp-customer-tag-container">
	                            <ul class="sxp-customer-tag-list">
	                                <li><a href="#">Holiday Campaign</a></li>
	                                <li><a href="">+2</a></li>
	                            </ul>
	                        </div><!-- end .sxp-customer-compaign-container -->
	                    </td>
	                    <td class="sxp-customer-assigned-column" data-colname="sxp-customer-order">799</td>
	                    <td class="sxp-customer-revenue-column" data-colname="sxp-customer-revenue">$6910.60</td>
	                    <td class="sxp-customer-last-order-column" data-colname="sxp-customer-last-order">23 days ago</td>
	                </tr><!-- end .sxp-customer-list -->
                    <tr id="sxp-customer-list-2" class="sxp-customer-list">
	                    <th scope="row" class="check-column">
		                    <label class="screen-reader-text" for="cb-select-1"></label>
		                    <input id="cb-select-1" type="checkbox" name="post[]" value="1">
	                    </th>
	                    <td class="sxp-customers-column" data-colname="sxp-customer-customers">
		                    <div class="sxp-customer-desc">
			                    <div class="sxp-customer-desc-thumbnail">
				                    <img src="<?php echo esc_url( plugin_dir_url( basename(__DIR__ )). 'SaleXpresso/assets/images/customers/customer2.png' ); ?>" alt="Customer Thumbnail">
			                    </div><!-- end .sxp-customer-desc-thumbnail -->
			                    <div class="sxp-customer-desc-details">
				                    <p class="sxp-customer-desc-details-name">Jane Nguyen</p>
				                    <p class="sxp-customer-desc-details-location">Vermont</p>
			                    </div><!-- end .sxp-customer-desc-detaisl -->
		                    </div><!-- end .sxp-customer-desc -->
	                    </td>
	                    <td class="sxp-customer-name" data-colname="sxp-customer"><a href="#">Gold</a></td>
	                    <td class="sxp-customer-tag-column" data-colname="sxp-customer-tag">
		                    <div class="sxp-customer-tag-container">
			                    <ul class="sxp-customer-tag-list">
				                    <li><a href="#">New Year</a></li>
				                    <li><a href="">+2</a></li>
			                    </ul>
		                    </div><!-- end .sxp-customer-compaign-container -->
	                    </td>
	                    <td class="sxp-customer-assigned-column" data-colname="sxp-customer-order">727</td>
	                    <td class="sxp-customer-revenue-column" data-colname="sxp-customer-revenue">$3535.92</td>
	                    <td class="sxp-customer-last-order-column" data-colname="sxp-customer-last-order">23 days ago</td>
                    </tr><!-- end .sxp-customer-list -->
                    <tr id="sxp-customer-list-3" class="sxp-customer-list">
	                    <th scope="row" class="check-column">
		                    <label class="screen-reader-text" for="cb-select-1"></label>
		                    <input id="cb-select-1" type="checkbox" name="post[]" value="1">
	                    </th>
	                    <td class="sxp-customers-column" data-colname="sxp-customer-customers">
		                    <div class="sxp-customer-desc">
			                    <div class="sxp-customer-desc-thumbnail">
				                    <img src="<?php echo esc_url( plugin_dir_url( basename(__DIR__ )). 'SaleXpresso/assets/images/customers/customer3.png' ); ?>" alt="Customer Thumbnail">
			                    </div><!-- end .sxp-customer-desc-thumbnail -->
			                    <div class="sxp-customer-desc-details">
				                    <p class="sxp-customer-desc-details-name">Jane Nguyen</p>
				                    <p class="sxp-customer-desc-details-location">Vermont</p>
			                    </div><!-- end .sxp-customer-desc-detaisl -->
		                    </div><!-- end .sxp-customer-desc -->
	                    </td>
	                    <td class="sxp-customer-name" data-colname="sxp-customer"><a href="#">Gold</a></td>
	                    <td class="sxp-customer-tag-column" data-colname="sxp-customer-tag">
		                    <div class="sxp-customer-tag-container">
			                    <ul class="sxp-customer-tag-list">
				                    <li><a href="#">Sports Lover</a></li>
				                    <li><a href="">+4</a></li>
			                    </ul>
		                    </div><!-- end .sxp-customer-compaign-container -->
	                    </td>
	                    <td class="sxp-customer-assigned-column" data-colname="sxp-customer-order">727</td>
	                    <td class="sxp-customer-revenue-column" data-colname="sxp-customer-revenue">$3535.92</td>
	                    <td class="sxp-customer-last-order-column" data-colname="sxp-customer-last-order">23 days ago</td>
                    </tr><!-- end .sxp-customer-list -->
                    <tr id="sxp-customer-list-4" class="sxp-customer-list">
	                    <th scope="row" class="check-column">
		                    <label class="screen-reader-text" for="cb-select-1"></label>
		                    <input id="cb-select-1" type="checkbox" name="post[]" value="1">
	                    </th>
	                    <td class="sxp-customers-column" data-colname="sxp-customer-customers">
		                    <div class="sxp-customer-desc">
			                    <div class="sxp-customer-desc-thumbnail">
				                    <img src="<?php echo esc_url( plugin_dir_url( basename(__DIR__ )). 'SaleXpresso/assets/images/customers/customer4.png' ); ?>" alt="Customer Thumbnail">
			                    </div><!-- end .sxp-customer-desc-thumbnail -->
			                    <div class="sxp-customer-desc-details">
				                    <p class="sxp-customer-desc-details-name">Jane Nguyen</p>
				                    <p class="sxp-customer-desc-details-location">Vermont</p>
			                    </div><!-- end .sxp-customer-desc-detaisl -->
		                    </div><!-- end .sxp-customer-desc -->
	                    </td>
	                    <td class="sxp-customer-name" data-colname="sxp-customer"><a href="#">Gold</a></td>
	                    <td class="sxp-customer-tag-column" data-colname="sxp-customer-tag">
		                    <div class="sxp-customer-tag-container">
			                    <ul class="sxp-customer-tag-list">
				                    <li><a href="#">Birthday</a></li>
				                    <li><a href="">+2</a></li>
			                    </ul>
		                    </div><!-- end .sxp-customer-compaign-container -->
	                    </td>
	                    <td class="sxp-customer-assigned-column" data-colname="sxp-customer-order">727</td>
	                    <td class="sxp-customer-revenue-column" data-colname="sxp-customer-revenue">$3535.92</td>
	                    <td class="sxp-customer-last-order-column" data-colname="sxp-customer-last-order">23 days ago</td>
                    </tr><!-- end .sxp-customer-list -->
                    <tr id="sxp-customer-list-5" class="sxp-customer-list">
	                    <th scope="row" class="check-column">
		                    <label class="screen-reader-text" for="cb-select-1"></label>
		                    <input id="cb-select-1" type="checkbox" name="post[]" value="1">
	                    </th>
	                    <td class="sxp-customers-column" data-colname="sxp-customer-customers">
		                    <div class="sxp-customer-desc">
			                    <div class="sxp-customer-desc-thumbnail">
				                    <img src="<?php echo esc_url( plugin_dir_url( basename(__DIR__ )). 'SaleXpresso/assets/images/customers/customer5.png' ); ?>" alt="Customer Thumbnail">
			                    </div><!-- end .sxp-customer-desc-thumbnail -->
			                    <div class="sxp-customer-desc-details">
				                    <p class="sxp-customer-desc-details-name">Jane Nguyen</p>
				                    <p class="sxp-customer-desc-details-location">Vermont</p>
			                    </div><!-- end .sxp-customer-desc-detaisl -->
		                    </div><!-- end .sxp-customer-desc -->
	                    </td>
	                    <td class="sxp-customer-name" data-colname="sxp-customer"><a href="#">Gold</a></td>
	                    <td class="sxp-customer-tag-column" data-colname="sxp-customer-tag">
		                    <div class="sxp-customer-tag-container">
			                    <ul class="sxp-customer-tag-list">
				                    <li><a href="#">Doctor</a></li>
				                    <li><a href="">+5</a></li>
			                    </ul>
		                    </div><!-- end .sxp-customer-compaign-container -->
	                    </td>
	                    <td class="sxp-customer-assigned-column" data-colname="sxp-customer-order">727</td>
	                    <td class="sxp-customer-revenue-column" data-colname="sxp-customer-revenue">$3535.92</td>
	                    <td class="sxp-customer-last-order-column" data-colname="sxp-customer-last-order">23 days ago</td>
                    </tr><!-- end .sxp-customer-list -->
                    <tr id="sxp-customer-list-6" class="sxp-customer-list">
	                    <th scope="row" class="check-column">
		                    <label class="screen-reader-text" for="cb-select-1"></label>
		                    <input id="cb-select-1" type="checkbox" name="post[]" value="1">
	                    </th>
	                    <td class="sxp-customers-column" data-colname="sxp-customer-customers">
		                    <div class="sxp-customer-desc">
			                    <div class="sxp-customer-desc-thumbnail">
				                    <img src="<?php echo esc_url( plugin_dir_url( basename(__DIR__ )). 'SaleXpresso/assets/images/customers/customer6.png' ); ?>" alt="Customer Thumbnail">
			                    </div><!-- end .sxp-customer-desc-thumbnail -->
			                    <div class="sxp-customer-desc-details">
				                    <p class="sxp-customer-desc-details-name">Jane Nguyen</p>
				                    <p class="sxp-customer-desc-details-location">Vermont</p>
			                    </div><!-- end .sxp-customer-desc-detaisl -->
		                    </div><!-- end .sxp-customer-desc -->
	                    </td>
	                    <td class="sxp-customer-name" data-colname="sxp-customer"><a href="#">Gold</a></td>
	                    <td class="sxp-customer-tag-column" data-colname="sxp-customer-tag">
		                    <div class="sxp-customer-tag-container">
			                    <ul class="sxp-customer-tag-list">
				                    <li><a href="#">New Year</a></li>
				                    <li><a href="">+2</a></li>
			                    </ul>
		                    </div><!-- end .sxp-customer-compaign-container -->
	                    </td>
	                    <td class="sxp-customer-assigned-column" data-colname="sxp-customer-order">727</td>
	                    <td class="sxp-customer-revenue-column" data-colname="sxp-customer-revenue">$3535.92</td>
	                    <td class="sxp-customer-last-order-column" data-colname="sxp-customer-last-order">23 days ago</td>
                    </tr><!-- end .sxp-customer-list -->
                    <tr id="sxp-customer-list-7" class="sxp-customer-list">
	                    <th scope="row" class="check-column">
		                    <label class="screen-reader-text" for="cb-select-1"></label>
		                    <input id="cb-select-1" type="checkbox" name="post[]" value="1">
	                    </th>
	                    <td class="sxp-customers-column" data-colname="sxp-customer-customers">
		                    <div class="sxp-customer-desc">
			                    <div class="sxp-customer-desc-thumbnail">
				                    <img src="<?php echo esc_url( plugin_dir_url( basename(__DIR__ )). 'SaleXpresso/assets/images/customers/customer7.png' ); ?>" alt="Customer Thumbnail">
			                    </div><!-- end .sxp-customer-desc-thumbnail -->
			                    <div class="sxp-customer-desc-details">
				                    <p class="sxp-customer-desc-details-name">Jane Nguyen</p>
				                    <p class="sxp-customer-desc-details-location">Vermont</p>
			                    </div><!-- end .sxp-customer-desc-detaisl -->
		                    </div><!-- end .sxp-customer-desc -->
	                    </td>
	                    <td class="sxp-customer-name" data-colname="sxp-customer"><a href="#">Gold</a></td>
	                    <td class="sxp-customer-tag-column" data-colname="sxp-customer-tag">
		                    <div class="sxp-customer-tag-container">
			                    <ul class="sxp-customer-tag-list">
				                    <li><a href="#">New Year</a></li>
				                    <li><a href="">+2</a></li>
			                    </ul>
		                    </div><!-- end .sxp-customer-compaign-container -->
	                    </td>
	                    <td class="sxp-customer-assigned-column" data-colname="sxp-customer-order">727</td>
	                    <td class="sxp-customer-revenue-column" data-colname="sxp-customer-revenue">$3535.92</td>
	                    <td class="sxp-customer-last-order-column" data-colname="sxp-customer-last-order">23 days ago</td>
                    </tr><!-- end .sxp-customer-list -->
                    <tr id="sxp-customer-list-8" class="sxp-customer-list">
	                    <th scope="row" class="check-column">
		                    <label class="screen-reader-text" for="cb-select-1"></label>
		                    <input id="cb-select-1" type="checkbox" name="post[]" value="1">
	                    </th>
	                    <td class="sxp-customers-column" data-colname="sxp-customer-customers">
		                    <div class="sxp-customer-desc">
			                    <div class="sxp-customer-desc-thumbnail">
				                    <img src="<?php echo esc_url( plugin_dir_url( basename(__DIR__ )). 'SaleXpresso/assets/images/customers/customer8.png' ); ?>" alt="Customer Thumbnail">
			                    </div><!-- end .sxp-customer-desc-thumbnail -->
			                    <div class="sxp-customer-desc-details">
				                    <p class="sxp-customer-desc-details-name">Jane Nguyen</p>
				                    <p class="sxp-customer-desc-details-location">Vermont</p>
			                    </div><!-- end .sxp-customer-desc-detaisl -->
		                    </div><!-- end .sxp-customer-desc -->
	                    </td>
	                    <td class="sxp-customer-name" data-colname="sxp-customer"><a href="#">Gold</a></td>
	                    <td class="sxp-customer-tag-column" data-colname="sxp-customer-tag">
		                    <div class="sxp-customer-tag-container">
			                    <ul class="sxp-customer-tag-list">
				                    <li><a href="#">New Year</a></li>
				                    <li><a href="">+2</a></li>
			                    </ul>
		                    </div><!-- end .sxp-customer-compaign-container -->
	                    </td>
	                    <td class="sxp-customer-assigned-column" data-colname="sxp-customer-order">727</td>
	                    <td class="sxp-customer-revenue-column" data-colname="sxp-customer-revenue">$3535.92</td>
	                    <td class="sxp-customer-last-order-column" data-colname="sxp-customer-last-order">23 days ago</td>
                    </tr><!-- end .sxp-customer-list -->
                    <tr id="sxp-customer-list-9" class="sxp-customer-list">
	                    <th scope="row" class="check-column">
		                    <label class="screen-reader-text" for="cb-select-1"></label>
		                    <input id="cb-select-1" type="checkbox" name="post[]" value="1">
	                    </th>
	                    <td class="sxp-customers-column" data-colname="sxp-customer-customers">
		                    <div class="sxp-customer-desc">
			                    <div class="sxp-customer-desc-thumbnail">
				                    <img src="<?php echo esc_url( plugin_dir_url( basename(__DIR__ )). 'SaleXpresso/assets/images/customers/customer9.png' ); ?>" alt="Customer Thumbnail">
			                    </div><!-- end .sxp-customer-desc-thumbnail -->
			                    <div class="sxp-customer-desc-details">
				                    <p class="sxp-customer-desc-details-name">Jane Nguyen</p>
				                    <p class="sxp-customer-desc-details-location">Vermont</p>
			                    </div><!-- end .sxp-customer-desc-detaisl -->
		                    </div><!-- end .sxp-customer-desc -->
	                    </td>
	                    <td class="sxp-customer-name" data-colname="sxp-customer"><a href="#">Gold</a></td>
	                    <td class="sxp-customer-tag-column" data-colname="sxp-customer-tag">
		                    <div class="sxp-customer-tag-container">
			                    <ul class="sxp-customer-tag-list">
				                    <li><a href="#">New Year</a></li>
				                    <li><a href="">+2</a></li>
			                    </ul>
		                    </div><!-- end .sxp-customer-compaign-container -->
	                    </td>
	                    <td class="sxp-customer-assigned-column" data-colname="sxp-customer-order">727</td>
	                    <td class="sxp-customer-revenue-column" data-colname="sxp-customer-revenue">$3535.92</td>
	                    <td class="sxp-customer-last-order-column" data-colname="sxp-customer-last-order">23 days ago</td>
                    </tr><!-- end .sxp-customer-list -->
                    <tr id="sxp-customer-list-10" class="sxp-customer-list">
	                    <th scope="row" class="check-column">
		                    <label class="screen-reader-text" for="cb-select-1"></label>
		                    <input id="cb-select-1" type="checkbox" name="post[]" value="1">
	                    </th>
	                    <td class="sxp-customers-column" data-colname="sxp-customer-customers">
		                    <div class="sxp-customer-desc">
			                    <div class="sxp-customer-desc-thumbnail">
				                    <img src="<?php echo esc_url( plugin_dir_url( basename(__DIR__ )). 'SaleXpresso/assets/images/customers/customer10.png' ); ?>" alt="Customer Thumbnail">
			                    </div><!-- end .sxp-customer-desc-thumbnail -->
			                    <div class="sxp-customer-desc-details">
				                    <p class="sxp-customer-desc-details-name">Jane Nguyen</p>
				                    <p class="sxp-customer-desc-details-location">Vermont</p>
			                    </div><!-- end .sxp-customer-desc-detaisl -->
		                    </div><!-- end .sxp-customer-desc -->
	                    </td>
	                    <td class="sxp-customer-name" data-colname="sxp-customer"><a href="#">Gold</a></td>
	                    <td class="sxp-customer-tag-column" data-colname="sxp-customer-tag">
		                    <div class="sxp-customer-tag-container">
			                    <ul class="sxp-customer-tag-list">
				                    <li><a href="#">New Year</a></li>
				                    <li><a href="">+2</a></li>
			                    </ul>
		                    </div><!-- end .sxp-customer-compaign-container -->
	                    </td>
	                    <td class="sxp-customer-assigned-column" data-colname="sxp-customer-order">727</td>
	                    <td class="sxp-customer-revenue-column" data-colname="sxp-customer-revenue">$3535.92</td>
	                    <td class="sxp-customer-last-order-column" data-colname="sxp-customer-last-order">23 days ago</td>
                    </tr><!-- end .sxp-customer-list -->
                    <tr id="sxp-customer-list-11" class="sxp-customer-list">
	                    <th scope="row" class="check-column">
		                    <label class="screen-reader-text" for="cb-select-1"></label>
		                    <input id="cb-select-1" type="checkbox" name="post[]" value="1">
	                    </th>
	                    <td class="sxp-customers-column" data-colname="sxp-customer-customers">
		                    <div class="sxp-customer-desc">
			                    <div class="sxp-customer-desc-thumbnail">
				                    <img src="<?php echo esc_url( plugin_dir_url( basename(__DIR__ )). 'SaleXpresso/assets/images/customers/customer11.png' ); ?>" alt="Customer Thumbnail">
			                    </div><!-- end .sxp-customer-desc-thumbnail -->
			                    <div class="sxp-customer-desc-details">
				                    <p class="sxp-customer-desc-details-name">Jane Nguyen</p>
				                    <p class="sxp-customer-desc-details-location">Vermont</p>
			                    </div><!-- end .sxp-customer-desc-detaisl -->
		                    </div><!-- end .sxp-customer-desc -->
	                    </td>
	                    <td class="sxp-customer-name" data-colname="sxp-customer"><a href="#">Gold</a></td>
	                    <td class="sxp-customer-tag-column" data-colname="sxp-customer-tag">
		                    <div class="sxp-customer-tag-container">
			                    <ul class="sxp-customer-tag-list">
				                    <li><a href="#">New Year</a></li>
				                    <li><a href="">+2</a></li>
			                    </ul>
		                    </div><!-- end .sxp-customer-compaign-container -->
	                    </td>
	                    <td class="sxp-customer-assigned-column" data-colname="sxp-customer-order">727</td>
	                    <td class="sxp-customer-revenue-column" data-colname="sxp-customer-revenue">$3535.92</td>
	                    <td class="sxp-customer-last-order-column" data-colname="sxp-customer-last-order">23 days ago</td>
                    </tr><!-- end .sxp-customer-list -->
                    <tr id="sxp-customer-list-12" class="sxp-customer-list">
	                    <th scope="row" class="check-column">
		                    <label class="screen-reader-text" for="cb-select-1"></label>
		                    <input id="cb-select-1" type="checkbox" name="post[]" value="1">
	                    </th>
	                    <td class="sxp-customers-column" data-colname="sxp-customer-customers">
		                    <div class="sxp-customer-desc">
			                    <div class="sxp-customer-desc-thumbnail">
				                    <img src="<?php echo esc_url( plugin_dir_url( basename(__DIR__ )). 'SaleXpresso/assets/images/customers/customer12.png' ); ?>" alt="Customer Thumbnail">
			                    </div><!-- end .sxp-customer-desc-thumbnail -->
			                    <div class="sxp-customer-desc-details">
				                    <p class="sxp-customer-desc-details-name">Jane Nguyen</p>
				                    <p class="sxp-customer-desc-details-location">Vermont</p>
			                    </div><!-- end .sxp-customer-desc-detaisl -->
		                    </div><!-- end .sxp-customer-desc -->
	                    </td>
	                    <td class="sxp-customer-name" data-colname="sxp-customer"><a href="#">Gold</a></td>
	                    <td class="sxp-customer-tag-column" data-colname="sxp-customer-tag">
		                    <div class="sxp-customer-tag-container">
			                    <ul class="sxp-customer-tag-list">
				                    <li><a href="#">New Year</a></li>
				                    <li><a href="">+2</a></li>
			                    </ul>
		                    </div><!-- end .sxp-customer-compaign-container -->
	                    </td>
	                    <td class="sxp-customer-assigned-column" data-colname="sxp-customer-order">727</td>
	                    <td class="sxp-customer-revenue-column" data-colname="sxp-customer-revenue">$3535.92</td>
	                    <td class="sxp-customer-last-order-column" data-colname="sxp-customer-last-order">23 days ago</td>
                    </tr><!-- end .sxp-customer-list -->
                    <tr id="sxp-customer-list-13" class="sxp-customer-list">
	                    <th scope="row" class="check-column">
		                    <label class="screen-reader-text" for="cb-select-1"></label>
		                    <input id="cb-select-1" type="checkbox" name="post[]" value="1">
	                    </th>
	                    <td class="sxp-customers-column" data-colname="sxp-customer-customers">
		                    <div class="sxp-customer-desc">
			                    <div class="sxp-customer-desc-thumbnail">
				                    <img src="<?php echo esc_url( plugin_dir_url( basename(__DIR__ )). 'SaleXpresso/assets/images/customers/customer13.png' ); ?>" alt="Customer Thumbnail">
			                    </div><!-- end .sxp-customer-desc-thumbnail -->
			                    <div class="sxp-customer-desc-details">
				                    <p class="sxp-customer-desc-details-name">Jane Nguyen</p>
				                    <p class="sxp-customer-desc-details-location">Vermont</p>
			                    </div><!-- end .sxp-customer-desc-detaisl -->
		                    </div><!-- end .sxp-customer-desc -->
	                    </td>
	                    <td class="sxp-customer-name" data-colname="sxp-customer"><a href="#">Gold</a></td>
	                    <td class="sxp-customer-tag-column" data-colname="sxp-customer-tag">
		                    <div class="sxp-customer-tag-container">
			                    <ul class="sxp-customer-tag-list">
				                    <li><a href="#">New Year</a></li>
				                    <li><a href="">+2</a></li>
			                    </ul>
		                    </div><!-- end .sxp-customer-compaign-container -->
	                    </td>
	                    <td class="sxp-customer-assigned-column" data-colname="sxp-customer-order">727</td>
	                    <td class="sxp-customer-revenue-column" data-colname="sxp-customer-revenue">$3535.92</td>
	                    <td class="sxp-customer-last-order-column" data-colname="sxp-customer-last-order">23 days ago</td>
                    </tr><!-- end .sxp-customer-list -->
                    <tr id="sxp-customer-list-14" class="sxp-customer-list">
	                    <th scope="row" class="check-column">
		                    <label class="screen-reader-text" for="cb-select-1"></label>
		                    <input id="cb-select-1" type="checkbox" name="post[]" value="1">
	                    </th>
	                    <td class="sxp-customers-column" data-colname="sxp-customer-customers">
		                    <div class="sxp-customer-desc">
			                    <div class="sxp-customer-desc-thumbnail">
				                    <img src="<?php echo esc_url( plugin_dir_url( basename(__DIR__ )). 'SaleXpresso/assets/images/customers/customer14.png' ); ?>" alt="Customer Thumbnail">
			                    </div><!-- end .sxp-customer-desc-thumbnail -->
			                    <div class="sxp-customer-desc-details">
				                    <p class="sxp-customer-desc-details-name">Jane Nguyen</p>
				                    <p class="sxp-customer-desc-details-location">Vermont</p>
			                    </div><!-- end .sxp-customer-desc-detaisl -->
		                    </div><!-- end .sxp-customer-desc -->
	                    </td>
	                    <td class="sxp-customer-name" data-colname="sxp-customer"><a href="#">Gold</a></td>
	                    <td class="sxp-customer-tag-column" data-colname="sxp-customer-tag">
		                    <div class="sxp-customer-tag-container">
			                    <ul class="sxp-customer-tag-list">
				                    <li><a href="#">New Year</a></li>
				                    <li><a href="">+2</a></li>
			                    </ul>
		                    </div><!-- end .sxp-customer-compaign-container -->
	                    </td>
	                    <td class="sxp-customer-assigned-column" data-colname="sxp-customer-order">727</td>
	                    <td class="sxp-customer-revenue-column" data-colname="sxp-customer-revenue">$3535.92</td>
	                    <td class="sxp-customer-last-order-column" data-colname="sxp-customer-last-order">23 days ago</td>
                    </tr><!-- end .sxp-customer-list -->
                    <tr id="sxp-customer-list-15" class="sxp-customer-list">
	                    <th scope="row" class="check-column">
		                    <label class="screen-reader-text" for="cb-select-1"></label>
		                    <input id="cb-select-1" type="checkbox" name="post[]" value="1">
	                    </th>
	                    <td class="sxp-customers-column" data-colname="sxp-customer-customers">
		                    <div class="sxp-customer-desc">
			                    <div class="sxp-customer-desc-thumbnail">
				                    <img src="<?php echo esc_url( plugin_dir_url( basename(__DIR__ )). 'SaleXpresso/assets/images/customers/customer15.png' ); ?>" alt="Customer Thumbnail">
			                    </div><!-- end .sxp-customer-desc-thumbnail -->
			                    <div class="sxp-customer-desc-details">
				                    <p class="sxp-customer-desc-details-name">Jane Nguyen</p>
				                    <p class="sxp-customer-desc-details-location">Vermont</p>
			                    </div><!-- end .sxp-customer-desc-detaisl -->
		                    </div><!-- end .sxp-customer-desc -->
	                    </td>
	                    <td class="sxp-customer-name" data-colname="sxp-customer"><a href="#">Gold</a></td>
	                    <td class="sxp-customer-tag-column" data-colname="sxp-customer-tag">
		                    <div class="sxp-customer-tag-container">
			                    <ul class="sxp-customer-tag-list">
				                    <li><a href="#">New Year</a></li>
				                    <li><a href="">+2</a></li>
			                    </ul>
		                    </div><!-- end .sxp-customer-compaign-container -->
	                    </td>
	                    <td class="sxp-customer-assigned-column" data-colname="sxp-customer-order">727</td>
	                    <td class="sxp-customer-revenue-column" data-colname="sxp-customer-revenue">$3535.92</td>
	                    <td class="sxp-customer-last-order-column" data-colname="sxp-customer-last-order">23 days ago</td>
                    </tr><!-- end .sxp-customer-list -->
                    <tr id="sxp-customer-list-16" class="sxp-customer-list">
	                    <th scope="row" class="check-column">
		                    <label class="screen-reader-text" for="cb-select-1"></label>
		                    <input id="cb-select-1" type="checkbox" name="post[]" value="1">
	                    </th>
	                    <td class="sxp-customers-column" data-colname="sxp-customer-customers">
		                    <div class="sxp-customer-desc">
			                    <div class="sxp-customer-desc-thumbnail">
				                    <img src="<?php echo esc_url( plugin_dir_url( basename(__DIR__ )). 'SaleXpresso/assets/images/customers/customer16.png' ); ?>" alt="Customer Thumbnail">
			                    </div><!-- end .sxp-customer-desc-thumbnail -->
			                    <div class="sxp-customer-desc-details">
				                    <p class="sxp-customer-desc-details-name">Jane Nguyen</p>
				                    <p class="sxp-customer-desc-details-location">Vermont</p>
			                    </div><!-- end .sxp-customer-desc-detaisl -->
		                    </div><!-- end .sxp-customer-desc -->
	                    </td>
	                    <td class="sxp-customer-name" data-colname="sxp-customer"><a href="#">Gold</a></td>
	                    <td class="sxp-customer-tag-column" data-colname="sxp-customer-tag">
		                    <div class="sxp-customer-tag-container">
			                    <ul class="sxp-customer-tag-list">
				                    <li><a href="#">New Year</a></li>
				                    <li><a href="">+2</a></li>
			                    </ul>
		                    </div><!-- end .sxp-customer-compaign-container -->
	                    </td>
	                    <td class="sxp-customer-assigned-column" data-colname="sxp-customer-order">727</td>
	                    <td class="sxp-customer-revenue-column" data-colname="sxp-customer-revenue">$3535.92</td>
	                    <td class="sxp-customer-last-order-column" data-colname="sxp-customer-last-order">23 days ago</td>
                    </tr><!-- end .sxp-customer-list -->
                    <tr id="sxp-customer-list-17" class="sxp-customer-list">
	                    <th scope="row" class="check-column">
		                    <label class="screen-reader-text" for="cb-select-1"></label>
		                    <input id="cb-select-1" type="checkbox" name="post[]" value="1">
	                    </th>
	                    <td class="sxp-customers-column" data-colname="sxp-customer-customers">
		                    <div class="sxp-customer-desc">
			                    <div class="sxp-customer-desc-thumbnail">
				                    <img src="<?php echo esc_url( plugin_dir_url( basename(__DIR__ )). 'SaleXpresso/assets/images/customers/customer17.png' ); ?>" alt="Customer Thumbnail">
			                    </div><!-- end .sxp-customer-desc-thumbnail -->
			                    <div class="sxp-customer-desc-details">
				                    <p class="sxp-customer-desc-details-name">Jane Nguyen</p>
				                    <p class="sxp-customer-desc-details-location">Vermont</p>
			                    </div><!-- end .sxp-customer-desc-detaisl -->
		                    </div><!-- end .sxp-customer-desc -->
	                    </td>
	                    <td class="sxp-customer-name" data-colname="sxp-customer"><a href="#">Gold</a></td>
	                    <td class="sxp-customer-tag-column" data-colname="sxp-customer-tag">
		                    <div class="sxp-customer-tag-container">
			                    <ul class="sxp-customer-tag-list">
				                    <li><a href="#">New Year</a></li>
				                    <li><a href="">+2</a></li>
			                    </ul>
		                    </div><!-- end .sxp-customer-compaign-container -->
	                    </td>
	                    <td class="sxp-customer-assigned-column" data-colname="sxp-customer-order">727</td>
	                    <td class="sxp-customer-revenue-column" data-colname="sxp-customer-revenue">$3535.92</td>
	                    <td class="sxp-customer-last-order-column" data-colname="sxp-customer-last-order">23 days ago</td>
                    </tr><!-- end .sxp-customer-list -->
				</tbody>

			</table><!-- end .sxp-customer-table -->

			<div class="sxp-customer-pagination-wrapper">
				<ul class="sxp-pagination">
					<li><a href="#"><img alt="arrow-left.svg" src="data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iMTYiIGhlaWdodD0iMTYiIHZpZXdCb3g9IjAgMCAxNiAxNiIgZmlsbD0ibm9uZSIgeG1sbnM9Imh0dHA6Ly93d3cudzMub3JnLzIwMDAvc3ZnIj4KPHBhdGggZD0iTTE0IDhMMyA4IiBzdHJva2U9IiM3RDdEQjMiIHN0cm9rZS13aWR0aD0iMiIgc3Ryb2tlLWxpbmVjYXA9InJvdW5kIiBzdHJva2UtbGluZWpvaW49InJvdW5kIi8+CjxwYXRoIGQ9Ik02IDEyTDIgOEw2IDQiIHN0cm9rZT0iIzdEN0RCMyIgc3Ryb2tlLXdpZHRoPSIyIiBzdHJva2UtbGluZWNhcD0icm91bmQiIHN0cm9rZS1saW5lam9pbj0icm91bmQiLz4KPC9zdmc+Cg=="/></a></li>
					<li><a href="#">1</a></li>
					<li><a href="#">2</a></li>
					<li><a href="#">3</a></li>
					<li><a href="#">4</a></li>
					<li>...</li>
					<li><a href="#">5</a></li>
					<li><a href="#">6</a></li>
					<li><a href="#">7</a></li>
					<li><a href="#">8</a></li>
					<li><a href="#"><img alt="arrow-right.svg" src="data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iMTYiIGhlaWdodD0iMTYiIHZpZXdCb3g9IjAgMCAxNiAxNiIgZmlsbD0ibm9uZSIgeG1sbnM9Imh0dHA6Ly93d3cudzMub3JnLzIwMDAvc3ZnIj4KPHBhdGggZD0iTTIgOEwxMyA4IiBzdHJva2U9IiM3RDdEQjMiIHN0cm9rZS13aWR0aD0iMiIgc3Ryb2tlLWxpbmVjYXA9InJvdW5kIiBzdHJva2UtbGluZWpvaW49InJvdW5kIi8+CjxwYXRoIGQ9Ik0xMCA0TDE0IDhMMTAgMTIiIHN0cm9rZT0iIzdEN0RCMyIgc3Ryb2tlLXdpZHRoPSIyIiBzdHJva2UtbGluZWNhcD0icm91bmQiIHN0cm9rZS1saW5lam9pbj0icm91bmQiLz4KPC9zdmc+Cg=="/></a></li>
				</ul>
			</div><!-- end .sxp-customer-paginaation-wrapper -->

			<div class="sxp-customer-bottom-wrapper">
				<div class="sxp-customer-selected-container">
					<div class="sxp-customer-row-select">
                        <a href="#" class="sxp-customer-remove-select"><img src="data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iMzYiIGhlaWdodD0iMzYiIHZpZXdCb3g9IjAgMCAzNiAzNiIgZmlsbD0ibm9uZSIgeG1sbnM9Imh0dHA6Ly93d3cudzMub3JnLzIwMDAvc3ZnIj4KPHBhdGggZD0iTTIyIDE0TDE0IDIyIiBzdHJva2U9IiM3RDdEQjMiIHN0cm9rZS13aWR0aD0iMiIgc3Ryb2tlLWxpbmVjYXA9InJvdW5kIiBzdHJva2UtbGluZWpvaW49InJvdW5kIi8+CjxwYXRoIGQ9Ik0xNCAxNEwyMiAyMiIgc3Ryb2tlPSIjN0Q3REIzIiBzdHJva2Utd2lkdGg9IjIiIHN0cm9rZS1saW5lY2FwPSJyb3VuZCIgc3Ryb2tlLWxpbmVqb2luPSJyb3VuZCIvPgo8L3N2Zz4K" alt="Remove selection"></a>
						<a href="#" class="sxp-customer-selected">2 Rows Selected</a>
					</div>
					<div class="sxp-customer-remove-customer">
						<a href="#">Delete</a>
					</div>
				</div><!-- end .sxp-customer-selected-container -->
			</div><!-- end .sxp-customer-bottom-wrapper -->

		</div><!-- end .sxp-customer-list-wrapper -->
		<?php
	}
	
}

// End of file class-sxp-customer-list-table.php.
