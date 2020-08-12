/**!
 * SaleXpresso Public Scripts
 *
 * @author SaleXpresso <support@salexpresso.com>
 * @package SaleXpresso
 * @version 1.0.0
 * @since 1.0.0
 */
// import _ from 'lodash';
( function( $, window, document, SaleXpresso, Cookies ) {
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
		_wpnonce,
		gdpr,
		ac_timeout,
		messages: { cart_email_gdpr, no_thanks },
	} = SaleXpresso;
	// @TODO handle GDPR.
	
	/**
	 * Abandon Cart.
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
			// this._oldData = "";
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
					
					const first_name = $("#billing_first_name").val() || '';
					const last_name = $("#billing_last_name").val() || '';
					const company = $("#billing_company").val() || '';
					const country = $("#billing_country").val() || '';
					const address_1 = $("#billing_address_1").val() || '';
					const address_2 = $("#billing_address_2").val() || '';
					const city = $("#billing_city").val() || '';
					const state = $("#billing_state").val() || '';
					const postcode = $("#billing_postcode").val() || '';
					const phone = $("#billing_phone").val() || '';
					const comments = $("#order_comments").val() || '';
					
					const data = {
						_wpnonce,
						email,
						first_name,
						last_name,
						company,
						country,
						address_1,
						address_2,
						city,
						state,
						postcode,
						phone,
						comments,
					};
					for ( const k of Object.keys( data ) ) {
						if ( '_wpnonce' === k ) {
							continue;
						}
						Cookies.set( `sxp_ac_${k}`, data[k], { expires: parseInt( ac_timeout ) } );
					}
					// const hash = JSON.stringify( data );
					// if ( self._oldData !== hash ) {
					// 	self._oldData = hash; // reduce backend call.
					// 	wp.ajax.post( 'sxp_save_abandon_cart_data', data );
					// }
					wp.ajax.post( 'sxp_save_abandon_cart_data', data );
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
		const checkoutForm = $( 'form.checkout' );
		checkoutForm.on( 'checkout_place_order_success', function () {
			const data = [ {
				label: 'gateway_id',
				value: elVal( '[name="payment_method"]:checked' ),
			}, {
				label: 'total',
				value: parseFloat( $('.order-total').text().replace( /[^\d.]/gm, '' ) ).toFixed(2),
			} ];
			sxpEvent( 'checkout-completed', data );
		} );
		if ( checkoutForm.length ) {
		
		}
		const keys = 'email,first_name,last_name,company,country,address_1,address_2,city,state,postcode,phone,comments';
		for ( const k of keys.split( ',' ) ) {
			if ( k ) {
				let id = `billing_${k}`;
				if ( 'comments' === k ) {
					id = `order_${k}`;
				}
				$(`#${id}`).val( Cookies.get( `sxp_ac_${k}` ) );
			}
		}
	} );
}( jQuery, window, document, SaleXpresso, Cookies ) );
