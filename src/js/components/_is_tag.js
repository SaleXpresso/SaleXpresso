import $ from 'jquery';

/**
 *
 * @param {jQuery} el
 * @param {string} tagName
 * @private
 */
function isTag( el, tagName ) {
	return el.get( 0 ).tagName.toLowerCase() === tagName.toLocaleString();
}

$.fn.isTag = function( tagName ) {
	return isTag( $( this ), tagName );
};

export { isTag };
