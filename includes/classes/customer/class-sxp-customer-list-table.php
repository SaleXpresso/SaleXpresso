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
					<label for="sxp-customer-search" class="screen-reader-text"><?php __('Search Customer', 'salexpresso'); ?></label>
					<input type="text" id="sxp-customer-search" placeholder="Search Customers">
				</div><!-- end .sxp-customer-search -->
				<div class="sxp-customer-btn-wrapper">
					<a href="#" class="sxp-customer-type-btn sxp-btn sxp-btn-default"><i data-feather="plus"></i> Customer Type Rules</a>
					<a href="#" class="sxp-customer-add-btn sxp-btn sxp-btn-primary"><i data-feather="plus"></i> Add New Customer</a>
				</div>
			</div><!-- end .sxp-customer-top-wrapper -->
			<table class="wp-list-table widefat sxp-table sxp-customer-table">
				<thead>
					<tr>
						<td id="cb" class="manage-column column-cb check-column">
							<label class="screen-reader-text" for="cb-select-all-1">Select All</label>
							<input id="cb-select-all-1" type="checkbox">
						</td>
						<th scope="col" id="sxp-customer-customers" class="manage-column column-categories"><a href="#">Customers</a></th>
						<th scope="col" id="sxp-customer" class="manage-column column-title column-primary sortable desc"><a href="#">Customer Type</a></th>
						<th scope="col" id="sxp-customer-tag" class="manage-column column-author"><a href="#">Customer Tag</a></th>
						<th scope="col" id="sxp-customer-order" class="manage-column column-categories"><a href="#">Orders</a></th>
						<th scope="col" id="sxp-customer-revenue" class="manage-column column-categories"><a href="#">Revenue</a></th>
						<th scope="col" id="sxp-customer-last-order" class="manage-column column-categories"><a href="#">Last Order</a></th>
					</tr>
				</thead>

				<tbody id="the-list">
					<tr id="sxp-customer-list-1" class="sxp-customer-list">
						<th scope="row" class="check-column">
							<label class="screen-reader-text" for="cb-select-1"></label>
							<input id="cb-select-1" type="checkbox" name="post[]" value="1">
						</th>
						<td class="title column-title has-row-actions column-primary page-title sxp-customers-column" data-colname="sxp-customer-customers">
							<div class="sxp-customer-desc">
								<div class="sxp-customer-desc-thumbnail">
									<img src="<?php echo esc_url( plugin_dir_url( basename(__DIR__ )) . 'SaleXpresso/assets/images/customers/customer1.png' ); ?>" alt="Customer Thumbnail">
								</div><!-- end .sxp-customer-desc-thumbnail -->
								<div class="sxp-customer-desc-details">
									<p class="sxp-customer-desc-details-name">Wendy Bell</p>
									<p class="sxp-customer-desc-details-location">Vermont</p>
								</div><!-- end .sxp-customer-desc-detaisl -->
							</div><!-- end .sxp-customer-desc -->
							<button type="button" class="toggle-row"><span class="screen-reader-text">Show more details</span></button>
						</td>
						<td class="sxp-customer-name" data-colname="Customer Type"><a href="#" style="background: #FFD0D0">VIP</a></td>
						<td class="sxp-customer-tag-column" data-colname="Customer Tag">
							<div class="sxp-customer-tag-container">
								<ul class="sxp-tag-list">
									<li><a href="#">Holiday Campaign</a></li>
									<li><a href="">+2</a></li>
								</ul>
							</div><!-- end .sxp-customer-compaign-container -->
						</td>
						<td class="sxp-customer-assigned-column" data-colname="Customer Order">799</td>
						<td class="sxp-customer-revenue-column" data-colname="Customer Revenue">$6910.60</td>
						<td class="sxp-customer-last-order-column" data-colname="Last Order">23 days ago</td>
					</tr><!-- end .sxp-customer-list -->
					<tr id="sxp-customer-list-2" class="sxp-customer-list">
						<th scope="row" class="check-column">
							<label class="screen-reader-text" for="cb-select-1"></label>
							<input id="cb-select-1" type="checkbox" name="post[]" value="1">
						</th>
						<td class="title column-title has-row-actions column-primary page-title sxp-customers-column" data-colname="sxp-customer-customers">
							<div class="sxp-customer-desc">
								<div class="sxp-customer-desc-thumbnail">
									<img src="<?php echo esc_url( plugin_dir_url( basename(__DIR__ )) . 'SaleXpresso/assets/images/customers/customer2.png' ); ?>" alt="Customer Thumbnail">
								</div><!-- end .sxp-customer-desc-thumbnail -->
								<div class="sxp-customer-desc-details">
									<p class="sxp-customer-desc-details-name">Jane Nguyen</p>
									<p class="sxp-customer-desc-details-location">Vermont</p>
								</div><!-- end .sxp-customer-desc-detaisl -->
							</div><!-- end .sxp-customer-desc -->
							<button type="button" class="toggle-row"><span class="screen-reader-text">Show more details</span></button>
						</td>
						<td class="sxp-customer-name" data-colname="Customer Type"><a href="#" style="background: #E3FFDA">Gold</a></td>
						<td class="sxp-customer-tag-column" data-colname="Customer Tag">
							<div class="sxp-customer-tag-container">
								<ul class="sxp-tag-list">
									<li><a href="#">New Year</a></li>
									<li><a href="">+2</a></li>
								</ul>
							</div><!-- end .sxp-customer-compaign-container -->
						</td>
						<td class="sxp-customer-assigned-column" data-colname="Customer Order">727</td>
						<td class="sxp-customer-revenue-column" data-colname="Customer Revenue">$3535.92</td>
						<td class="sxp-customer-last-order-column" data-colname="Last Order">23 days ago</td>
					</tr><!-- end .sxp-customer-list -->
					<tr id="sxp-customer-list-3" class="sxp-customer-list">
						<th scope="row" class="check-column">
							<label class="screen-reader-text" for="cb-select-1"></label>
							<input id="cb-select-1" type="checkbox" name="post[]" value="1">
						</th>
						<td class="title column-title has-row-actions column-primary page-title sxp-customers-column" data-colname="sxp-customer-customers">
							<div class="sxp-customer-desc">
								<div class="sxp-customer-desc-thumbnail">
									<img src="<?php echo esc_url( plugin_dir_url( basename(__DIR__ )) . 'SaleXpresso/assets/images/customers/customer3.png' ); ?>" alt="Customer Thumbnail">
								</div><!-- end .sxp-customer-desc-thumbnail -->
								<div class="sxp-customer-desc-details">
									<p class="sxp-customer-desc-details-name">Jane Nguyen</p>
									<p class="sxp-customer-desc-details-location">Vermont</p>
								</div><!-- end .sxp-customer-desc-detaisl -->
							</div><!-- end .sxp-customer-desc -->
							<button type="button" class="toggle-row"><span class="screen-reader-text">Show more details</span></button>
						</td>
						<td class="sxp-customer-name" data-colname="Customer Type"><a href="#" style="background: #FFCFB5">Gold</a></td>
						<td class="sxp-customer-tag-column" data-colname="Customer Tag">
							<div class="sxp-customer-tag-container">
								<ul class="sxp-tag-list">
									<li><a href="#">Sports Lover</a></li>
									<li><a href="">+4</a></li>
								</ul>
							</div><!-- end .sxp-customer-compaign-container -->
						</td>
						<td class="sxp-customer-assigned-column" data-colname="Customer Order">727</td>
						<td class="sxp-customer-revenue-column" data-colname="Customer Revenue">$3535.92</td>
						<td class="sxp-customer-last-order-column" data-colname="Last Order">23 days ago</td>
					</tr><!-- end .sxp-customer-list -->
					<tr id="sxp-customer-list-4" class="sxp-customer-list">
						<th scope="row" class="check-column">
							<label class="screen-reader-text" for="cb-select-1"></label>
							<input id="cb-select-1" type="checkbox" name="post[]" value="1">
						</th>
						<td class="title column-title has-row-actions column-primary page-title sxp-customers-column" data-colname="sxp-customer-customers">
							<div class="sxp-customer-desc">
								<div class="sxp-customer-desc-thumbnail">
									<img src="<?php echo esc_url( plugin_dir_url( basename(__DIR__ )) . 'SaleXpresso/assets/images/customers/customer4.png' ); ?>" alt="Customer Thumbnail">
								</div><!-- end .sxp-customer-desc-thumbnail -->
								<div class="sxp-customer-desc-details">
									<p class="sxp-customer-desc-details-name">Jane Nguyen</p>
									<p class="sxp-customer-desc-details-location">Vermont</p>
								</div><!-- end .sxp-customer-desc-detaisl -->
							</div><!-- end .sxp-customer-desc -->
							<button type="button" class="toggle-row"><span class="screen-reader-text">Show more details</span></button>
						</td>
						<td class="sxp-customer-name" data-colname="Customer Type"><a href="#" style="background:  #FFCFB5">Gold</a></td>
						<td class="sxp-customer-tag-column" data-colname="Customer Tag">
							<div class="sxp-customer-tag-container">
								<ul class="sxp-tag-list">
									<li><a href="#">Birthday</a></li>
									<li><a href="">+2</a></li>
								</ul>
							</div><!-- end .sxp-customer-compaign-container -->
						</td>
						<td class="sxp-customer-assigned-column" data-colname="Customer Order">727</td>
						<td class="sxp-customer-revenue-column" data-colname="Customer Revenue">$3535.92</td>
						<td class="sxp-customer-last-order-column" data-colname="Last Order">23 days ago</td>
					</tr><!-- end .sxp-customer-list -->
					<tr id="sxp-customer-list-5" class="sxp-customer-list">
						<th scope="row" class="check-column">
							<label class="screen-reader-text" for="cb-select-1"></label>
							<input id="cb-select-1" type="checkbox" name="post[]" value="1">
						</th>
						<td class="title column-title has-row-actions column-primary page-title sxp-customers-column" data-colname="sxp-customer-customers">
							<div class="sxp-customer-desc">
								<div class="sxp-customer-desc-thumbnail">
									<img src="<?php echo esc_url( plugin_dir_url( basename(__DIR__ )) . 'SaleXpresso/assets/images/customers/customer5.png' ); ?>" alt="Customer Thumbnail">
								</div><!-- end .sxp-customer-desc-thumbnail -->
								<div class="sxp-customer-desc-details">
									<p class="sxp-customer-desc-details-name">Jane Nguyen</p>
									<p class="sxp-customer-desc-details-location">Vermont</p>
								</div><!-- end .sxp-customer-desc-detaisl -->
							</div><!-- end .sxp-customer-desc -->
							<button type="button" class="toggle-row"><span class="screen-reader-text">Show more details</span></button>
						</td>
						<td class="sxp-customer-name" data-colname="Customer Type"><a href="#" style="background: #DAE4FF">Gold</a></td>
						<td class="sxp-customer-tag-column" data-colname="Customer Tag">
							<div class="sxp-customer-tag-container">
								<ul class="sxp-tag-list">
									<li><a href="#">Doctor</a></li>
									<li><a href="">+5</a></li>
								</ul>
							</div><!-- end .sxp-customer-compaign-container -->
						</td>
						<td class="sxp-customer-assigned-column" data-colname="Customer Order">727</td>
						<td class="sxp-customer-revenue-column" data-colname="Customer Revenue">$3535.92</td>
						<td class="sxp-customer-last-order-column" data-colname="Last Order">23 days ago</td>
					</tr><!-- end .sxp-customer-list -->
					<tr id="sxp-customer-list-6" class="sxp-customer-list">
						<th scope="row" class="check-column">
							<label class="screen-reader-text" for="cb-select-1"></label>
							<input id="cb-select-1" type="checkbox" name="post[]" value="1">
						</th>
						<td class="title column-title has-row-actions column-primary page-title sxp-customers-column" data-colname="sxp-customer-customers">
							<div class="sxp-customer-desc">
								<div class="sxp-customer-desc-thumbnail">
									<img src="<?php echo esc_url( plugin_dir_url( basename(__DIR__ )) . 'SaleXpresso/assets/images/customers/customer6.png' ); ?>" alt="Customer Thumbnail">
								</div><!-- end .sxp-customer-desc-thumbnail -->
								<div class="sxp-customer-desc-details">
									<p class="sxp-customer-desc-details-name">Jane Nguyen</p>
									<p class="sxp-customer-desc-details-location">Vermont</p>
								</div><!-- end .sxp-customer-desc-detaisl -->
							</div><!-- end .sxp-customer-desc -->
							<button type="button" class="toggle-row"><span class="screen-reader-text">Show more details</span></button>
						</td>
						<td class="sxp-customer-name" data-colname="Customer Type"><a href="#" style="background: #CFFFF4">Gold</a></td>
						<td class="sxp-customer-tag-column" data-colname="Customer Tag">
							<div class="sxp-customer-tag-container">
								<ul class="sxp-tag-list">
									<li><a href="#">New Year</a></li>
									<li><a href="">+2</a></li>
								</ul>
							</div><!-- end .sxp-customer-compaign-container -->
						</td>
						<td class="sxp-customer-assigned-column" data-colname="Customer Order">727</td>
						<td class="sxp-customer-revenue-column" data-colname="Customer Revenue">$3535.92</td>
						<td class="sxp-customer-last-order-column" data-colname="Last Order">23 days ago</td>
					</tr><!-- end .sxp-customer-list -->

				</tbody>

			</table><!-- end .sxp-customer-table -->

			<div class="sxp-pagination-wrapper">
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
			</div><!-- end .sxp-paginaation-wrapper -->

			<div class="sxp-bottom-wrapper">
				<div class="sxp-selected-container">
					<div class="sxp-row-select">
						<a href="#" class="sxp-remove-select"><img src="data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iMzYiIGhlaWdodD0iMzYiIHZpZXdCb3g9IjAgMCAzNiAzNiIgZmlsbD0ibm9uZSIgeG1sbnM9Imh0dHA6Ly93d3cudzMub3JnLzIwMDAvc3ZnIj4KPHBhdGggZD0iTTIyIDE0TDE0IDIyIiBzdHJva2U9IiM3RDdEQjMiIHN0cm9rZS13aWR0aD0iMiIgc3Ryb2tlLWxpbmVjYXA9InJvdW5kIiBzdHJva2UtbGluZWpvaW49InJvdW5kIi8+CjxwYXRoIGQ9Ik0xNCAxNEwyMiAyMiIgc3Ryb2tlPSIjN0Q3REIzIiBzdHJva2Utd2lkdGg9IjIiIHN0cm9rZS1saW5lY2FwPSJyb3VuZCIgc3Ryb2tlLWxpbmVqb2luPSJyb3VuZCIvPgo8L3N2Zz4K" alt="Remove selection"></a>
						<a href="#" class="sxp-selected">2 Rows Selected</a>
					</div>
					<div class="sxp-remove-customer">
						<a href="#">Delete</a>
					</div>
				</div><!-- end .sxp-selected-container -->
			</div><!-- end .sxp-bottom-wrapper -->

		</div><!-- end .sxp-customer-list-wrapper -->
		<?php
	}
	
}

// End of file class-sxp-customer-list-table.php.
