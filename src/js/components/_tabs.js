import $ from 'jquery';

function tabs() {
	return $( document ).on( 'click', '[data-target]', function( event ) {
		const self = $( this ),
			tab = self.closest( '.tab-item' ),
			target = $( `#${ self.data( 'target' ) }` );
		if ( target.length ) {
			// Switch to the tab.
			event.preventDefault();
			if ( ! tab.hasClass( 'is-active' ) ) {
				$( '.tab-item' ).removeClass( 'is-active' );
				tab.addClass( 'is-active' );
				$( '.tab-content' ).removeClass( 'is-active' );
				target.addClass( 'is-active' );
			}
			self.trigger( 'shown' );
		}
	} );
}
export { tabs };
