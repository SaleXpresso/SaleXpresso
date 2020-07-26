<?php
/**
 * Installation related functions and actions.
 *
 * @package SaleXpresso/Core
 * @version 1.0.0
 */

namespace SaleXpresso;

use SaleXpresso\Settings\SXP_Admin_Settings;

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
		
		add_filter( 'wpmu_drop_tables', [ __CLASS__, 'wpmu_drop_tables' ], 10 );
		
		add_filter( 'plugin_action_links_' . SXP_PLUGIN_BASENAME, [ __CLASS__, 'plugin_action_links' ] );
		add_filter( 'plugin_row_meta', [ __CLASS__, 'plugin_row_meta' ], 10, 2 );
		
		add_action( 'salexpresso_update_roles_and_caps', [ __CLASS__, 'create_roles_caps' ] );
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
		self::create_tables();
		self::create_options();
		self::create_roles_caps();
		self::setup_environment();
		self::create_cron_jobs();
		self::create_files();
		self::maybe_enable_setup_wizard();
		self::update_sxp_version();
		self::maybe_update_db_version();
		
		delete_transient( 'sxp_installing' );
		do_action( 'salexpresso_flush_rewrite_rules' );
		do_action( 'salexpresso_installed' );
	}
	
	/**
	 * Default options.
	 *
	 * Sets up the default options used on the settings page.
	 */
	private static function create_options() {
		// Include settings so that we can run through defaults.
		include_once dirname( __FILE__ ) . '/settings/class-sxp-admin-settings.php';
		
		$settings = SXP_Admin_Settings::get_settings_pages();
		
		foreach ( $settings as $section ) {
			if ( ! method_exists( $section, 'get_settings' ) ) {
				continue;
			}
			$subsections = array_unique( array_merge( array( '' ), array_keys( $section->get_sections() ) ) );
			
			foreach ( $subsections as $subsection ) {
				foreach ( $section->get_settings( $subsection ) as $value ) {
					if ( isset( $value['default'] ) && isset( $value['id'] ) ) {
						$autoload = isset( $value['autoload'] ) ? (bool) $value['autoload'] : true;
						// using add option, so it doesn't accidentally updates existing options.
						add_option( $value['id'], $value['default'], '', ( $autoload ? 'yes' : 'no' ) );
					}
				}
			}
		}
	}
	
	/**
	 * Set up the database tables which the plugin needs to function.
	 *
	 * @return void
	 */
	private static function create_tables() {
		global $wpdb;
		
		$wpdb->hide_errors();
		
		if ( ! function_exists( 'dbDelta' ) ) {
			require_once ABSPATH . 'wp-admin/includes/upgrade.php';
		}
		
		dbDelta( self::get_schema() ); // phpcs:ignore WordPressVIPMinimum.Functions.RestrictedFunctions.dbDelta_dbdelta
		
	}
	
	/**
	 * Get Table schema.
	 *
	 * When adding or removing a table, make sure to update the list of tables in SXP_Install::get_tables().
	 *
	 * @return string
	 */
	private static function get_schema() {
		global $wpdb;
		
		$charset_collate = '';
		if ( $wpdb->has_cap( 'collation' ) ) {
			$charset_collate = $wpdb->get_charset_collate();
		}
		
		return <<<SQL
CREATE TABLE {$wpdb->prefix}sxp_analytics (
    id BIGINT(20) NOT NULL AUTO_INCREMENT,
	session_id VARCHAR(60) NOT NULL,
	page_id VARCHAR(36) NOT NULL,
	duration int DEFAULT 0,
	scrolled int DEFAULT 0,
	hostname TEXT,
	path TEXT,
	viewport_width int DEFAULT 0,
	viewport_height int DEFAULT 0,
	screen_width int DEFAULT 0,
	screen_height int DEFAULT 0,
	language VARCHAR( 6 ),
	is_unique int( 1 ) DEFAULT 0,
	referrer TEXT,
	timezone VARCHAR( 60 ),
	type VARCHAR( 60 ),
	bot int( 1 ) DEFAULT 0,
	version VARCHAR( 10 ),
	PRIMARY KEY  ( `id` ),
) {$charset_collate};
CREATE TABLE {$wpdb->prefix}sxp_abandon_cart (
	id BIGINT(20) NOT NULL AUTO_INCREMENT,
	email VARCHAR(100),
	session_id VARCHAR(60) NOT NULL,
	cart_contents LONGTEXT,
	order_id BIGINT(20),
	cart_total DECIMAL(10,2),
	cart_meta LONGTEXT NULL,
	status ENUM( 'processing','abandoned','recovered','completed', 'lost' ) NOT NULL DEFAULT 'processing',
	coupon_code VARCHAR(60) DEFAULT NULL,
	last_sent_email int DEFAULT 0,
    time DATETIME DEFAULT NULL,
    unsubscribed boolean DEFAULT 0,
	PRIMARY KEY  (`id`, `session_id`),
	UNIQUE KEY (`session_id`)
) {$charset_collate};
SQL;
	}
	
	/**
	 * Return a list of SaleXpresso tables. Used to make sure all SXP tables are dropped when uninstalling the plugin
	 * in a single site or multi site environment.
	 *
	 * @return array SaleXpresso tables.
	 */
	public static function get_tables() {
		global $wpdb;
		
		$tables = [
			"{$wpdb->prefix}sxp_analytics",
			"{$wpdb->prefix}sxp_abandon_cart",
		];
		
		/**
		 * Add table name to the list of known SaleXpresso tables.
		 *
		 * If SaleXpresso plugins need to add new tables, they can inject them here.
		 *
		 * @param array $tables An array of SaleXpresso-specific database table names.
		 */
		$tables = apply_filters( 'salexpresso_install_get_tables', $tables );
		
		return $tables;
	}
	
	/**
	 * Drop WooCommerce tables.
	 *
	 * @return void
	 */
	public static function drop_tables() {
		global $wpdb;
		
		$tables = self::get_tables();
		// phpcs:disable
		foreach ( $tables as $table ) {
			$wpdb->query( "DROP TABLE IF EXISTS {$table}" );
		}
		// phpcs:enable
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
	 * Uninstall tables when MU blog is deleted.
	 *
	 * @param array $tables List of tables that will be deleted by WP.
	 *
	 * @return string[]
	 */
	public static function wpmu_drop_tables( $tables ) {
		return array_merge( $tables, self::get_tables() );
	}
	
	/**
	 * Create Custom Roles.
	 *
	 * @return void
	 */
	public static function create_roles_caps() {
		
		$exclude_session_tracking = get_option( 'salexpresso_st_exclude_role' );
		$exclude_abundant_cart    = get_option( 'salexpresso_ac_exclude_role' );
		
		$roles = wp_roles();
		if ( ! empty( $exclude_session_tracking ) ) {
			foreach ( $exclude_session_tracking as $role => $exclude ) {
				$roles->add_cap( $role, 'disable_session_tracking', $exclude === 'yes' );
			}
		}
		
		if ( ! empty( $exclude_abundant_cart ) ) {
			foreach ( $exclude_abundant_cart as $role => $exclude ) {
				$roles->add_cap( $role, 'disable_abundant_cart', $exclude === 'yes' );
			}
		}
	}
	
	/**
	 * Remove Roles
	 *
	 * @return void
	 */
	public static function remove_roles_caps() {
		$roles = wp_roles();
		
		foreach ( $roles->roles as $role_key => $role_value ) {
			$roles->remove_cap( $role_key, 'track_session' );
			$roles->remove_cap( $role_key, 'track_abundant_cart' );
		}
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
	private static function update_sxp_version() {
		$old = get_option( 'salexpresso_version', false );
		if ( false !== $old ) {
			update_option( 'salexpresso_updated_form', $old );
		}
		delete_option( 'salexpresso_version' );
		add_option( 'salexpresso_version', SXP()->version );
	}
	
	/**
	 * Is a DB update needed?
	 *
	 * @return boolean
	 */
	public static function needs_db_update() {
		// @TODO update on next release.
		return false;
	}
	/**
	 * See if we need to show or run database updates during install.
	 *
	 * @return void
	 */
	private static function maybe_update_db_version() {
		if ( self::needs_db_update() ) {
			if ( apply_filters( 'salexpresso_enable_auto_update_db', false ) ) {
				self::update();
			} else {
				SXP_Admin_Notices::add_notice( 'update', true );
			}
		} else {
			self::update_db_version();
		}
	}
	
	/**
	 * Push all needed DB updates to the queue for processing.
	 *
	 * @return void
	 */
	private static function update() {
		// @TODO update on next release.
	}
	
	/**
	 * Update DB version to current.
	 *
	 * @param string|null $version New SaleXpresso DB version or null.
	 */
	public static function update_db_version( $version = null ) {
		delete_option( 'salexpresso_db_version' );
		add_option( 'salexpresso_db_version', is_null( $version ) ? SXP()->version : $version );
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
		
		$common_files = [
			'htaccess' => [
				'.htaccess',
				'deny from all',
			],
			'index'    => [
				'index.php',
				'<?php\n// Silence is golden.',
			],
		];
		
		$files = [
			[
				'base'    => SXP_UPLOAD_DIR,
				'file'    => $common_files['htaccess'][0],
				'content' => $common_files['htaccess'][1],
			],
			[
				'base'    => SXP_UPLOAD_DIR,
				'file'    => $common_files['index'][0],
				'content' => $common_files['index'][1],
			],
			[
				'base'    => SXP_CACHE_DIR,
				'file'    => $common_files['htaccess'][0],
				'content' => $common_files['htaccess'][1],
			],
			[
				'base'    => SXP_CACHE_DIR,
				'file'    => $common_files['index'][0],
				'content' => $common_files['index'][1],
			],
			[
				'base'    => SXP_LOG_DIR,
				'file'    => $common_files['htaccess'][0],
				'content' => $common_files['htaccess'][1],
			],
			[
				'base'    => SXP_LOG_DIR,
				'file'    => $common_files['index'][0],
				'content' => $common_files['index'][1],
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
	
	/**
	 * Show action links on the plugin screen.
	 *
	 * @param string[] $links Plugin Action links.
	 *
	 * @return array
	 */
	public static function plugin_action_links( $links ) {
		$links['settings'] = '<a href="' . admin_url( 'admin.php?page=sxp-settings' ) . '" aria-label="' . esc_attr__( 'View SaleXpresso settings', 'salexpresso' ) . '">' . esc_html__( 'Settings', 'salexpresso' ) . '</a>';
		
		return $links;
	}
	
	/**
	 * Show row meta on the plugin screen.
	 *
	 * @param mixed $links Plugin Row Meta.
	 * @param mixed $file  Plugin Base file.
	 *
	 * @return array
	 */
	public static function plugin_row_meta( $links, $file ) {
		if ( SXP_PLUGIN_BASENAME === $file ) {
			$row_meta = [
				'docs'    => '<a href="' . esc_url( apply_filters( 'salexpresso_docs_url', 'https://docs.salexpresso.com/documentation/' ) ) . '" aria-label="' . esc_attr__( 'View WooCommerce documentation', 'salexpresso' ) . '">' . esc_html__( 'Docs', 'salexpresso' ) . '</a>',
				'apidocs' => '<a href="' . esc_url( apply_filters( 'salexpresso_apidocs_url', 'https://docs.salexpresso.com/' ) ) . '" aria-label="' . esc_attr__( 'View WooCommerce API docs', 'salexpresso' ) . '">' . esc_html__( 'API docs', 'salexpresso' ) . '</a>',
				'support' => '<a href="' . esc_url( apply_filters( 'salexpresso_support_url', 'https://salexpresso.com/my-account/tickets/' ) ) . '" aria-label="' . esc_attr__( 'Visit premium customer support', 'salexpresso' ) . '">' . esc_html__( 'Premium support', 'salexpresso' ) . '</a>',
			];
			
			return array_merge( $links, $row_meta );
		}
		
		return (array) $links;
	}
}

// End of file class-sxp-install.php.
