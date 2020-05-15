/**!
 * SaleXpresso Admin Scripts
 *
 * @author SaleXpresso <support@salexpresso.com>
 * @package SaleXpresso
 * @version 1.0.0
 * @since 1.0.0
 */
import { tabs } from './components/_tabs.js';
import 'url-search-params-polyfill';
import './components/_accordion';

// import $ from 'jquery';
// import { sprintf, _n } from '@wordpress/i18n';

( function( $, window, document, wp, pagenow, SaleXpresso ) {
	const params = new URLSearchParams( location.search );
	const sxp_page = 0 === pagenow.indexOf( 'salexpresso_page_' ) ? params.get( 'page' ) : false;
	const sxp_sub_page = sxp_page ? params.get( 'tab' ) : false;
	$( window ).on( 'load', function() {
		const sxhWrapper = $( '.sxp-wrapper' );
		$( document ).on( 'change', '.selector', function( event ) {
			event.preventDefault();
		} );
		if ( sxhWrapper.hasClass( 'sxp-has-tabs' ) ) {
			tabs();
		}
	} );

	// date range picker
	$( function() {
		const start = moment().subtract( 29, 'days' );
		const end = moment();

		function cb( start, end ) {
			$( '#reportrange span' ).html( start.format( 'MMMM D, YYYY' ) + ' - ' + end.format( 'MMMM D, YYYY' ) );
		}

		$( '#reportrange' ).daterangepicker( {
			startDate: start,
			endDate: end,
			ranges: {
				Today: [ moment(), moment() ],
				Yesterday: [ moment().subtract( 1, 'days' ), moment().subtract( 1, 'days' ) ],
				'Last 7 Days': [ moment().subtract( 6, 'days' ), moment() ],
				'Last 30 Days': [ moment().subtract( 29, 'days' ), moment() ],
				'This Month': [ moment().startOf( 'month' ), moment().endOf( 'month' ) ],
				'Last Month': [ moment().subtract( 1, 'month' ).startOf( 'month' ), moment().subtract( 1, 'month' ).endOf( 'month' ) ],
			},
		}, cb );

		cb( start, end );
	} );

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
		const tab_id = $( this ).attr( 'data-tab' );

		$( 'ul.tabs li' ).removeClass( 'current' );
		$( '.tab-content' ).removeClass( 'current' );

		$( this ).addClass( 'current' );
		$( '#' + tab_id ).addClass( 'current' );
	} );

	// Initiate Feather Icon
	feather.replace( {
		'stroke-width': 2,
		width: 16,
		height: 16,
	});
}( jQuery, window, document, wp, pagenow, SaleXpresso ) );
