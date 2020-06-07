/**
 * Dribbble like scrollltab.
 */

import _ from 'lodash';

/**
 * The Scrollable Nav Bar.
 *
 * @return {undefined|*} return void or object.
 */

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
		return _.throttle( callback, 16 );
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
	return horizontalScrollNav.init();
}

export { horizontalScrollBar };
