<?php
/**
 * Default Hooks
 *
 * @author   SaleXpresso
 * @category Core
 * @package  SaleXpresso
 * @version  1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	header( 'Status: 403 Forbidden' );
	header( 'HTTP/1.1 403 Forbidden' );
	die();
}
// We're keeping log file at wc log dir now.
add_filter( 'salexpresso_install_skip_create_files', '__return_true' );
// End of file hooks.php.
