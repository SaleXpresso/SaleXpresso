import $ from 'jquery';

const CLASS_NAME_HAS_FOLD = 'has-fold';
const CLASS_NAME_FOLD = 'fold';
const CLASS_NAME_OPEN = 'open';

function Accordion( el ) {
	if ( ! el.find( 'tr' ).hasClass( CLASS_NAME_HAS_FOLD ) || ! el.find( 'tr' ).hasClass( CLASS_NAME_FOLD ) ) {
		return;
	}

	const hide = elem => elem.removeClass( CLASS_NAME_OPEN ).next( `.${ CLASS_NAME_FOLD }` ).removeClass( CLASS_NAME_OPEN );
	const show = elem => elem.addClass( CLASS_NAME_OPEN ).next( `.${ CLASS_NAME_FOLD }` ).addClass( CLASS_NAME_OPEN );

	return el.find( `tr.${ CLASS_NAME_HAS_FOLD }` ).on( 'click', function( e ) {
		e.preventDefault();
		e.stopPropagation();
		const row = $( this );
		if ( row.hasClass( CLASS_NAME_OPEN ) ) {
			hide( row );
		} else {
			hide( el.find( `tr.${ CLASS_NAME_FOLD }` ) );
			show( row );
		}
	} );
}

$.fn.tableAccordion = function() {
	return $( this ).each( function() {
		return Accordion( $( this ) );
	} );
};

export { Accordion };
