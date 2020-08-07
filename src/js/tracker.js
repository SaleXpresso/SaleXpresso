/* eslint-env browser */

( function( window, document, sxpOptions, onPageData ) {
	if ( ! window ) {
		return;
	}
	
	/////////////////////
	// PREDEFINED VARIABLES FOR BETTER MINIFICATION
	//
	
	// This seems like a lot of repetition, but it makes our script available for
	// multiple destination which prevents us to need multiple scripts. The minified
	// version stays small.
	
	// Collection TYPES
	const TYPE_ERROR = 'error';
	const TYPE_EVENT = 'event';
	const TYPE_PAGE_VIEW = 'page-view';
	const TYPE_PAGE_LEAVE = 'page-leave';
	
	const version = '{{VERSION}}';
	const sxpGlobal = 'sxpEvent';
	const con = window.console;
	const doNotTrack = 'doNotTrack';
	const slash = '/';
	const nav = window.navigator;
	const loc = window.location;
	const SITE_URL = '{{SITE_URL}}';
	const path_replace = SITE_URL.replace( new RegExp( '.*' + location.hostname + '\/?' ), '' );
	const PATH_NAME = loc.pathname.replace( path_replace, '' ).replace( /^\/\//, '/' );
	const userAgent = nav.userAgent;
	const notSending = 'Not sending request ';
	const encodeURIComponentFunc = encodeURIComponent;
	const decodeURIComponentFunc = decodeURIComponent;
	const stringify = JSON.stringify;
	const thousand = 1000;
	const addEventListenerFunc = window.addEventListener;
	const undefinedVar = undefined;
	const documentElement = document.documentElement || {};
	const language = 'language';
	const Height = 'Height';
	const Width = 'Width';
	const scroll = 'scroll';
	const scrollHeight = scroll + Height;
	const offsetHeight = 'offset' + Height;
	const clientHeight = 'client' + Height;
	const clientWidth = 'client' + Width;
	const screen = window.screen;
	const sendBeaconText = 'sendBeacon';
	
	const uuid = () => {
		const cryptoObject = window.crypto || window.msCrypto;
		const emptyUUID = [ 1e7 ] + -1e3 + -4e3 + -8e3 + -1e11;
		const uuidRegex = /[018]/g;
		try {
			return emptyUUID.replace( uuidRegex, c => (
				c ^ ( cryptoObject.getRandomValues( new Uint8Array( 1 ) )[ 0 ] & ( 15 >> ( c / 4 ) ) ) // eslint-disable-line no-bitwise
			).toString( 16 ) );
		} catch ( error ) {
			return emptyUUID.replace( uuidRegex, c => {
					const r = ( Math.random() * 16 ) | 0, // eslint-disable-line no-bitwise
						v = c < 2 ? r : ( r & 0x3 ) | 0x8; // eslint-disable-line no-bitwise
					return v.toString( 16 );
				},
			);
		}
	};
	const assign = ( ...objects ) => {
		const to = {};
		const arg = objects;
		for ( let index = 0; index < arg.length; index++ ) {
			const nextSource = arg[ index ];
			if ( nextSource ) {
				for ( const nextKey in nextSource ) {
					if ( Object.prototype.hasOwnProperty.call( nextSource, nextKey ) ) {
						to[ nextKey ] = nextSource[ nextKey ];
					}
				}
			}
		}
		return to;
	};
	
	const getParams = function( regex ) {
		// From the search we grab the utm_source and ref and save only that
		const matches = loc.search.match( new RegExp( '[?&](' + regex + ')=([^?&]+)', 'gi' ) );
		const match = matches ? matches.map( m => m.split( '=' )[ 1 ] ) : [];
		if ( match && match[ 0 ] ) {
			return match[ 0 ];
		}
	};
	
	/////////////////////
	// GET SETTINGS
	//
	
	// const isBoolean = value => !! value === value;
	// Find the script element where options can be set on
	// const attr = ( el, attribute ) => el && el.getAttribute( 'data-' + attribute );
	const api = '{{api_url}}'; // api url used as pixel too.
	const tracker = '{{tracker}}'; // tracker file name used in script src attribute.
	let {
		mode,
		ignorePages,
		autoCollect,
		recordDnt,
	} = assign(
		{
			mode: '', // Script mode, this can be hash mode for example
			ignorePages: [], // Customers can ignore certain pages
			autoCollect: true, // Some customers want to collect page views manually
			recordDnt: false, // Should we record Do Not Track visits?
		},
		sxpOptions
	);
	
	
	
	/////////////////////
	// HELPER FUNCTIONS
	//
	const now = Date.now;
	
	// Ignore pages specified in data-ignore-pages
	const shouldIgnore = function( path ) {
		for ( const i in ignorePages ) {
			const ignorePageRaw = ignorePages[ i ];
			if ( ! ignorePageRaw ) {
				continue;
			}
			// Prepend a slash when it's missing
			const ignorePage = ignorePageRaw[ 0 ] === '/' ? ignorePageRaw : '/' + ignorePageRaw;
			try {
				if ( ignorePage === path || new RegExp( ignorePage.replace( /\*/gi, '(.*)' ), 'gi' ).test( path ) ) {
					return true;
				}
			} catch ( error ) {
				return false;
			}
		}
		return false;
	};
	
	/////////////////////
	// SEND DATA VIA OUR PIXEL
	//
	
	// Send data via image (pixel)
	const sendData = function( data, callback ) {
		data = assign( payload, data );
		
		callback = ( callback && 'function' === typeof callback ) ? callback : () => {};
		
		// data = assign( append, payload );
		if ( ! ( sendBeaconText in nav ) ) {
			const image = new Image();
			if ( callback && 'function' === typeof callback ) {
				image.onerror = callback;
				image.onload = callback;
			}
			image.src = api + ( api.indexOf( '?' ) > -1 ? '&' : '?' ) + 'payload=' + stringify( data );
		} else {
			nav[ sendBeaconText ]( api, stringify( data ) );
			callback();
		}
	};
	
	/////////////////////
	// ERROR FUNCTIONS
	//
	
	// Send errors
	const sendError = function( errorOrMessage ) {
		errorOrMessage = errorOrMessage.message || errorOrMessage;
		warn( errorOrMessage );
		sendData( {
			type: TYPE_ERROR,
			error: errorOrMessage,
			url: SITE_URL + PATH_NAME,
		} );
	};
	
	// A simple log function so the user knows why a request is not being send
	const warn = message => {
		// @TODO only execute the next block if dev mode is enabled.
		if ( con && con.warn ) {
			con.warn( 'SaleXpresso Analytics:', message );
		}
	};
	
	// Make sure ignore pages is an array
	if ( ! Array.isArray( ignorePages ) ) {
		if ( typeof ignorePages === 'string' && ignorePages.length ) {
			ignorePages = ignorePages.split( /, ?/ ).map( page => page.trim() );
		} else {
			ignorePages = [];
		}
	}
	
	// bot detection.
	const bot =
		nav.webdriver ||
		( window.hasOwnProperty( '__nightmare' ) && window.__nightmare ) ||
		'callPhantom' in window || '_phantom' in window || 'phantom' in window ||
		/(bot|spider|crawl)/i.test( userAgent ) || (
			window.chrome && (
				'' === nav.languages ||
				! ( nav.plugins instanceof PluginArray )
			)
		);
	
	// This code could error on (incomplete) implementations, that's why we use try...catch
	let timezone;
	try {
		timezone = Intl.DateTimeFormat().resolvedOptions().timeZone;
	} catch ( e ) {
		/* Do nothing */
	}
	
	/////////////////////
	// PAYLOAD FOR BOTH PAGE VIEWS AND EVENTS
	//
	const payload = {
		version,
		bot,
		timezone,
		meta: {
			object_type: onPageData.wp_object_type,
			object_id: onPageData.wp_object_id,
		},
	};
	
	try {
		
		// We listen for the error events and only send errors that are
		// from our script (checked by filename) to our server.
		addEventListenerFunc( TYPE_ERROR, function( event ) {
			if ( event.filename && event.filename.indexOf( tracker ) > -1 ) {
				sendError( event.message );
			}
		}, false );
		
		const pushState = 'pushState';
		const dispatchEvent = window.dispatchEvent;
		const duration = 'duration';
		let start = now();
		let scrolled = 0;
		
		// Warn when no document.doctype is defined (this breaks some documentElement dimensions)
		if ( ! document.doctype ) {
			warn( 'Add DOCTYPE html for more accurate dimensions' );
		}
		
		// Don't track when Do Not Track is set to true
		if ( ! recordDnt && doNotTrack in nav && nav[ doNotTrack ] === '1' ) {
			return warn( notSending + 'when ' + doNotTrack + ' is enabled' );
		}
		
		/////////////////////
		// SETUP INITIAL VARIABLES
		//
		
		let page = {};
		let lastPageId = uuid();
		let lastSendPath;
		
		// We don't want to end up with sensitive data so we clean the referrer URL
		// Eg.
		let referrer = ( document.referrer || '' )
			.replace( /^https?:\/\/((m|l|w{2,3}([0-9]+)?)\.)?([^?#]+)(.*)$/, '$4' )
			.replace( /^([^/]+)$/, '$1' ) || undefinedVar;
		
		// The prefix utm_ is optional
		const utmRegexPrefix = '(utm_)?';
		const source = {
			source: getParams( utmRegexPrefix + 'source|ref' ),
			medium: getParams( utmRegexPrefix + 'medium' ),
			campaign: getParams( utmRegexPrefix + 'campaign' ),
			term: getParams( utmRegexPrefix + 'term' ),
			content: getParams( utmRegexPrefix + 'content' ),
			referrer,
		};
		
		/////////////////////
		// TIME ON PAGE AND SCROLLED LOGIC
		//
		
		// We don't put msHidden in if duration block, because it's used outside of that functionality
		let msHidden = 0;
		
		let hiddenStart;
		window.addEventListener( 'visibilitychange', function() {
			if ( document.hidden ) {
				hiddenStart = now();
			} else {
				msHidden += now() - hiddenStart;
			}
		}, false );
		
		const sendOnLeave = function( id, push ) {
			const append = {
				type: TYPE_PAGE_LEAVE,
				page_id: push ? id : lastPageId,
				duration: Math.round( ( now() - start + msHidden ) / thousand )
			};
			
			msHidden = 0;
			start = now();
			append.scrolled = Math.max( 0, scrolled, position() );
			sendData( append );
		};
		
		addEventListenerFunc( 'unload', sendOnLeave, false );
		
		const body = document.body || {};
		
		const position = () => {
			try {
				const documentClientHeight = documentElement[ clientHeight ] || 0;
				const height = Math.max( body[ scrollHeight ] || 0, body[ offsetHeight ] || 0, documentElement[ clientHeight ] || 0, documentElement[ scrollHeight ] || 0, documentElement[ offsetHeight ] || 0 );
				return Math.min( 100, Math.round( ( 100 * ( ( documentElement.scrollTop || 0 ) + documentClientHeight ) ) / height / 5 ) * 5 );
			} catch ( error ) {
				return 0;
			}
		};
		
		addEventListenerFunc( 'load', function() {
			scrolled = position();
			addEventListenerFunc( scroll, function() {
				if ( scrolled < position() ) {
					scrolled = position();
				}
			}, false );
		} );
		
		/////////////////////
		// ACTUAL PAGE VIEW LOGIC
		//
		
		const getPath = function( overwrite ) {
			let path = overwrite || decodeURIComponentFunc( PATH_NAME );
			// Ignore pages specified in data-ignore-pages
			if ( shouldIgnore( path ) ) {
				warn( notSending + 'because ' + path + ' is ignored' );
				return;
			}
			// Add hash to path when script is put in to hash mode
			if ( mode === 'hash' && loc.hash ) {
				path += loc.hash.split( '?' )[ 0 ];
			}
			
			return path;
		};
		
		// Send page view and append data to it
		const sendPageView = function( isPushState, deleteSourceInfo, sameSite ) {
			
			if ( isPushState ) {
				sendOnLeave( '' + lastPageId, true );
			}
			
			lastPageId = uuid();
			page.page_id = lastPageId;
			
			const currentPage = SITE_URL + getPath();
			
			sendData(
				assign(
					page,
					deleteSourceInfo ? { referrer: sameSite ? referrer : null } : source,
					{ type: TYPE_PAGE_VIEW },
				),
			);
			
			referrer = currentPage;
		};
		
		const PageView = function( isPushState, pathOverwrite ) {
			// Obfuscate personal data in URL by dropping the search and hash
			const path = getPath( pathOverwrite );
			
			// Don't send the last path again (this could happen when pushState is used to change the path hash or search)
			if ( ! path || lastSendPath === path ) {
				return;
			}
			
			lastSendPath = path;
			
			const data = {
				path,
				viewport_width: Math.max( documentElement[ clientWidth ] || 0, window.innerWidth || 0 ) || null,
				viewport_height: Math.max( documentElement[ clientHeight ] || 0, window.innerHeight || 0, ) || null,
				screen_width: screen.width,
				screen_height: screen.height,
			};
			
			if ( nav[ language ] ) {
				data[ language ] = nav[ language ];
			}
			
			// If a user does refresh we need to delete the referrer because otherwise it count double
			const perf = window.performance;
			const navigation = 'navigation';
			
			// Check if back, forward or reload buttons are being used in modern browsers
			const userNavigated =
				perf &&
				perf.getEntriesByType &&
				perf.getEntriesByType( navigation )[ 0 ] &&
				perf.getEntriesByType( navigation )[ 0 ].type
					? [ 'reload', 'back_forward' ].indexOf( perf.getEntriesByType( navigation )[ 0 ].type ) > -1
					: perf && perf[ navigation ] && [ 1, 2 ].indexOf( perf[ navigation ].type ) > -1; // Check if back, forward or reload buttons are being use in older browsers 1: TYPE_RELOAD, 2: TYPE_BACK_FORWARD
			
			// Check if referrer is the same as current hostname
			const sameSite = referrer ? referrer.split( slash )[ 0 ] === SITE_URL : false;
			
			// We set unique variable based on pushState or back navigation, if no match we check the referrer
			data.is_unique = isPushState || userNavigated ? false : ! sameSite;
			
			page = data;
			
			sendPageView( isPushState, isPushState || userNavigated, sameSite );
		};
		
		/////////////////////
		// AUTOMATED PAGE VIEW COLLECTION
		//
		
		const his = window.history;
		const hisPushState = his ? his.pushState : undefinedVar;
		
		// Overwrite history pushState function to
		// allow listening on the pushState event
		if ( autoCollect && hisPushState && Event && dispatchEvent ) {
			const stateListener = function( type ) {
				const orig = his[ type ];
				return function() {
					const arg = arguments;
					const rv = orig.apply( this, arg );
					let event;
					if ( typeof Event === 'function' ) {
						event = new Event( type );
					} else {
						// Fix for IE
						event = document.createEvent( 'Event' );
						event.initEvent( type, true, true );
					}
					event.arguments = arg;
					dispatchEvent( event );
					return rv;
				};
			};
			
			his.pushState = stateListener( pushState );
			
			addEventListenerFunc( pushState, function() {
				PageView( 1 );
			}, false );
			
			addEventListenerFunc( 'popstate', function() {
				PageView( 1 );
			}, false );
		}
		
		// When in hash mode, we record a PageView based on the onhashchange function
		if ( autoCollect && mode === 'hash' && 'onhashchange' in window ) {
			addEventListenerFunc( 'hashchange', function() {
				PageView( 1 );
			}, false );
		}
		
		// This script should be loaded inside <head> tag.
		// Collect page view soons as it loaded (if autoCollect enabled).
		if ( autoCollect ) {
			PageView();
		} else {
			window.sxpPageView = function( path ) {
				PageView( 0, path );
			};
		}
		
		/////////////////////
		// EVENTS
		//
		// replace this session if from cookie session id.
		
		const validTypes = [ 'string', 'number' ];
		
		const sendEvent = function( event, meta, callbackRaw ) {
			const isFunction = event instanceof Function;
			const callback = callbackRaw instanceof Function ? callbackRaw : () => {};
			
			if ( validTypes.indexOf( typeof event ) < 0 && ! isFunction ) {
				warn( 'event is not a string: ' + event );
				return callback();
			}
			
			try {
				if ( isFunction ) {
					if ( validTypes.indexOf( typeof event ) < 0 ) {
						warn( 'event function output is not a string: ' + event );
						return callback();
					}
				}
			} catch ( error ) {
				warn( 'Error in your event function: ' + error.message );
				return callback();
			}
			
			event = ( '' + event )
				.replace( /[^a-z0-9]+/gi, '-' )
				.replace( /(^-|-$)/g, '' )
				.toLowerCase();
			
			if ( event ) {
				sendData(
					assign(
						source,
						bot,
						{
							meta: { event: meta }
						},
						{
							type: TYPE_EVENT,
							event, // Event Category.
							page_id: page.page_id,
						},
					),
					callback,
				);
			}
		};
		
		const defaultEventFunc = function( event, data, callback ) {
			sendEvent( event, data, callback );
		};
		
		// Set default function if user didn't define a function
		if ( ! window[ sxpGlobal ] ) {
			window[ sxpGlobal ] = defaultEventFunc;
		}
		
		const eventFunc = window[ sxpGlobal ];
		
		// Read queue of the user defined function
		const queue = eventFunc && eventFunc.q ? eventFunc.q : [];
		
		// Overwrite user defined function
		window[ sxpGlobal ] = defaultEventFunc;
		
		// Post events from the queue of the user defined function
		for ( const event in queue ) {
			sendEvent( queue[ event ] );
		}
	} catch ( e ) {
		sendError( e );
	}
}( window, document, '{{tracker_options}}', sxpSessionOnPageData ) );
