<?php
/**
 * Helper Functions
 *
 * @author   SaleXpresso
 * @category Core
 * @package  SaleXpresso\Functions
 * @version  1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	header( 'Status: 403 Forbidden' );
	header( 'HTTP/1.1 403 Forbidden' );
	die();
}

if ( ! function_exists( 'sxp_load_file' ) ) {
	/**
	 * Load File
	 *
	 * @param string $file      File Path To Load.
	 * @param bool   $if_exists Check if file exists before load.
	 *                          Default true.
	 * @param bool   $required  Load using require statement.
	 *                          Default false.
	 * @param bool   $once      Load once.
	 *                          Default false.
	 *
	 * @return bool|mixed
	 */
	function sxp_load_file( $file, $if_exists = true, $required = false, $once = false ) {
		return SXP()->load_file( $file, $if_exists, $required, $once );
	}
}
if ( ! function_exists( 'sxp_get_plugin_uri' ) ) {
	/**
	 * Get Plugin file URI
	 *
	 * @param string $file get uri for assets file from plugin directory.
	 *
	 * @return string
	 */
	function sxp_get_plugin_uri( $file = '' ) {
		$file = ltrim( $file, '/' );
		return SXP()->plugin_url() . '/' . $file;
	}
}
if ( ! function_exists( 'sxp_get_upload_path' ) ) {
	/**
	 * Get Upload Directory location for internal uses
	 *
	 * @param string $path   directory/path name to generate full absolute path.
	 * @param bool   $exists check if directory exists.
	 *
	 * @return string|false
	 */
	function sxp_get_upload_path( $path = '', $exists = false ) {
		$path = untrailingslashit( $path );
		$path = unleadingslashit( $path );
		if ( empty( $path ) ) {
			return false;
		}
		$path = SXP_UPLOAD_DIR . '/' . $path;
		if ( true === $exists && ! file_exists( $path ) ) {
			return false;
		}
		return $path;
	}
}
if ( ! function_exists( 'sxp_get_file_upload_path' ) ) {
	/**
	 * Get full file for a file name.
	 * Check and return full file path.
	 *
	 * @param string $file   File name with ext.
	 * @param string $path   Directory name to look for.
	 * @param bool   $exists [optional] flag to check if the file is already exists.
	 *
	 * @return bool|false|string
	 */
	function sxp_get_file_upload_path( $file, $path, $exists = false ) {
		$file = untrailingslashit( $file );
		$file = unleadingslashit( $file );
		if ( ! empty( $file ) ) {
			$path = sxp_get_upload_path( $path );
			if ( $path ) {
				$path .= '/' . $file;
				if ( true === $exists && ! file_exists( $path ) ) {
					return false;
				}
				return $path;
			}
		}
		return false;
	}
}
if ( ! function_exists( 'sxp_site_is_https' ) ) {
	/**
	 * Check if the home URL is https. If it is, we don't need to do things such as 'force ssl'.
	 *
	 * @see wc_site_is_https
	 *
	 * @return bool
	 */
	function sxp_site_is_https() {
		return false !== strstr( get_option( 'home' ), 'https:' );
	}
}
if ( ! function_exists( 'sxp_setcookie' ) ) {
	/**
	 * Set a cookie - wrapper for setcookie using WP constants.
	 *
	 * @see wc_setcookie
	 *
	 * @param  string  $name   Name of the cookie being set.
	 * @param  string  $value  Value of the cookie.
	 * @param  integer $expire Expiry of the cookie.
	 * @param  bool    $secure Whether the cookie should be served only over https.
	 * @param  bool    $httponly Whether the cookie is only accessible over HTTP, not scripting languages like JavaScript. @since 3.6.0.
	 */
	function sxp_setcookie( $name, $value, $expire = 0, $secure = false, $httponly = false ) {
		if ( ! headers_sent() ) {
			setcookie( $name, $value, $expire, COOKIEPATH ? COOKIEPATH : '/', COOKIE_DOMAIN, $secure, apply_filters( 'woocommerce_cookie_httponly', $httponly, $name, $value, $expire, $secure ) );
		} elseif ( defined( 'WP_DEBUG' ) && WP_DEBUG ) {
			headers_sent( $file, $line );
			trigger_error( "{$name} cookie cannot be set - headers already sent by {$file} on line {$line}", E_USER_NOTICE ); // @codingStandardsIgnoreLine
		}
	}
}
if ( ! function_exists( 'unleadingslashit' ) ) {
	/**
	 * Removes leading forward slashes and backslashes if they exist.
	 *
	 * The primary use of this is for paths and thus should be used for paths. It is
	 * not restricted to paths and offers no specific path support.
	 *
	 * @param string $string What to remove the trailing slashes from.
	 * @return string String without the trailing slashes.
	 */
	function unleadingslashit( $string ) { // phpcs:ignore WordPress.NamingConventions.PrefixAllGlobals.NonPrefixedFunctionFound
		return ltrim( $string, '/\\' );
	}
}
if ( ! function_exists( 'sxp_replace_whitespace' ) ) {
	/**
	 * Replace/remove all whitespace from string
	 *
	 * @param string|string[] $string      Input string.
	 * @param string          $replacement [optional] Replacement for the whitespace removed..
	 *
	 * @return string|string[]|null
	 */
	function sxp_replace_whitespace( $string, $replacement = '' ) {
		return preg_replace( '/\s.*/', $replacement, $string );
	}
}
if ( ! function_exists( 'sxp_get_logger' ) ) {
	/**
	 * Get Logger
	 *
	 * @return WC_Logger
	 */
	function sxp_get_logger() {
		return wc_get_logger();
	}
}
if ( ! function_exists( 'sxp_is_running_from_async_action_scheduler' ) ) {
	/**
	 * Is Action scheduler request running.
	 *
	 * @return bool
	 */
	function sxp_is_running_from_async_action_scheduler() {
		return isset( $_REQUEST['action'] ) && 'as_async_request_queue_runner' === $_REQUEST['action']; // phpcs:ignore WordPress.Security.NonceVerification.Recommended
	}
}
if ( ! function_exists( 'sxp_get_screen_ids' ) ) {
	/**
	 * Get All Screen ids of SaleXpresso.
	 *
	 * @return array
	 */
	function sxp_get_screen_ids() {
		global $sxp_screen_ids;
		if ( empty( $sxp_screen_ids ) ) {
			$sxp_screen_ids = [];
		}
		return $sxp_screen_ids;
	}
}
if ( ! function_exists( 'sxp_get_wc_screen_ids' ) ) {
	/**
	 * Get all WooCommerce screen ids.
	 *
	 * @see wc_get_screen_ids
	 * @return array return screen id string array if wc installed.
	 */
	function sxp_get_wc_screen_ids() {
		if ( function_exists( '' ) ) {
			return wc_get_screen_ids();
		}
		return [];
	}
}
if ( ! function_exists( 'sxp_get_screen_ids_for_admin_notice' ) ) {
	/**
	 * Get all screen id for where we can show notices
	 *
	 * @return array
	 */
	function sxp_get_screen_ids_for_admin_notice() {
		return array_merge( sxp_get_screen_ids(), sxp_get_wc_screen_ids() );
	}
}
if ( ! function_exists( 'sxp_str_replace_trim' ) ) {
	/**
	 * Apply trim after str_replace.
	 * Replace all occurrences of the search string with the replacement string.
	 * Strip whitespace (or other characters) from the beginning and end of a string.
	 *
	 * @link https://php.net/manual/en/function.str-replace.php
	 * @link https://php.net/manual/en/function.trim.php
	 *
	 * @param string[]|string $search   The value being searched for, otherwise known as the needle.
	 *                                  An array may be used to designate multiple needles.
	 * @param string[]|string $replace  The replacement value that replaces found search values.
	 *                                  An array may be used to designate multiple replacements.
	 * @param string[]|string $subject  The string or array being searched and replaced on, otherwise known as the haystack.
	 * @param int             $count    [optional] If passed, this will hold the number of matched and replaced needles.
	 * @param string          $charlist [optional] Character list for the trim function.
	 *
	 * @return string[]|string
	 */
	function sxp_str_replace_trim( $search, $replace, $subject, &$count = null, $charlist = " \t\n\r\0\x0B" ) {
		$replaced = str_replace( $search, $replace, $subject, $count );
		if ( ! is_array( $replaced ) ) {
			return trim( $replaced, $charlist );
		}
		return array_map( function ( $string ) use ( $charlist ) {
			return trim( $string, $charlist );
		}, $replaced );
	}
}
if ( ! function_exists( '_sxp_get_list_table' ) ) {
	/**
	 * Fetches an instance of a WP_List_Table class.
	 *
	 * @access private
	 *
	 * @global string $hook_suffix
	 *
	 * @param string $class The type of the list table, which is the class name.
	 * @param array  $args  Optional. Arguments to pass to the class. Accepts 'screen'.
	 * @return SaleXpresso\SXP_List_Table|bool List table object on success, false if the class does not exist.
	 */
	function _sxp_get_list_table( $class, $args = [] ) {
		
		$core_classes = [
			'SaleXpresso\List_Table\SXP_Customer_List_Table'          => 'customer',
			'SaleXpresso\List_Table\SXP_Customer_Activity_List_Table' => 'customer-activity',
			'SaleXpresso\List_Table\SXP_Customer_Group_List_Table'    => 'customer-group',
			'SaleXpresso\List_Table\SXP_Customer_Type_List_Table'     => [ 'customer-group', 'customer-type', ],
			'SaleXpresso\List_Table\SXP_Customer_Tag_List_Table'      => [ 'customer-group', 'customer-tag', ],
			'SaleXpresso\List_Table\SXP_Abundant_Cart_List_Table'     => 'abundant-cart',
		];
		if ( isset( $core_classes[ $class ] ) ) {
			foreach ( (array) $core_classes[ $class ] as $required ) {
				sxp_load_file( 'includes/classes/list-table/class-sxp-' . $required . '-list-table.php' );
			}
			
			if ( isset( $args['screen'] ) ) {
				$args['screen'] = convert_to_screen( $args['screen'] );
			} elseif ( isset( $GLOBALS['hook_suffix'] ) ) {
				$args['screen'] = get_current_screen();
			} else {
				$args['screen'] = null;
			}
			
			return new $class( $args );
		}
		
		return false;
	}
}
if ( ! function_exists( 'add_actions' ) ) {
	/**
	 * Hooks a function on to a array of action.
	 *
	 * @see add_action
	 * @param callable  $function_to_add The name of the function you wish to be called.
	 * @param string[]  $tags            The name of the action to which the $function_to_add is hooked.
	 * @param int|int[] $priority        Optional. Used to specify the order in which the functions
	 *                                   associated with a particular action are executed. Default 10.
	 *                                   Lower numbers correspond with earlier execution,
	 *                                   and functions with the same priority are executed
	 *                                   in the order in which they were added to the action.
	 * @param int|int[] $accepted_args   Optional. The number of arguments the function accepts. Default 1.
	 * @return true Will always return true.
	 */
	function add_actions( $function_to_add, $tags, $priority = 10, $accepted_args = 1 ) { // phpcs:ignore WordPress.NamingConventions.PrefixAllGlobals.NonPrefixedFunctionFound
		return add_filters( $function_to_add, $tags, $priority, $accepted_args );
	}
}
if ( ! function_exists( 'add_filters' ) ) {
	/**
	 * Hook a function or method to a array of filter action.
	 *
	 * @see add_filter
	 * @param callable  $function_to_add The callback to be run when the filter is applied.
	 * @param string[]  $tags            The name of the filter to hook the $function_to_add callback to.
	 * @param int|int[] $priority        Optional. Used to specify the order in which the functions
	 *                                   associated with a particular action are executed.
	 *                                   Lower numbers correspond with earlier execution,
	 *                                   and functions with the same priority are executed
	 *                                   in the order in which they were added to the action. Default 10.
	 * @param int|int[] $accepted_args   Optional. The number of arguments the function accepts. Default 1.
	 * @return true
	 */
	function add_filters( $function_to_add, $tags, $priority = 10, $accepted_args = 1 ) { // phpcs:ignore WordPress.NamingConventions.PrefixAllGlobals.NonPrefixedFunctionFound
		if ( ! empty( $tags ) ) {
			if ( empty( $priority ) ) {
				$priority = 10;
			}
			if ( empty( $accepted_args ) ) {
				$accepted_args = 1;
			}
			$i = 0;
			foreach ( (array) $tags as $tag ) {
				$_priority      = is_array( $priority ) ? ( isset( $priority[ $i ] ) ? absint( $priority[ $i ] ) : 10 ) : absint( $priority );
				$_accepted_args = is_array( $accepted_args ) ? ( isset( $accepted_args[ $i ] ) ? absint( $accepted_args[ $i ] ) : 1 ) : absint( $accepted_args );
				add_filter( $tag, $function_to_add, $_priority, $_accepted_args );
				$i++;
			}
		}
		return true;
	}
}
if ( ! function_exists( 'is_wp_post' ) ) {
	/**
	 * Check whether variable is a WordPress Post Object.
	 *
	 * Returns true if $thing is an object of the WP_Post class.
	 *
	 * @param mixed $thing Check if unknown variable is a WP_Post object.
	 * @return bool True, if WP_Post. False, if not WP_Post.
	 */
	function is_wp_post( $thing ) {
		return ( $thing instanceof WP_Post );
	}
}
if ( ! function_exists( 'sxp_help_tip' ) ) {
	/**
	 * Display a SaleXpresso help tip.
	 *
	 * @see wc_help_tip()
	 * @param  string $tip        Help tip text.
	 * @param  bool   $allow_html Allow sanitized HTML if true or escape.
	 * @return string
	 */
	function sxp_help_tip( $tip, $allow_html = false ) {
		if ( $allow_html ) {
			$tip = wc_sanitize_tooltip( $tip );
		} else {
			$tip = esc_attr( $tip );
		}
		
		return '<span class="sxp-help-tip" data-tip="' . $tip . '"></span>';
	}
}
if ( ! function_exists( 'sxp_sanitize_csv_field' ) ) {
	/**
	 * Sanitize Comma Separated Values with callback.
	 *
	 * @param string   $input             The Input.
	 * @param callable $sanitize_callback Optional. Sanitize Callback, default is absint
	 * @param bool     $unique            Return Only Unique
	 *
	 * @return string
	 */
	function sxp_sanitize_csv_field( $input, $sanitize_callback = null, $unique = true ) {
		if ( ! empty( $input ) ) {
			
			if ( ! is_callable( $sanitize_callback ) ) {
				$sanitize_callback = 'sanitize_text_field';
			}
			
			$output = sanitize_text_field( $input );
			
			// Input might have extra (needed) white space (eg \t,\n,\r\n, etc).
			// Don't remove them.
			// Caller can use callback to trim the output or change individual item type.
			
			$output = explode( ',', $output );
			$output = array_map( $sanitize_callback, $output );
			$output = array_filter( $output );
			
			if ( $unique ) {
				$output = array_unique( $output );
			}
			
			return implode( ',', $output );
		}
		return '';
	}
}
if ( ! function_exists( 'sxp_csv_string_to_array' ) ) {
	/**
	 * Convert comma separated values to integer array
	 *
	 * @param string $input String to convert.
	 * @param callable $map_cb Optional. Map output array values.
	 *
	 * @return array|string[]|int[]
	 */
	function sxp_csv_string_to_array( $input, $map_cb = null ) {
		if ( empty( $input ) ) {
			return [];
		}
		
		// Input might have extra (needed) white space (eg \t,\n,\r\n, etc).
		// Don't remove them.
		// Caller can use callback to trim the output or change individual item type.
		
		$output = explode( ',', $input );
		if ( is_callable( $map_cb ) ) {
			$output = array_map( $map_cb, $output );
		}
		$output = array_filter( $output );
		
		return array_unique( $output );
	}
}
if ( ! function_exists( 'sxp_get_the_user' ) ) {
	/**
	 * Get User Object.
	 *
	 * @param int|string $user Optional. User ID, Email or login (username) to fine the user.
	 *                         Default to current user.
	 *
	 * @return bool|WP_User
	 */
	function sxp_get_the_user( $user = null ) {
		if ( is_null( $user ) ) {
			if ( ! function_exists( 'wp_get_current_user' ) ) {
				return false;
			}
			$user = wp_get_current_user();
			
			return $user->ID > 0 ? $user : false;
		}
		if ( ! function_exists( 'get_user_by' ) ) {
			return false;
		}
		if ( is_numeric( $user ) ) {
			return get_user_by( 'id', (int) $user );
		} elseif ( is_email( $user ) ) {
			return get_user_by( 'email', $user );
		} elseif ( is_string( $user ) ) {
			return get_user_by( 'login', $user );
		}
		
		return false;
	}
}
if ( ! function_exists( 'sxp_is_abundant_cart_enabled_for_user' ) ) {
	/**
	 * Check if abundant cart is enabled for current user.
	 *
	 * @param int $user Optional. Default to current user id.
	 * @return bool
	 */
	function sxp_is_abundant_cart_enabled_for_user( $user = null ) {
		if ( 'yes' !== get_option( 'salexpresso_ac_enable', 'yes' ) ) {
			// cart tracking disabled.
			return  true;
		}
		$ids  = sxp_csv_string_to_array( get_option( 'salexpresso_ac_exclude_ids' ), 'absint' );
		$user = sxp_get_the_user( $user );
		
		if ( empty( $user ) ) {
			return false;
		}
		
		if ( ! empty( $ids ) ) {
			if ( in_array( $user->ID, $ids ) ) {
				// exclude user.
				return true;
			}
		}
		
		return $user->has_cap( 'disable_abundant_cart' );
	}
}
if ( ! function_exists( 'sxp_exclude_user_from_session_tracking' ) ) {
	/**
	 * Check if session tracking is enabled for current user.
	 *
	 * @param int $user Optional. Default to current user id.
	 * @return bool
	 */
	function sxp_exclude_user_from_session_tracking( $user = null ) {
		if ( 'yes' !== get_option( 'salexpresso_st_enable', 'yes' ) ) {
			// session tracking disabled.
			return  true;
		}
		$ids  = sxp_csv_string_to_array( get_option( 'salexpresso_st_exclude_user_ids' ), 'absint' );
		$user = sxp_get_the_user( $user );
		
		if ( empty( $user ) ) {
			return false;
		}
		
		if ( ! empty( $ids ) ) {
			if ( in_array( $user->ID, $ids ) ) {
				// exclude user.
				return true;
			}
		}
		
		return $user->has_cap( 'disable_session_tracking' );
	}
}
if ( ! function_exists( 'sxp_api_link' ) ) {
	/**
	 * Retrieves the URL for a api path (action).
	 *
	 * @see site_url()
	 * @param string $path   Optional. Path relative to the site URL. Default empty.
	 * @param string $scheme Optional. Scheme to give the site URL context. See set_url_scheme().
	 * @return string|bool   Site URL link with optional path appended.
	 */
	function sxp_api_link( $path = '', $scheme = '' ) {
		return sxp_get_api_link( null, $path, $scheme );
	}
}
if ( ! function_exists( 'sxp_get_api_link' ) ) {
	/**
	 * Retrieves the URL for a api path (action).
	 *
	 * @see get_site_url()
	 * @param int    $blog_id Optional. Site ID. Default null (current site).
	 * @param string $path    Optional. Path relative to the site URL. Default empty.
	 * @param string $scheme  Optional. Scheme to give the site URL context. Accepts
	 *                        'http', 'https', 'login', 'login_post', 'admin', or
	 *                        'relative'. Default null.
	 * @return string|false   Site URL link with optional path appended.
	 */
	function sxp_get_api_link( $blog_id = null, $path = '', $scheme = '' ) {
		$path = untrailingslashit( $path );
		$path = sanitize_text_field( $path );
		$path = str_replace( [ ' ' ], '-', $path );
		if ( ! $path ) {
			return false;
		}
		if ( get_option( 'permalink_structure' ) ) {
			$path = 'sxp-api/' . $path;
		} else {
			$path = '?sxp-api=' . $path;
		}
		
		$url = get_site_url( $blog_id, $path, $scheme );
		
		return trailingslashit( $url );
	}
}
if ( ! function_exists( 'sxp_get_host_name' ) ) {
	/**
	 * Get hostname from url.
	 *
	 * @param string $url
	 *
	 * @return string
	 */
	function sxp_get_host_name( $url ) {
		if ( false === strpos( $url, '://' ) ) {
			$url = 'http://' . $url;
		}
		return wp_parse_url( $url, PHP_URL_HOST );
	}
}
if ( ! function_exists( 'sxp_get_acquired_via' ) ) {
	/**
	 * Get User acquired via.
	 *
	 * @param array $data Session/Analytics data.
	 * @param string $context Context of use. This is useful for the filters.
	 *                        Developer can use this context in filter callbacks to determine
	 *                        why or where this function is being called.
	 *
	 * @return mixed
	 */
	function sxp_get_acquired_via( $data, $context = 'view' ) {
		if ( isset( $data['session_meta']['ip'] ) ) {
			unset( $data['session_meta']['ip'] );
		}
		
		if ( isset( $data['session_meta']['affiliate'] ) && ! empty( $data['session_meta']['affiliate'] ) ) {
			$acquired_via = sprintf(
				/* translator: 1. Affiliate Name or ID */
				esc_html_x( 'Affiliate:: %s', 'Set User Acquired Via Meta', 'salexpresso' ),
				esc_attr( $data['session_meta']['affiliate'] )
			);
			$acquired_via = apply_filters( 'salexpresso_acquired_via_affiliate_id', $acquired_via, $data, $context );
		} else if ( isset( $data['session_meta']['referral'] ) && ! empty( $data['session_meta']['referral'] ) ) {
			$acquired_via = sprintf(
				/* translator: 1. Referral Name or ID */
				esc_html_x( 'Referral:: %s', 'Set User Acquired Via Meta', 'salexpresso' ),
				esc_attr( $data['session_meta']['referral'] )
			);
			$acquired_via = apply_filters( 'salexpresso_acquired_via_referral_id', $acquired_via, $data, $context );
		} else if ( ! empty( $data['source'] ) && ! empty( $data['campaign'] ) ) {
			$acquired_via = sprintf(
				/* translator: 1. Marketing Campaign Name, 2. Marketing Campaign Source */
				esc_html_x( 'Campaign:: %s (%s)', 'Set User Acquired Via Meta', 'salexpresso' ),
				esc_attr( $data['campaign'] ),
				esc_attr( $data['source'] )
			);
			
			$acquired_via = apply_filters( 'salexpresso_acquired_via_campaign', $acquired_via, $data, $context );
		} else if( isset( $data['referrer'] ) && ! empty( $data['referrer'] ) ) {
			$acquired_via = apply_filters( 'salexpresso_acquired_via_referrer', sxp_get_host_name( $data['referrer'] ), $data, $context );
		} else {
			$acquired_via = esc_html_x( 'Direct Visit', 'User Acquired Via Direct Visit', 'salexpresso' );
			$acquired_via = apply_filters( 'salexpresso_acquired_via_bookmark', $acquired_via, $data, $context );
		}
		
		return apply_filters( 'salexpresso_acquired_via', $acquired_via, $data );;
	}
}
if ( ! function_exists( 'sxp_update_user_acquired_via' ) ) {
	/**
	 * Update user's acquired_via meta.
	 *
	 * @param int   $user_id WP User ID
	 * @param bool  $_is_unique Is unique visit.
	 * @param array $data    Session tracking data.
	 *
	 * @return void
	 */
	function sxp_update_user_acquired_via( $user_id, $_is_unique = false, $data = array() ) {
		if ( ! is_user_logged_in() ) {
			return;
		}
		
		$cc_id = get_user_meta( $user_id, '_sxp_cookie_id', true );
		
		$_acquired_via = get_user_meta( $user_id, 'acquired_via', true );
		$is_organic = 0;
		$acquired_via = '';
		if ( empty( $_acquired_via ) ) {
			// doesn't have acquired_via and current visit is not unique.
			if ( ! $_is_unique ) {
				global $wpdb;
				// find the first visit.
				/** @noinspection SqlResolve */
				$record = $wpdb->get_row(
					$wpdb->prepare(
						"SELECT * FROM {$wpdb->sxp_analytics} WHERE visitor_id = %s ORDER BY created ASC LIMIT 1;",
						$cc_id
					),
					ARRAY_A
				);
				
				if ( ! empty( $record ) ) {
					$record['session_meta'] = ! empty( $record['session_meta'] ) ? json_decode( $record['session_meta'], true ) : [];
					$data = $record;
					unset( $record );
				}
			}
			$acquired_via = sxp_get_acquired_via( $data, 'save' );
			$is_organic = $data['is_organic'];
		} else {
			$_is_organic = (int) get_user_meta( $user_id, '__is_organic', true );
			$total_spent = (float) wc_get_customer_total_spent( $user_id );
			if ( $_is_organic && 0 == $total_spent && ! $data['is_organic'] ) {
				// ony update organic user if new visits isn't organic & user hasn't purchased anything yet.
				$acquired_via = sxp_get_acquired_via( $data, 'save' );
				$is_organic = $data['is_organic'];
			}
		}
		// update user meta.
		if ( ! empty( $acquired_via ) ) {
			if ( has_filter( 'salexpresso_update_user_acquired_via' ) ) {
				/**
				 * Filters User acquired via data before updating user meta.
				 *
				 * @param array $acquired_via_data {
				 *      @type string $acquired_via Required.
				 *      @type int $is_organic Required.
				 * }
				 */
				$filtered = trim( apply_filters( 'salexpresso_update_user_acquired_via', [ $acquired_via, $is_organic ], $data ) );
				if ( isset( $filtered[0], $filtered[1] ) && ! empty( $filtered[0] ) ) {
					$acquired_via = $filtered[0];
					$is_organic = (bool) $filtered[1];
					unset( $_acquired_via );
				}
			}
			update_user_meta( $user_id, '__acquired_via', $acquired_via );
			update_user_meta( $user_id, '__is_organic', $is_organic );
		}
	}
}
if ( ! function_exists( 'sxp_wp_unique_id' ) ) {
	/**
	 * Get unique ID.
	 *
	 * This is a PHP implementation of Underscore's uniqueId method. A static variable
	 * contains an integer that is incremented with each call. This number is returned
	 * with the optional prefix. As such the returned value is not universally unique,
	 * but it is unique across the life of the PHP process.
	 *
	 * @see wp_unique_id() Themes requiring WordPress 5.0.3 and greater should use this instead.
	 *
	 * @staticvar int $id_counter
	 *
	 * @param string $prefix Prefix for the returned ID.
	 * @return string Unique ID.
	 */
	function sxp_wp_unique_id( $prefix = '' ) {
		static $id_counter = 0;
		if ( function_exists( 'wp_unique_id' ) ) {
			return wp_unique_id( $prefix );
		}
		return $prefix . (string) ++$id_counter;
	}
}
if ( ! function_exists( 'sxp_get_random_bytes' ) ) {
	/**
	 * Generates a string of pseudo-random bytes.
	 *
	 * @param int $length Optional. Default is 16
	 *
	 * @return string
	 */
	function sxp_get_random_bytes( $length = 16 ) {
		$data = '';
		try {
			if ( function_exists( 'random_bytes' ) ) {
				$data = random_bytes( $length );
			} else if ( function_exists( 'openssl_random_pseudo_bytes' ) ) {
				$data = openssl_random_pseudo_bytes( $length );
			}
		} catch ( Exception $e ) {
			// Do nothing.
		}
		
		if ( $length !== strlen( $data ) ) {
			global $wp_hasher;
			
			if ( empty( $wp_hasher ) ) {
				require_once ABSPATH . WPINC . '/class-phpass.php';
				$wp_hasher = new PasswordHash( 8, true );
			}
			
			$data = $wp_hasher->get_random_bytes( $length );
		}
		return $data;
	}
}
if ( ! function_exists( 'sxp_get_uuid_v4' ) ) {
	function sxp_get_uuid_v4() {
		$data = sxp_get_random_bytes( 16 );
		$data[6] = chr(ord($data[6]) & 0x0f | 0x40); // set version to 0100
		$data[8] = chr(ord($data[8]) & 0x3f | 0x80); // set bits 6-7 to 10
		
		return vsprintf('%s%s-%s-%s-%s-%s%s%s', str_split(bin2hex($data), 4));
	}
}
if ( ! function_exists( 'sxp_array_multi_search' ) ) {
	/**
	 * Searches the (multidimensional) array for a given value and returns
	 * the corresponding key/s if successful.
	 * Max 2 dimension.
	 *
	 * @link https://stackoverflow.com/questions/6661530/php-multidimensional-array-search-by-value
	 *
	 * @param string       $search          the needle.
	 * @param string       $field           the field/column to check.
	 * @param array|object $data            the haystack.
	 * @param bool         $return_multiple return the first match or all keys.
	 * @param bool         $strict          user strict match. Optional. default null.
	 *
	 * @return int|int[]|string|string[]|false the key for needle if it is found in the
	 *
	 */
	function sxp_array_multi_search( $search, $field, $data, $return_multiple = false, $strict = null ) {
		$data = array_column( (array) $data, $field );
		$args = false === $return_multiple? [ $search, $data ] : [ $data, $search ];
		if ( ! is_null( $strict ) ) {
			$args[] = ! ! $strict;
		}
		if ( false === $return_multiple ) {
			return call_user_func_array( 'array_search', $args );
		} else {
			return call_user_func_array( 'array_keys', $args );
		}
	}
}
if ( ! function_exists( 'sxp_array_multi_search_assoc' ) ) {
	/**
	 * Searches the (multidimensional) associative array for a given value and returns
	 * the corresponding key/s if successful.
	 * Max 2 dimension.
	 *
	 * @param string       $search          the needle.
	 * @param string       $field           the field/column to check.
	 * @param array|object $data            the haystack.
	 * @param bool         $return_multiple return the first match or all keys.
	 * @param bool         $strict          user strict match. Optional. default null.
	 *
	 * @return int|int[]|string|string[]|false the key for needle if it is found in the
	 *
	 */
	function sxp_array_multi_search_assoc( $search, $field, $data, $return_multiple = false, $strict = null ) {
		$data = (array) $data;
		$data = array_combine( array_keys( $data ), array_column( $data, $field ) );
		$args = false === $return_multiple? [ $search, $data ] : [ $data, $search ];
		if ( ! is_null( $strict ) ) {
			$args[] = ! ! $strict;
		}
		
		if ( false === $return_multiple ) {
			return call_user_func_array( 'array_search', $args );
		} else {
			return call_user_func_array( 'array_keys', $args );
		}
		
	}
}
if ( ! function_exists( 'sxp_deep_clean' ) ) {
	/**
	 * Sanitize Input Data.
	 * Similar to wc_clean() but works with associative arrays and objects.
	 *
	 * @param mixed    $input             Data to sanitize
	 * @param bool     $sanitize_keys     Optional. Sanitize array keys. Default true.
	 * @param callable $sanitize_callback Optional. Sanitize function.
	 *                                    Default is sanitize_text_field.
	 *
	 * @return array|string|object|bool
	 * @see wc_clean()
	 */
	function sxp_deep_clean( $input, $sanitize_keys = true, $sanitize_callback = null ) {
		if ( ! is_callable( $sanitize_callback ) ) {
			$sanitize_callback = 'sanitize_text_field';
		}
		$input = wp_unslash( $input );
		if ( is_array( $input ) ) {
			$output = [];
			foreach ( $input as $k => $v ) {
				if ( $sanitize_keys ) {
					$k = sanitize_text_field( $k );
				}
				$output[ $k ] = sxp_deep_clean( $v, $sanitize_keys, $sanitize_callback );
			}
			return $output;
		} else if ( is_object( $input ) ) {
			$output = new stdClass();
			foreach ( get_object_vars( $input ) as $k => $v ) {
				if ( $sanitize_keys ) {
					$k = sanitize_text_field( $k );
				}
				$output->$k = sxp_deep_clean( $v, $sanitize_keys, $sanitize_callback );
			}
		} else {
			return is_scalar( $input ) ? call_user_func( $sanitize_callback, $input ) : '';
		}
	}
}
if ( ! function_exists( 'sxp_get_wc_payment_gateways' ) ) {
	/**
	 * Get All Payment Gateway.
	 *
	 * @param string $id Gateway ID. Optional to get all.
	 *
	 *
	 * @return array|false|WC_Payment_Gateway|WC_Payment_Gateway[]
	 */
	function sxp_get_wc_payment_gateways( $id = null ) {
		if ( WC()->payment_gateways() ) {
			$payment_gateways = WC()->payment_gateways()->payment_gateways();
			// do not use get_available_payment_gateways method.
		} else {
			$payment_gateways = array();
		}
		if ( ! is_null( $id ) ) {
			if ( isset( $payment_gateways[ $id ] ) ) {
				return $payment_gateways[ $id ];
			} else {
				return false;
			}
		}
		return $payment_gateways;
	}
}
// End of file helper.php.
