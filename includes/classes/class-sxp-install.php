<?php
/**
 * Installation related functions and actions.
 *
 * @package SaleXpresso/Core
 * @version 1.0.0
 */

namespace SaleXpresso;

if ( ! defined( 'ABSPATH' ) ) {
	header( 'Status: 403 Forbidden' );
	header( 'HTTP/1.1 403 Forbidden' );
	die();
}

/**
 * Class SXP_Install
 *
 * @package SaleXpresso
 */
class SXP_Install {
	
	/**
	 * Initialize
	 */
	public static function init() {
		add_action( 'init', [ __CLASS__, 'check_version' ], 5 );
	}
	
	/**
	 * Check WooCommerce version and run the updater is required.
	 *
	 * This check is done on all requests and runs if the versions do not match.
	 */
	public static function check_version() {
		if ( ! defined( 'IFRAME_REQUEST' ) && version_compare( get_option( 'salexpresso_version' ), SXP()->version, '<' ) ) {
			self::install();
			do_action( 'salexpresso_updated' );
		}
	}
	
	/**
	 * Install SaleXpresso.
	 *
	 * @return void
	 */
	public static function install() {
		if ( ! is_blog_installed() ) {
			return;
		}
		
		// Check if we are not already running this routine.
		if ( 'yes' === get_transient( 'sxp_installing' ) ) {
			return;
		}
		
		// If we made it till here nothing is running yet, lets set the transient now.
		set_transient( 'sxp_installing', 'yes', MINUTE_IN_SECONDS * 10 );
		if ( ! defined( 'SXP_INSTALLING' ) ) {
			define( 'SXP_INSTALLING', true );
		}
		SXP()->register_tables();
		self::remove_admin_notices();
		self::create_roles();
		self::setup_environment();
		self::create_cron_jobs();
		self::create_files();
		self::maybe_enable_setup_wizard();
		self::update_wc_version();
		
		delete_transient( 'sxp_installing' );
		do_action( 'salexpresso_flush_rewrite_rules' );
		do_action( 'salexpresso_installed' );
	}
	
	/**
	 * Reset all admin notices
	 *
	 * @return void
	 */
	private static function remove_admin_notices() {
		SXP_Admin_Notices::hide_notices();
	}
	
	/**
	 * Create Custom Roles.
	 *
	 * @return void
	 */
	private static function create_roles() {
		// @TODO create extra role for sales representative.
	}
	
	/**
	 * Create Taxonomies and rest endpoints.
	 *
	 * @return void
	 */
	private static function setup_environment() {
		SXP_Post_Types::register_taxonomies();
	}
	
	/**
	 * Is new install or updated from old version.
	 *
	 * @return bool
	 */
	public static function is_new_install() {
		return is_null( get_option( 'salexpresso_version', null ) ) || is_null( get_option( 'salexpresso_updated_form' ) );
	}
	
	/**
	 * Lunch the setup wizard.
	 *
	 * @return void
	 */
	private static function maybe_enable_setup_wizard() {
		if ( apply_filters( 'salexpresso_enable_setup_wizard', true ) && self::is_new_install() ) {
			SXP_Admin_Notices::add_notice( 'install', true );
			set_transient( '_sxp_activation_redirect', 1, 30 );
		}
	}
	
	/**
	 * Update WC version to current.
	 *
	 * @return void
	 */
	private static function update_wc_version() {
		$old = get_option( 'salexpresso_version', false );
		if ( false !== $old ) {
			update_option( 'salexpresso_updated_form', $old );
		}
		delete_option( 'salexpresso_version' );
		add_option( 'salexpresso_version', SXP()->version );
	}
	
	/**
	 * Create files/directories.
	 *
	 * @return void
	 */
	private static function create_files() {
		// Bypass if filesystem is read-only and/or non-standard upload system is used.
		if ( apply_filters( 'salexpresso_install_skip_create_files', false ) ) {
			return;
		}
		
		// Install files and folders for uploading files and prevent hotlinking.
		$upload_dir = wp_upload_dir();
		
		$files = [
			[
				'base'    => SXP_LOG_DIR,
				'file'    => '.htaccess',
				'content' => 'deny from all',
			],
			[
				'base'    => SXP_LOG_DIR,
				'file'    => 'index.php',
				'content' => "<?php\n// Silence is golden.",
			],
		];
		
		// @XXX Replace this with native WordPress filesystem.
		foreach ( $files as $file ) {
			if ( wp_mkdir_p( $file['base'] ) && ! file_exists( trailingslashit( $file['base'] ) . $file['file'] ) ) {
				$file_handle = @fopen( trailingslashit( $file['base'] ) . $file['file'], 'w' ); // phpcs:ignore WordPress.PHP.NoSilencedErrors.Discouraged, WordPress.WP.AlternativeFunctions.file_system_read_fopen
				if ( $file_handle ) {
					fwrite( $file_handle, $file['content'] ); // phpcs:ignore WordPress.WP.AlternativeFunctions.file_system_read_fwrite, WordPressVIPMinimum.Functions.RestrictedFunctions.file_ops_fwrite
					fclose( $file_handle ); // phpcs:ignore WordPress.WP.AlternativeFunctions.file_system_read_fclose
				}
			}
		}
	}
	
	/**
	 * Create Cron Jobs
	 *
	 * @return void
	 */
	private static function create_cron_jobs() {
		// @TODO Clear cron then add again...
	}
}

// End of file class-sxp-install.php.
