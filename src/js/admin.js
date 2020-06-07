/**!
 * SaleXpresso Admin Scripts
 *
 * @author SaleXpresso <support@salexpresso.com>
 * @package SaleXpresso
 * @version 1.0.0
 * @since 1.0.0
 */

import 'url-search-params-polyfill';
//import _ from 'lodash';
import $ from 'jquery';
import moment from 'moment';
import feather from 'feather-icons';
import 'daterangepicker';
import 'jquery-modal';
import 'selectize';
import { horizontalScrollBar } from './components/_horizontalScrollBar';

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

	$( '[href="#"]' ).on( 'click', function( e ) {
		e.preventDefault();
	} );

	//$( '.selectize' ).selectize();

	// date range picker
	const reportRenge = $( '#reportrange' );
	const start = moment().subtract( 29, 'days' );
	const end = moment();
	const dateRangeRender = ( startDt, endDt ) => {
		reportRenge.find( 'span' ).html( startDt.format( 'MMMM D, YYYY' ) + ' - ' + endDt.format( 'MMMM D, YYYY' ) );
	};

	reportRenge.daterangepicker( {
		startDate: start,
		endDate: end,
		ranges: {
			Today: [ moment(), moment() ],
			Yesterday: [ moment().subtract( 0, 'days' ), moment().subtract( 0, 'days' ) ],
			'Last 7 Days': [ moment().subtract( 6, 'days' ), moment() ],
			'Last 30 Days': [ moment().subtract( 29, 'days' ), moment() ],
			'This Month': [ moment().startOf( 'month' ), moment().endOf( 'month' ) ],
			'Last Month': [ moment().subtract( 1, 'month' ).startOf( 'month' ), moment().subtract( 1, 'month' ).endOf( 'month' ) ],
			'This Year': [ moment().startOf( 'year' ), moment().endOf( 'year' ) ],
			'Last Year': [ moment().subtract( 1, 'year' ).startOf( 'year' ), moment().subtract( 1, 'year' ).endOf( 'year' ) ],
		},
		autoApply: true,
	}, dateRangeRender );
	dateRangeRender( start, end );

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
	horizontalScrollBar();

	$( '.nav-bar a' ).on( 'click', function() {
		const el = $( this ),
			li = el.closest( '.item' );
		$( '.nav-bar .item' ).removeClass( 'active' );
		li.addClass( 'active' );
	} );
}( window, document, wp, pagenow, SaleXpresso ) );
