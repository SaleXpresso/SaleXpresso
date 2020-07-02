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
// End of file helper.php.
