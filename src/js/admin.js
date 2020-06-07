/**!
 * SaleXpresso Admin Scripts
 *
 * @author SaleXpresso <support@salexpresso.com>
 * @package SaleXpresso
 * @version 1.0.0
 * @since 1.0.0
 */

import 'url-search-params-polyfill';
// import _ from 'lodash';
import moment from 'moment';
import $ from 'jquery';
import feather from 'feather-icons';
import 'daterangepicker';
import 'jquery-modal';

// import { tabs } from './components/_tabs.js';
// import './components/_accordion';
// import { sprintf, _n } from '@wordpress/i18n';

( function( window, document, wp, pagenow, SaleXpresso ) {
	// const params = new URLSearchParams( location.search );
	//const sxpPage = 0 === pagenow.indexOf( 'salexpresso_page_' ) ? params.get( 'page' ) : false;
	//const sxpSubPage = sxpPage ? params.get( 'tab' ) : false;
	if ( SaleXpresso.hasOwnProperty( 'wrapClass' ) ) {
		$( '.wrap' ).addClass( SaleXpresso.wrapClass );
	}
	$( window ).on( 'load', function() {
		const sxhWrapper = $( '.sxp-wrapper' );
		$( document ).on( 'change', '.selector', function( event ) {
			event.preventDefault();
		} );
		if ( sxhWrapper.hasClass( 'sxp-has-tabs' ) ) {
			// tabs();
		}
	} );

	// date range picker
	const start = moment().subtract( 29, 'days' );
	const end = moment();
	function cb( startDt, endDt ) {
		$( '#reportrange span' ).html( startDt.format( 'MMMM D, YYYY' ) + ' - ' + endDt.format( 'MMMM D, YYYY' ) );
	}
	$( '#reportrange' ).daterangepicker( {
		startDate: start,
		endDate: end,
		ranges: {
			Today: [ moment(), moment() ],
			Yesterday: [ moment().subtract( 0, 'days' ), moment().subtract( 0, 'days' ) ],
			'Last 7 Days': [ moment().subtract( 6, 'days' ), moment() ],
			'Last 30 Days': [ moment().subtract( 29, 'days' ), moment() ],
			'This Month': [ moment().startOf( 'month' ), moment().endOf( 'month' ) ],
			'Last Month': [ moment().subtract( 1, 'month' ).startOf( 'month' ), moment().subtract( 1, 'month' ).endOf( 'month' ) ],
		},
	}, cb );
	cb( start, end );
	// Accordion Table
	$( function() {
		$( '.sxp-table tr.has-fold' ).on( 'click', function() {
			if ( $( this ).hasClass( 'open' ) ) {
				$( this ).removeClass( 'open' ).next( '.fold' ).removeClass( 'open' );
			} else {
				$( '.sxp-table tr.has-fold' ).removeClass( 'open' ).next( '.fold' ).removeClass( 'open' );
				$( this ).addClass( 'open' ).next( '.fold' ).addClass( 'open' );
			}
		} );
	} );

	// customer profile tab
	$( 'ul.tabs li' ).click( function() {
		const tabId = $( this ).attr( 'data-tab' );

		$( 'ul.tabs li' ).removeClass( 'current' );
		$( '.tab-content' ).removeClass( 'current' );

		$( this ).addClass( 'current' );
		$( '#' + tabId ).addClass( 'current' );
	} );

	// Initiate Feather Icon
	feather.replace( {
		'stroke-width': 2,
		width: 16,
		height: 16,
	} );

	// modal
	$( '#sxp-tag-modal' ).hide();
	$( '.sxp-tag-add a' ).on( 'click', 'tr', function() {
		$( '#sxp-tag-modal' ).modal( {
			fadeDuration: 100,
			closeExisting: false,
		} );
	} );

	// Scrollable Tab
	function horizontalScrollBar() {
		const $D = {
			addClass( e, t ) {
				return 'function' === typeof e.addClass ? e.addClass( t ) : e.classList.add( t );
			},
			removeClass( e, t ) {
				return 'function' === typeof e.removeClass ? e.removeClass( t ) : e.classList.remove( t );
			},
			hide( e ) {
				return $D.addClass( e, 'd-none' );
			},
			show( e ) {
				return $D.removeClass( e, 'd-none' );
			},
		};
		const throttle = function( callback, limit ) {
			let wait = false;
			return function() {
				if ( ! wait ) {
					callback.apply( null, arguments );
					wait = true;
					setTimeout( function() {
						wait = false;
					}, limit );
				}
			};
		};
		const requestAnimationFrameWithLeadingCall = function( callback, context ) {
			if ( window.requestAnimationFrame ) {
				let initialCall = false;
				return function() {
					const args = arguments;
					if ( ! initialCall ) {
						initialCall = true;
						return callback.apply( context, args );
					}
					window.requestAnimationFrame( function() {
						callback.apply( context, args );
					} );
				};
			}
			return throttle( callback, 16 );
		};
		const horizontalScrollNav = {
			backArrow: null,
			forwardArrow: null,
			currentCategory: null,
			allCategoryLinks: null,
			init() {
				const wrapperClass = '.horizontal-scroll-bar';
				const cateListClass = wrapperClass + ' ul';
				this._render = requestAnimationFrameWithLeadingCall( this._render.bind( this ) );
				this.categoryList = document.querySelector( cateListClass );
				if ( ! this.categoryList ) {
					return;
				}
				this.backArrow = document.querySelector( wrapperClass + ' .scroll-backward a' );
				this.forwardArrow = document.querySelector( wrapperClass + ' .scroll-forward a' );
				this.currentCategory = this.categoryList.querySelector( cateListClass + ' li.active' );
				this.allCategoryLinks = this.categoryList.querySelectorAll( cateListClass + ' li a' );
				// Fire up...
				this.initEvents();
				this._render();
				this._ensureActiveCategoryInView();
			},
			initEvents() {
				window.addEventListener( 'resize', this._render.bind( this ) );
				this.categoryList.addEventListener( 'scroll', this._render.bind( this ) );
				this.forwardArrow.addEventListener( 'click', this._forwardClicked.bind( this ) );
				this.backArrow.addEventListener( 'click', this._backClicked.bind( this ) );
				this.allCategoryLinks.forEach( function( t ) {
					t.addEventListener( 'click', this._categoryClicked.bind( this ) );
				}.bind( this ) );
			},
			_render() {
				if ( 0 < this.categoryList.scrollLeft ) {
					$D.show( this.backArrow );
				} else {
					$D.hide( this.backArrow );
				}
				if ( this.categoryList.scrollLeft + this.categoryList.clientWidth >= this.categoryList.scrollWidth ) {
					$D.hide( this.forwardArrow );
				} else {
					$D.show( this.forwardArrow );
				}
			},
			_forwardClicked( e ) {
				e.preventDefault();
				this.categoryList.scrollLeft += this.categoryList.clientWidth;
			},
			_backClicked( e ) {
				e.preventDefault( e );
				this.categoryList.scrollLeft -= this.categoryList.clientWidth;
			},
			_ensureActiveCategoryInView() {
				if ( this.currentCategory ) {
					if ( this.currentCategory.offsetLeft + this.currentCategory.clientWidth < this.categoryList.clientWidth - this.forwardArrow.clientWidth ) {

					} else {
						this.categoryList.scrollLeft = this.currentCategory.offsetLeft - this.forwardArrow.clientWidth;
					}
				}
				// this.currentCategory && (
				// 	this.currentCategory.offsetLeft + this.currentCategory.clientWidth < this.categoryList.clientWidth - this.forwardArrow.clientWidth || (
				// 		this.categoryList.scrollLeft = this.currentCategory.offsetLeft - this.forwardArrow.clientWidth
				// 	)
				// );
			},
			_categoryClicked( event ) {
				const e = event.currentTarget.offsetLeft + event.currentTarget.clientWidth,
					i = this.categoryList.clientWidth + this.categoryList.scrollLeft - this.forwardArrow.clientWidth,
					n = e - i,
					r = i < e;
				if ( event.currentTarget.offsetLeft - this.backArrow.clientWidth < this.categoryList.scrollLeft ) {
					this.categoryList.scrollLeft = event.currentTarget.offsetLeft - this.forwardArrow.clientWidth;
				}
				if ( r ) {
					this.categoryList.scrollLeft = this.categoryList.scrollLeft + n;
				}
				// event.currentTarget.offsetLeft - this.backArrow.clientWidth < this.categoryList.scrollLeft && (this.categoryList.scrollLeft = event.currentTarget.offsetLeft - this.forwardArrow.clientWidth),
				// r && (this.categoryList.scrollLeft = this.categoryList.scrollLeft + n);
			},
		};
		horizontalScrollNav.init();
	}
	horizontalScrollBar();

	$( '[href="#"]' ).on( 'click', function( e ) {
		e.preventDefault();
	} );
	$( '.nav-bar a' ).on( 'click', function() {
		const el = $( this ),
			li = el.closest( '.item' );
		$( '.nav-bar .item' ).removeClass( 'active' );
		li.addClass( 'active' );
	} );
}( window, document, wp, pagenow, SaleXpresso ) );
