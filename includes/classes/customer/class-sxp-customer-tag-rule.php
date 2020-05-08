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
 * Class SXP_Customer_Tag_Rule
 *
 * @package SaleXpresso
 */
class SXP_Customer_Tag_Rule {

	/**
	 * SXP_Customer_Tag_Rule constructor.
	 */
	public function __construct() {
		// @TODO Extend WP_List_Table.
		?>
			<div class="sxp-rule-wrapper">
			<div class="sxp-rule-name">
				<h4 class="sxp-rule-section-header">Customer Type Name <i data-feather="info"></i></h4>
				<div class="sxp-rule-section-title-container">
					Distributor
				</div>
			</div><!-- end .sxp-rule-name -->
			<div class="sxp-rule-container">
				<h4 class="sxp-rule-section-header">Create Rule <i data-feather="info"></i></h4>
				<div class="sxp-rules-wrapper">
					<div class="sxp-rules">
						<div class="sxp-rule-single">
							<label for="sxp-rule-select" class="screen-reader-text"><?php __('Select Rule', 'salexpresso'); ?> </label>
							<select class="sxp-custom-select" id="sxp-rule-select">
								<option value="purchase-amount">Purchase Amount</option>
								<option value="purchase-quantity">Purchase Quantity</option>
								<option value="recurring-purchase">Recurring Purchase</option>
								<option value="customer-life-time-value">Customer's Life time value</option>
								<option value="coupon-code">Coupon code</option>
								<option value="product-bought">Product Bought</option>
								<option value="tagged-width">Tagged width</option>
							</select>
						</div><!-- end .sxp-rule-single -->
						<div class="sxp-rule-single">
							<label for="sxp-rule-select" class="screen-reader-text"><?php __('Select Rule', 'salexpresso'); ?> </label>
							<select class="sxp-custom-select" id="sxp-rule-select">
								<option value="purchase-amount" selected>Purchase Amount</option>
								<option value="purchase-quantity">Purchase Quantity</option>
								<option value="recurring-purchase">Recurring Purchase</option>
								<option value="customer-life-time-value">Customer's Life time value</option>
								<option value="coupon-code">Coupon code</option>
								<option value="product-bought">Product Bought</option>
								<option value="tagged-width">Tagged width</option>
							</select>
							<label for="sxp-customer-pac" class="screen-reader-text"><?php __('Purchase Amount Comparison', 'salexpresso'); ?> </label>
							<select class="sxp-custom-select" id="sxp-customer-pac">
								<option value="equal">=</option>
								<option value="not-equal">!=</option>
								<option value="greater-than">></option>
								<option value="less-than"><</option>
								<option value="greater-than-or-equal">>=</option>
								<option value="less-than-or-equal"><=</option>
							</select>
							<label for="sxp-customer-pav" class="screen-reader-text"><?php __('Select Purchase Amount Value', 'salexpresso'); ?> </label>
							<input type="number" value="5000" class="sxp-custom-number" id="sxp-customer-pav">
						</div><!-- end .sxp-rule-single -->
						<div class="sxp-rule-single">
							<label for="sxp-rule-select" class="screen-reader-text"><?php __('Select Rule', 'salexpresso'); ?> </label>
							<select class="sxp-custom-select" id="sxp-rule-select">
								<option value="purchase-amount">Purchase Amount</option>
								<option value="purchase-quantity" selected>Purchase Quantity</option>
								<option value="recurring-purchase">Recurring Purchase</option>
								<option value="customer-life-time-value">Customer's Life time value</option>
								<option value="coupon-code">Coupon code</option>
								<option value="product-bought">Product Bought</option>
								<option value="tagged-width">Tagged width</option>
							</select>
							<label for="sxp-customer-pqr" class="screen-reader-text"><?php __('Select minimux maximum value', 'salexpresso'); ?> </label>
							<select class="sxp-custom-select" id="sxp-customer-pqr">
								<option value="min-max">min-max</option>
								<option value="max-min">max-min</option>
							</select>
							<div class="sxp-form-group">
								<label for="sxp-customer-pqmiv"><?php __('Select minimum value', 'salexpresso'); ?></label>
								<input type="number" name="sxp-purchase-quantity-min-value" id="sxp-customer-pqmiv" value="50">
							</div><!-- sxp-form-group -->
							<div class="sxp-form-group">
								<label for="sxp-customer-pqmav"><?php __('Select maximum value', 'salexpresso'); ?></label>
								<input type="number" name="sxp-purchase-quantity-min-value" id="sxp-customer-pqmav" value="2000">
							</div><!-- sxp-form-group -->
						</div><!-- end .sxp-rule-single -->
						<div class="sxp-rule-single">
							<label for="sxp-rule-select" class="screen-reader-text"><?php __('Select Rule', 'salexpresso'); ?> </label>
							<select class="sxp-custom-select" id="sxp-rule-select">
								<option value="purchase-amount">Purchase Amount</option>
								<option value="purchase-quantity">Purchase Quantity</option>
								<option value="recurring-purchase" selected>Recurring Purchase</option>
								<option value="customer-life-time-value">Customer's Life time value</option>
								<option value="coupon-code">Coupon code</option>
								<option value="product-bought">Product Bought</option>
								<option value="tagged-width">Tagged width</option>
							</select>
							<label for="sxp-customer-rpc" class="screen-reader-text"><?php __('Recurring Purchase', 'salexpresso'); ?> </label>
							<select class="sxp-custom-select" id="sxp-customer-rpc">
								<option value="equal">=</option>
								<option value="not-equal">!=</option>
								<option value="greater-than">></option>
								<option value="less-than"><</option>
								<option value="greater-than-or-equal">>=</option>
								<option value="less-than-or-equal"><=</option>
							</select>
							<label for="sxp-customer-rpv" class="screen-reader-text"><?php __('Select Recurring Purchase', 'salexpresso'); ?> </label>
							<input type="number" value="600" class="sxp-custom-number" id="sxp-customer-rpv">
						</div><!-- end .sxp-rule-single -->
						<div class="sxp-rule-single">
							<label for="sxp-rule-select" class="screen-reader-text"><?php __('Select Rule', 'salexpresso'); ?> </label>
							<select class="sxp-custom-select" id="sxp-rule-select">
								<option value="purchase-amount">Purchase Amount</option>
								<option value="purchase-quantity">Purchase Quantity</option>
								<option value="recurring-purchase">Recurring Purchase</option>
								<option value="customer-life-time-value" selected>Customer's Life time value</option>
								<option value="coupon-code">Coupon code</option>
								<option value="product-bought">Product Bought</option>
								<option value="tagged-width">Tagged width</option>
							</select>
						</div><!-- end .sxp-rule-single -->
						<div class="sxp-rule-single">
							<label for="sxp-rule-select" class="screen-reader-text"><?php __('Select Rule', 'salexpresso'); ?> </label>
							<select class="sxp-custom-select" id="sxp-rule-select">
								<option value="purchase-amount">Purchase Amount</option>
								<option value="purchase-quantity">Purchase Quantity</option>
								<option value="recurring-purchase">Recurring Purchase</option>
								<option value="customer-life-time-value">Customer's Life time value</option>
								<option value="coupon-code" selected>Coupon code</option>
								<option value="product-bought">Product Bought</option>
								<option value="tagged-width">Tagged width</option>
							</select>
							<label for="sxp-customer-ccv" class="screen-reader-text"><?php __('Coupon Code Value', 'salexpresso'); ?> </label>
							<select class="sxp-custom-select" id="sxp-customer-ccv">
								<Option class="">JIGCL586</Option>
								<Option class="">"HEHGK98"</Option>
								<Option class="">9798KJLJ</Option>
								<Option class="">JIGCL586</Option>
							</select>
						</div><!-- end .sxp-rule-single -->
						<div class="sxp-rule-single">
							<label for="sxp-rule-select" class="screen-reader-text"><?php __('Select Rule', 'salexpresso'); ?> </label>
							<select class="sxp-custom-select" id="sxp-rule-select">
								<option value="purchase-amount">Purchase Amount</option>
								<option value="purchase-quantity">Purchase Quantity</option>
								<option value="recurring-purchase">Recurring Purchase</option>
								<option value="customer-life-time-value">Customer's Life time value</option>
								<option value="coupon-code">Coupon code</option>
								<option value="product-bought" selected>Product Bought</option>
								<option value="tagged-width">Tagged width</option>
							</select>
						</div><!-- end .sxp-rule-single -->
						<div class="sxp-rule-single">
							<label for="sxp-rule-select" class="screen-reader-text"><?php __('Select Rule', 'salexpresso'); ?> </label>
							<select class="sxp-custom-select" id="sxp-rule-select">
								<option value="purchase-amount">Purchase Amount</option>
								<option value="purchase-quantity">Purchase Quantity</option>
								<option value="recurring-purchase">Recurring Purchase</option>
								<option value="customer-life-time-value">Customer's Life time value</option>
								<option value="coupon-code">Coupon code</option>
								<option value="product-bought">Product Bought</option>
								<option value="tagged-width" selected>Tagged width</option>
							</select>
							<label for="sxp-customer-twc" class="screen-reader-text"><?php __('Tagged width comparison', 'salexpresso'); ?> </label>
							<select class="sxp-custom-select"  id="sxp-customer-twc">
								<option value="equal">=</option>
								<option value="not-equal">!=</option>
								<option value="greater-than">></option>
								<option value="less-than"><</option>
								<option value="greater-than-or-equal">>=</option>
								<option value="less-than-or-equal"><=</option>
							</select>
							<label for="sxp-customer-twv" class="screen-reader-text"><?php __('Tagged width value', 'salexpresso'); ?> </label>
							<select class="sxp-custom-select" id="sxp-customer-twv">
								<option value="no-tag">No tags</option>
								<option value="no-tag">tag 1</option>
								<option value="no-tag">tag 2</option>
							</select>
						</div><!-- end .sxp-rule-single -->
					</div><!-- end .sxp-rules -->
				</div><!-- end .sxp-rules-wrapper -->
			</div><!-- end .sxp-rule-container -->
			<div class="sxp-rule-bottom">
				<div class="sxp-rule-add-btn sxp-btn-link">
					<a href="#" class="sxp-btn sxp-btn-link"><i data-feather="plus"></i> Add Condition</a>
				</div><!-- end .sxp-customer-rule-add-btn -->
				<div class="sxp-rule-save-wrapper">
					<a class="sxp-btn-cancel" href="#">Cancel</a>
					<a class="sxp-btn sxp-btn-primary sxp-rule-save-btn" href="#">Save New Customer Type</a>
				</div><!-- end .sxp-customer-rule-save-wrapper -->
			</div><!-- end .sxp-customer-rule-bottom -->
		</div><!-- end .sxp-rule-wrapper -->
		<?php
	}
}
