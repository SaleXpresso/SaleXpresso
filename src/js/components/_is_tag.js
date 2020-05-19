import $ from 'jquery';

/**
 *
 * @param {jQuery} el
 * @param {string} tagName
 * @private
 */
function _is_tag( el, tagName ) {
	return el.get( 0 ).tagName.toLowerCase() === tagName.toLocaleString();
}

$.fn._is_tag = function( tagName ) {
	return _is_tag( $( this ), tagName );
};

export { _is_tag };
