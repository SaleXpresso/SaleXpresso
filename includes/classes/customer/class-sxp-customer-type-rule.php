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
 * Class SXP_Customer_Type_Rule
 *
 * @package SaleXpresso\Customer
 */
class SXP_Customer_Type_Rule {
	/**
	 * SXP_Customer_Type_Rule constructor.
	 */
	public function __construct() {
		?>
			<div class="sxp-customer-rule-wrapper">
				<div class="sxp-customer-name">
					<h4 class="sxp-customer-section-header">Customer Type Name</h4>
					<div class="sxp-customer-section-title-container">
						Distributor
					</div>
				</div><!-- end .sxp-customer-name -->
				<div class="sxp-customer-rule-container">
					<h4 class="sxp-customer-section-header">Create Rule</h4>

					<div class="sxp-customer-rules-wrapper">
						<div class="sxp-customer-rules">
							<div class="sxp-customer-single-rule">
								<select class="sxp-custom-select">
									<option value="purchase-ammount">Purchase Amount</option>
									<option value="purchase-quantity">Purchase Quantity</option>
									<option value="recurring-purchase">Recurring Purchase</option>
									<option value="customer-life-time-value">Customer's Life time value</option>
									<option value="coupon-code">Coupon code</option>
									<option value="product-bought">Product Bought</option>
									<option value="tagged-width">Tagged width</option>
								</select>
							</div><!-- end .sxp-customer-single-rule -->

							<div class="sxp-customer-single-rule">
								<select class="sxp-custom-select">
									<option value="purchase-ammount" selected>Purchase Amount</option>
									<option value="purchase-quantity">Purchase Quantity</option>
									<option value="recurring-purchase">Recurring Purchase</option>
									<option value="customer-life-time-value">Customer's Life time value</option>
									<option value="coupon-code">Coupon code</option>
									<option value="product-bought">Product Bought</option>
									<option value="tagged-width">Tagged width</option>
								</select>
								<select class="sxp-custom-select">
									<option value="equal">=</option>
									<option value="not-equal">!=</option>
									<option value="greater-than">></option>
									<option value="less-than"><</option>
									<option value="greater-than-or-equal">>=</option>
									<option value="less-than-or-equal"><=</option>
								</select>
								<input type="number" value="5000" class="sxp-custom-number">
							</div><!-- end .sxp-customer-single-rule -->

							<div class="sxp-customer-single-rule">
								<select class="sxp-custom-select">
									<option value="purchase-ammount">Purchase Amount</option>
									<option value="purchase-quantity" selected>Purchase Quantity</option>
									<option value="recurring-purchase">Recurring Purchase</option>
									<option value="customer-life-time-value">Customer's Life time value</option>
									<option value="coupon-code">Coupon code</option>
									<option value="product-bought">Product Bought</option>
									<option value="tagged-width">Tagged width</option>
								</select>
								<select class="sxp-custom-select">
									<option value="min-max">min-max</option>
								</select>
								<div class="sxp-form-group">
									<label for="min-quantity">min</label>
									<input type="number" name="min-quantity" id="min-quantity" value="50">
								</div><!-- sxp-form-group -->
								<div class="sxp-form-group">
									<label for="max-quantity">max</label>
									<input type="number" name="max-quantity" id="max-quantity" value="2000">
								</div><!-- sxp-form-group -->
							</div><!-- end .sxp-customer-single-rule -->

							<div class="sxp-customer-single-rule">
								<select class="sxp-custom-select">
									<option value="purchase-ammount">Purchase Amount</option>
									<option value="purchase-quantity">Purchase Quantity</option>
									<option value="recurring-purchase" selected>Recurring Purchase</option>
									<option value="customer-life-time-value">Customer's Life time value</option>
									<option value="coupon-code">Coupon code</option>
									<option value="product-bought">Product Bought</option>
									<option value="tagged-width">Tagged width</option>
								</select>
								<select class="sxp-custom-select">
									<option value="equal">=</option>
									<option value="not-equal">!=</option>
									<option value="greater-than">></option>
									<option value="less-than"><</option>
									<option value="greater-than-or-equal">>=</option>
									<option value="less-than-or-equal"><=</option>
								</select>
								<input type="number" value="600" class="sxp-custom-number">
							</div><!-- end .sxp-customer-single-rule -->
							<div class="sxp-customer-single-rule">
								<select class="sxp-custom-select">
									<option value="purchase-ammount">Purchase Amount</option>
									<option value="purchase-quantity">Purchase Quantity</option>
									<option value="recurring-purchase">Recurring Purchase</option>
									<option value="customer-life-time-value" selected>Customer's Life time value</option>
									<option value="coupon-code">Coupon code</option>
									<option value="product-bought">Product Bought</option>
									<option value="tagged-width">Tagged width</option>
								</select>
							</div><!-- end .sxp-customer-single-rule -->

							<div class="sxp-customer-single-rule">
								<select class="sxp-custom-select">
									<option value="purchase-ammount">Purchase Amount</option>
									<option value="purchase-quantity">Purchase Quantity</option>
									<option value="recurring-purchase">Recurring Purchase</option>
									<option value="customer-life-time-value">Customer's Life time value</option>
									<option value="coupon-code" selected>Coupon code</option>
									<option value="product-bought">Product Bought</option>
									<option value="tagged-width">Tagged width</option>
								</select>
								<select class="sxp-custom-select">
									<Option class="">JIGCL586</Option>
									<Option class="">"HEHGK98"</Option>
									<Option class="">9798KJLJ</Option>
									<Option class="">JIGCL586</Option>
								</select>
							</div><!-- end .sxp-customer-single-rule -->

							<div class="sxp-customer-single-rule">
								<select class="sxp-custom-select">
									<option value="purchase-ammount">Purchase Amount</option>
									<option value="purchase-quantity">Purchase Quantity</option>
									<option value="recurring-purchase">Recurring Purchase</option>
									<option value="customer-life-time-value">Customer's Life time value</option>
									<option value="coupon-code">Coupon code</option>
									<option value="product-bought" selected>Product Bought</option>
									<option value="tagged-width">Tagged width</option>
								</select>
							</div><!-- end .sxp-customer-single-rule -->

							<div class="sxp-customer-single-rule">
								<select class="sxp-custom-select">
									<option value="purchase-ammount">Purchase Amount</option>
									<option value="purchase-quantity">Purchase Quantity</option>
									<option value="recurring-purchase">Recurring Purchase</option>
									<option value="customer-life-time-value">Customer's Life time value</option>
									<option value="coupon-code">Coupon code</option>
									<option value="product-bought">Product Bought</option>
									<option value="tagged-width" selected>Tagged width</option>
								</select>
								<select class="sxp-custom-select">
									<option value="equal">=</option>
									<option value="not-equal">!=</option>
									<option value="greater-than">></option>
									<option value="less-than"><</option>
									<option value="greater-than-or-equal">>=</option>
									<option value="less-than-or-equal"><=</option>
								</select>
								<select class="sxp-custom-select">
									<option value="no-tag">No tags</option>
									<option value="no-tag">tag 1</option>
									<option value="no-tag">tag 2</option>
								</select>
							</div><!-- end .sxp-customer-single-rule -->
						</div><!-- end .sxp-customer-rules -->
					</div><!-- end .sxp-customer-rules-wrapper -->
				</div><!-- end .sxp-customer-rule-container -->
				<div class="sxp-customer-rule-botttom">
					<div class="sxp-customer-rule-add-btn">
						<a href="#">Add Condition</a>
					</div><!-- end .sxp-customer-rule-add-btn -->
					<div class="sxp-customer-rule-save-wrapper">
						<a class="sxp-customer-rule-cancel-btn" href="#">Cancel</a>
						<a class="sxp-btn sxp-btn-muted sxp-customer-rule-save-btn" href="#">Save New Customer Type</a>
					</div><!-- end .sxp-customer-rule-save-wrapper -->
				</div><!-- end .sxp-customer-rule-bottom -->
			</div><!-- end .sxp-customer-rule-wrapper -->
		<?php
	}
}
