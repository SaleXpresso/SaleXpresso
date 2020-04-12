<?php
/**
 * Deprecated functions
 *
 * Where functions come to die.
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

/**
 * Runs a deprecated action with notice only if used.
 *
 * @since  1.0.0
 * @param string $tag         The name of the action hook.
 * @param array  $args        Array of additional function arguments to be passed to do_action().
 * @param string $version     The version of WooCommerce that deprecated the hook.
 * @param string $replacement The hook that should have been used.
 * @param string $message     A message regarding the change.
 */
function sxp_do_deprecated_action( $tag, $args, $version, $replacement = null, $message = null ) {
	if ( ! has_action( $tag ) ) {
		return;
	}
	sxp_deprecated_hook( $tag, $version, $replacement, $message );
	do_action_ref_array( $tag, $args ); // phpcs:ignore WordPress.NamingConventions.PrefixAllGlobals.DynamicHooknameFound
}

/**
 * Wrapper for deprecated functions so we can apply some extra logic.
 *
 * @since  1.0.0
 * @param string $function Function used.
 * @param string $version Version the message was added in.
 * @param string $replacement Replacement for the called function.
 */
function sxp_deprecated_function( $function, $version, $replacement = null ) {
	// phpcs:disable
	if ( is_ajax() || SXP()->is_rest_api_request() ) {
		do_action( 'deprecated_function_run', $function, $replacement, $version );
		$log_string  = "The {$function} function is deprecated since version {$version}.";
		$log_string .= $replacement ? " Replace with {$replacement}." : '';
		error_log( $log_string );
	} else {
		_deprecated_function( $function, $version, $replacement );
	}
	// phpcs:enable
}

/**
 * Wrapper for deprecated hook so we can apply some extra logic.
 *
 * @since  1.0.0
 * @param string $hook        The hook that was used.
 * @param string $version     The version of WordPress that deprecated the hook.
 * @param string $replacement The hook that should have been used.
 * @param string $message     A message regarding the change.
 */
function sxp_deprecated_hook( $hook, $version, $replacement = null, $message = null ) {
	// phpcs:disable
	if ( is_ajax() || SXP()->is_rest_api_request() ) {
		do_action( 'deprecated_hook_run', $hook, $replacement, $version, $message );
		
		$message    = empty( $message ) ? '' : ' ' . $message;
		$log_string = "{$hook} is deprecated since version {$version}";
		$log_string .= $replacement ? "! Use {$replacement} instead." : ' with no alternative available.';
		
		error_log( $log_string . $message );
	} else {
		_deprecated_hook( $hook, $version, $replacement, $message );
	}
	// phpcs:enable
}

/**
 * When catching an exception, this allows us to log it if unexpected.
 *
 * @since  1.0.0
 * @param Exception $exception_object The exception object.
 * @param string    $function The function which threw exception.
 * @param array     $args The args passed to the function.
 */
function sxp_caught_exception( $exception_object, $function = '', $args = array() ) {
	// phpcs:disable
	$message  = $exception_object->getMessage();
	$message .= '. Args: ' . print_r( $args, true ) . '.';
	
	do_action( 'woocommerce_caught_exception', $exception_object, $function, $args );
	error_log( "Exception caught in {$function}. {$message}." );
	// phpcs:enable
}

/**
 * Wrapper for _doing_it_wrong().
 *
 * @since  1.0.0
 * @param string $function Function used.
 * @param string $message Message to log.
 * @param string $version Version the message was added in.
 */
function sxp_doing_it_wrong( $function, $message, $version ) {
	// phpcs:disable
	$message .= ' Backtrace: ' . wp_debug_backtrace_summary();
	
	if ( is_ajax() || SXP()->is_rest_api_request() ) {
		do_action( 'doing_it_wrong_run', $function, $message, $version );
		error_log( "{$function} was called incorrectly. {$message}. This message was added in version {$version}." );
	} else {
		_doing_it_wrong( $function, $message, $version );
	}
	// phpcs:enable
}

/**
 * Wrapper for deprecated arguments so we can apply some extra logic.
 *
 * @since  1.0.0
 * @param  string $argument Argument used.
 * @param  string $version  Version the message was added in.
 * @param  string $message  Message to log.
 */
function sxp_deprecated_argument( $argument, $version, $message = null ) {
	// phpcs:disable
	if ( is_ajax() || SXP()->is_rest_api_request() ) {
		do_action( 'deprecated_argument_run', $argument, $message, $version );
		error_log( "The {$argument} argument is deprecated since version {$version}. {$message}" );
	} else {
		_deprecated_argument( $argument, $version, $message );
	}
	// phpcs:enable
}
// End of file deprecated-functions.php.
