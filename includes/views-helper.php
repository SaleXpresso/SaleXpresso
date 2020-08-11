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
