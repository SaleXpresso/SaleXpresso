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
if ( ! function_exists( 'str_replace_trim' ) ) {
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
// End of file helper.php.
