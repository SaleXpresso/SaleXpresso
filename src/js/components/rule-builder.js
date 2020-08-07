/**
 * Handle Rule Building
 * @version 1.0.0
 */
import $ from 'jquery';

const sxpRuleGroupClass = '.sxp-rule-group';
const sxpRuleClass = '.sxp-rule-single';
const sxpRuleGroupRelation = '.sxp-group-relation';
const sxpRuleRelation = '.rule-relation';

/**
 * The Rule Builder.
 * @constructor
 */
function sxpRuleBuilder() {
	
	// The indexes are to keep thing together while sending the data over http.
	// Php will organize/reset the array indexes (GIDXs & CIDXs)
	
	let wrapper = $( '.sxp-rules' ),
		group_template = $( '.rule_ui_group_template' ).text().trim(),
		condition_template = $( '.rule_ui_condition_template' ).text().trim(),
		__GIDX__ = wrapper.find( sxpRuleGroupClass ).length || 0;
	
	// If not template do nothing...
	if ( ! wrapper.length || '' === group_template || '' === condition_template ) {
		return;
	}
	
	const addCondition = function( group_wrapper, _gidx_ ) {
		_gidx_ = _gidx_ || group_wrapper.data( 'group_id' );
		group_wrapper = 'string' === typeof group_wrapper ? $( group_wrapper ) : group_wrapper;
		const condition_wrapper = group_wrapper.find( '.sxp-group-rules' );
		const group_conditions = condition_wrapper.find( sxpRuleClass );
		let __CIDX__ = ( condition_wrapper.data( 'index' ) || group_conditions.length ) + 1;
		
		condition_wrapper
			.data( 'index', __CIDX__ )
			.append( condition_template.replace( /__GIDX__/g, _gidx_ ).replace( /__IDX__/g, __CIDX__ ) );
		
		$( '.rule_' + __CIDX__ ).prev().find( sxpRuleRelation ).show();
	};
	
	$( document ).on( 'click', '.remove-group a', function ( event ) {
		event.preventDefault();
		const group = $( this ).closest( sxpRuleGroupClass );
		const groupPrev = group.prev();
		if ( groupPrev.length && ! group.next().length ) {
			groupPrev.find( sxpRuleGroupRelation ).hide();
		}
		group.remove();
	} );
	
	$( document ).on ( 'click', '.sxp-remove-rule', function ( event ) {
		event.preventDefault();
		const rule = $( this ).closest( sxpRuleClass );
		const rulePrev = rule.prev();
		if ( rulePrev.length && ! rule.next().length ) {
			rulePrev.find( sxpRuleRelation ).hide();
		}
		rule.remove();
	} );
	
	$( document ).on( 'click', '.sxp-add-rule-group', function ( event ) {
		event.preventDefault();
		__GIDX__ += 1;
		wrapper.append( group_template.replace( /__IDX__/g, __GIDX__ ) );
		const group = $( '.rule_group_' + __GIDX__ );
		if ( group.prev().length ) {
			group.prev().find( sxpRuleGroupRelation ).show();
		}
		
		// Add 1 blank condition to the group to help the user out.
		addCondition( group, __GIDX__ );
	} );
	
	// Handle add new condition button.
	$( document ).on( 'click', '.sxp-add-rule-condition', function ( event ) {
		event.preventDefault();
		addCondition( $( this ).closest( sxpRuleGroupClass ) );
	} );
	
}

export { sxpRuleBuilder };
