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
if ( ! function_exists( 'sxp_get_views' ) ) {
	/**
	 * Render view (pug) files from the includes directory and return or print the content.
	 *
	 * @param string $view  View file name without extension.
	 * @param array  $args  [optional] variables to pass to the view file.
	 * @param string $path  [optional] path to find the view file relative to includes/views.
	 * @param bool   $echo return or print the output..
	 *
	 * @return string|void return or print rendered html
	 */
	function sxp_get_views( $view = '', $args = [], $path = '', $echo = true ) {
		if ( true === $echo ) {
			SXP()->views()->display( $view, $args, $path );
			return;
		}
		return SXP()->views()->render( $view, $args, $path, false );
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
			'SaleXpresso\List_Table\SXP_Customer_List_Table'       => 'customer',
			'SaleXpresso\List_Table\SXP_Customer_Group_List_Table' => 'customer-group',
			'SaleXpresso\List_Table\SXP_Customer_Type_List_Table'  => [ 'customer-group', 'customer-type' ],
			'SaleXpresso\List_Table\SXP_Customer_Tag_List_Table'   => [ 'customer-group', 'customer-tag' ],
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
			
			$class = $class;
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
if ( ! function_exists( 'sxp_views' ) ) {
	/**
	 * Render view (pug) files
	 *
	 * @param array $args {
	 *      Args.
	 *      @type string $view  View file name without extension.
	 *      @type array  $data  [optional] variables to pass to the view file.
	 *      @type string $path  [optional] path to find the view file relative to includes/views.
	 *      @type bool   $echo return or print the output..
	 * }
	 *
	 * @return string|void
	 */
	function sxp_views( $args ) {
		$args = wp_parse_args( $args, [
			'data' => [],
			'path' => '',
			'echo' => true,
		] );
		if ( ! isset( $args['view'] ) || ( isset( $args['view'] ) && empty( $args['view'] ) ) ) {
			return;
		}
		$engine = SaleXpresso\SXP_Views::get_instance( SXP() );
		if ( $args['echo'] ) {
			$engine->display( $args['view'], $args['data'], $args['path'] );
		} else {
			return $engine->render( $args['view'], $args['data'], $args['path'], false );
		}
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
if ( ! function_exists( 'sxp_sanitize_csv_ids' ) ) {
	/**
	 * Sanitize Comma Separated Values with callback.
	 *
	 * @param string $input The Input.
	 * @param string $sanitize_callback Optional. Sanitize Callback, default is absint
	 * @param bool $unique Return Only Unique
	 *
	 * @return string
	 */
	function sxp_sanitize_csv_ids( $input, $sanitize_callback = 'sanitize_text_field', $unique = true ) {
		if ( ! empty( $input ) ) {
			
			if ( ! is_callable( $sanitize_callback ) ) {
				$sanitize_callback = 'sanitize_text_field';
			}
			
			$output = sanitize_text_field( $input );
			
			$output = str_replace( [ ' ' ], '', $output );
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
if ( ! function_exists( 'sxp_csv_string_to_ids_array' ) ) {
	/**
	 * Convert comma separated values to integer array
	 * @param $input
	 *
	 * @return array|int[]
	 */
	function sxp_csv_string_to_ids_array( $input ) {
		if ( empty( $input ) ) {
			return [];
		}
		
		$output = str_replace( [ ' ' ], '', $input );
		$output = explode( ',', $output );
		$output = array_map( 'absint', $output );
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
		$ids  = sxp_csv_string_to_ids_array( get_option( 'salexpresso_ac_exclude_ids' ) );
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
		$ids  = sxp_csv_string_to_ids_array( get_option( 'salexpresso_st_exclude_ids' ) );
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
// End of file helper.php.
