/**!
 * SaleXpresso Public Scripts
 *
 * @author SaleXpresso <support@salexpresso.com>
 * @package SaleXpresso
 * @version 1.0.0
 * @since 1.0.0
 */
import _ from 'lodash';
( function( $, window, document, SaleXpresso ) {
	"use strict";
	
	// Helper Functions.
	
	/**
	 * Checks if input is valid email.
	 *
	 * @param email
	 * @return {boolean}
	 */
	const isEmail = email => /^([a-zA-Z0-9_.+-])+\@(([a-zA-Z0-9-])+\.)+([a-zA-Z0-9]{2,4})+$/.test( email );
	
	/**
	 * Find Nested Element.
	 * Same as $(el)find().
	 *
	 * @param {jQuery|HTMLElement|String} el
	 * @param {jQuery|HTMLElement|String} selector
	 * @return {*|jQuery|HTMLElement}
	 */
	const findEl = ( el, selector ) => el.find( selector );
	
	/**
	 * Get Element.
	 * Alternative to $(selector) with support for find nested element.
	 *
	 * @param {jQuery|HTMLElement|Array|String} el
	 * @param {jQuery|HTMLElement|String} selector
	 * @return {*|jQuery|HTMLElement}
	 */
	const getEl = ( el, selector ) => {
		if ( 'Array' === el.constructor.name && 2 === el.length ) {
			selector = el[1];
			el = el[0];
		}
		if ( 'string' === typeof el ) {
			el = $( el );
		}
		if ( selector && 'string' === typeof selector ) {
			return findEl( el, selector );
		}
		return el;
	};
	
	/**
	 * Get Closest Element.
	 * Same as $(el).closest().
	 *
	 * @param {jQuery|HTMLElement|Array|String} el
	 * @param {jQuery|HTMLElement|String} selector
	 * @return {*|jQuery|HTMLElement|null|Element}
	 */
	const closestEl = ( el, selector ) => {
		return getEl( el ).closest( selector );
	}
	
	/**
	 * Get Element Value.
	 * $(el).val()
	 * @param {jQuery|HTMLElement|Array|String} el
	 * @param {jQuery|HTMLElement|String} selector
	 * @return {*}
	 */
	const elVal = ( el, selector ) => getEl( el, selector ).val();
	
	/**
	 * Get Element Data attributes.
	 * $(el).data()
	 *
	 * @param {jQuery|HTMLElement|Array|String} selector
	 * @param {String} data
	 * @return {*}
	 */
	const elData = ( selector, data ) => getEl( selector ).data( data );
	
	// Dyamic Options.
	const {
		gdpr,
		messages: { cart_email_gdpr, no_thanks },
	} = SaleXpresso;
	// @TODO handle GDPR.
	
	/**
	 * Abundant Cart.
	 * Save Cart Data in case of user didn't complete the checkout.
	 * 
	 * @class SaleXpressoCaptureUserData
	 */
	class SaleXpressoCaptureUserData {
		/**
		 * Constructor
		 */
		constructor () {
			this._gdpr = gdpr;
			this._typingTimer;
			this._doneTypingInterval = 500;
			this._oldData = {};
			this._init();
		}
		
		/**
		 * Initialize.
		 *
		 * @private
		 */
		_init() {
			const self = this;
			$(document).on( 'keyup keypress change blur', 'input', self._saveData.bind( self ) );
			$(document).on( 'keydown', 'input', self._clearTheCountDown.bind( self ) );
			setTimeout( () => {
				self._saveData();
			}, 750 );
		}
		
		/**
		 * Save User Data.
		 * Debunced with setTimeout
		 *
		 * @param {Event} event
		 *
		 * @private
		 */
		_saveData( event ) {
			const self = this;
			self._clearTheCountDown();
			self._typingTimer = setTimeout( () => {
				const email = $( '#billing_email' ).val() || '';
				if ( isEmail( email ) ) {
					const firstName = $("#billing_first_name").val() || '';
					const lastName = $("#billing_last_name").val() || '';
					const phone = $("#billing_phone").val() || '';
					const data = { email, firstName, lastName, phone };
					if ( ! _.isEqual( data, self._oldData ) ) {
						self._oldData = data; // reduce backend call.
						wp.ajax.post( 'sxp_save_checkout', { email, firstName, lastName } );
					}
				}
			}, this._doneTypingInterval );
		}
		
		/**
		 * Clear Timer.
		 *
		 * @private
		 */
		_clearTheCountDown() {
			if ( this._typingTimer ) {
				clearTimeout( this._typingTimer );
			}
		}
	}
	
	new SaleXpressoCaptureUserData();
	
	$( document ).on( 'ready', function() {
		
		$( document )
				// Add to cat on single product page.
				.on( 'click', '.single_add_to_cart_button', function () {
				const el = $( this );
				const form = $( this ).closest( 'form.cart' );
				const qtyEl = $( '[name="quantity"]' );
				let qty = 1;
				if ( qtyEl.length ) {
					qty = qtyEl.val();
				}
				let data = [ { label: 'quantity', value: qty } ];
				if ( form.hasClass( 'variations_form' ) ) {
					data.push( { label: 'product_id', value: elVal( form, '[name="product_id"]' ) } );
					data.push( { label: 'variation_id', value: elVal( form, '[name="variation_id"]' ) } );
				} else {
					data.push( { label: 'product_id', value: elVal( el ) } );
				}
				
				sxpEvent( 'add-to-cart', data );
			} )
			// Add to cat on product archive page.
			.on( 'click', '.add_to_cart_button', function () {
				const el = $( this );
				sxpEvent( 'add-to-cart', [
					{ label: 'product_id', value: elData( el, 'product_id' ) },
					{ label: 'quantity', value: elData( el, 'quantity ') },
				] );
			} )
			.on( 'click', '.woocommerce-cart-form .product-remove > a', function () {
				const el = $( this );
				sxpEvent( 'remove-from-cart', [ { label: 'product_id', value: elData( el, 'product_id' ) } ] );
			} )
			.on( 'click', '.woocommerce-cart .restore-item', function () {
				sxpEvent( 'undo-remove-from-cart' );
			} );
		// Capture successfull checkout.
		$( 'form.checkout' ).on( 'checkout_place_order_success', function () {
			const data = [ {
				label: 'gateway_id',
				value: elVal( '[name="payment_method"]:checked' ),
			}, {
				label: 'total',
				value: parseFloat( $('.order-total').text().replace( /[^\d.]/gm, '' ) ).toFixed(2),
			} ];
			sxpEvent( 'checkout-completed', data );
		} );
	} );
}( jQuery, window, document, SaleXpresso ) );
