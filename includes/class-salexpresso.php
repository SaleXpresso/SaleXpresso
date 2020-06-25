<?php
/**
 * SaleXpresso
 *
 * @package SaleXpresso
 * @version 1.0.0
 * @since   1.0.0
 */

namespace SaleXpresso;

if ( ! defined( 'ABSPATH' ) ) {
	header( 'Status: 403 Forbidden' );
	header( 'HTTP/1.1 403 Forbidden' );
	die();
}

/**
 * Main SaleXpresso class
 *
 * @class SaleXpresso
 */
final class SaleXpresso {
	
	/**
	 * SaleXpresso version.
	 *
	 * @var string
	 */
	public $version = '1.0.0';
	
	/**
	 * Debug Mode Flag.
	 *
	 * @var bool
	 */
	protected $debug = false;
	
	/**
	 * Debug Data Log Flag.
	 *
	 * @var bool
	 */
	protected $debug_log = false;
	
	/**
	 * Script Debug Flag.
	 *
	 * @var bool
	 */
	protected $debug_script = false;
	
	/**
	 * Debug Data Display Flag.
	 *
	 * @var bool
	 */
	protected $debug_display = false;
	
	/**
	 * The single instance of the class.
	 *
	 * @var SaleXpresso
	 * @since 1.0.0
	 */
	protected static $instance = null;
	
	/**
	 * Single instance of SXP_Views class.
	 *
	 * @var SXP_Views
	 */
	protected $views;
	
	/**
	 * Main SaleXpresso Instance.
	 *
	 * Ensures only one instance of SaleXpresso is loaded or can be loaded.
	 *
	 * @since 1.0.0
	 * @static
	 * @see SXP()
	 *
	 * @return SaleXpresso - Main instance.
	 */
	public static function get_instance() {
		if ( is_null( self::$instance ) ) {
			self::$instance = new self();
		}
		
		return self::$instance;
	}
	
	/**
	 * Cloning is forbidden.
	 *
	 * @since 1.0.0
	 */
	public function __clone() {
		sxp_doing_it_wrong( __METHOD__, __( 'Cloning is forbidden.', 'salexpresso' ), '2.1' );
	}
	
	/**
	 * Unserializing instances of this class is forbidden.
	 *
	 * @since 1.0.0
	 */
	public function __wakeup() {
		sxp_doing_it_wrong( __METHOD__, __( 'Unserializing instances of this class is forbidden.', 'salexpresso' ), '2.1' );
	}
	
	/**
	 * Auto-load in-accessible properties on demand.
	 *
	 * @param mixed $key Key name.
	 * @return mixed
	 */
	public function __get( $key ) {
		return null;
	}
	
	/**
	 * SaleXpresso Constructor.
	 */
	private function __construct() {
		$this->debug         = ( defined( 'WP_DEBUG' ) && WP_DEBUG );
		$this->debug_log     = $this->debug && ( defined( 'WP_DEBUG_LOG' ) && WP_DEBUG_LOG );
		$this->debug_script  = $this->debug && ( defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG );
		$this->debug_display = $this->debug && ( defined( 'WP_DEBUG_DISPLAY' ) && WP_DEBUG_DISPLAY );
		$this->define_constants();
		$this->register_tables();
		$this->includes();
		$this->init_hooks();
	}
	
	/**
	 * Trigger SXP Loaded once WP finished loading all plugins
	 *
	 * @return void
	 */
	public function on_load() {
		do_action( 'salexpresso_loaded' );
	}
	
	/**
	 * Fire up everything
	 *
	 * @return void
	 */
	private function init_hooks() {
		// the installer.
		register_activation_hook( SXP_PLUGIN_FILE, [ 'SaleXpresso\SXP_Install', 'install' ] );
		register_shutdown_function( [ $this, 'log_errors' ] );
		SXP_Assets::get_instance();
		SXP_Install::init();
		SXP_Admin_Menus::get_instance();
		SXP_Settings::get_instance();
		$this->views = SXP_Views::get_instance( $this );
		add_action( 'plugins_loaded', [ $this, 'on_load' ], -1 );
		add_action( 'admin_notices', [ $this, 'dependencies_notice' ] );
		add_action( 'init', [ $this, 'init' ], 0 );
		// Fix table names on blog switch.
		add_action( 'switch_blog', [ $this, 'register_tables' ], 0 );
	}
	
	/**
	 * The Init.
	 *
	 * @return void
	 */
	public function init() {
		if ( ! $this->check_assets() ) {
			return;
		}
		// Pre initialization action.
		do_action( 'before_salexpresso_init' );
		
		// Set up localisation.
		$this->load_plugin_textdomain();
		
		SXP_Admin_Notices::init();
		SXP_Admin_Notices::reset_admin_notices();
		
		// Init action.
		do_action( 'salexpresso_init' );
	}
	
	/**
	 * Get the view instance.
	 *
	 * @return SXP_Views
	 */
	public function views() {
		if ( is_null( $this->views ) ) {
			if ( $this->debug_log ) {
				error_log( sprintf( 'Calling %s too early. This should be called after `salexpresso_loaded` or `salexpresso_init`', __METHOD__ ) ); // phpcs:ignore WordPress.PHP.DevelopmentFunctions.error_log_error_log
			}
			return null;
		}
		return $this->views;
	}
	
	/**
	 * Load Localisation files.
	 *
	 * Note: the first-loaded translation file overrides any following ones if the same translation is present.
	 *
	 * @return void
	 */
	public function load_plugin_textdomain() {
		if ( function_exists( 'determine_locale' ) ) {
			$locale = determine_locale();
		} else {
			// @todo Remove when start supporting WP 5.0 or later.
			$locale = is_admin() ? get_user_locale() : get_locale();
		}
		
		$locale = apply_filters( 'plugin_locale', $locale, 'salexpresso' ); // phpcs:ignore WordPress.NamingConventions.PrefixAllGlobals.NonPrefixedHooknameFound
		
		unload_textdomain( 'salexpresso' );
		load_textdomain( 'salexpresso', WP_LANG_DIR . '/salexpresso/salexpresso-' . $locale . '.mo' );
		load_plugin_textdomain( 'salexpresso', false, plugin_basename( dirname( SXP_PLUGIN_FILE ) ) . '/i18n/languages' );
	}
	
	/**
	 * Ensures fatal errors are logged so they can be picked up in the status report.
	 *
	 * @return void
	 */
	public function log_errors() {
		$error = error_get_last();
		if (
			$error &&
			in_array(
				$error['type'],
				array( E_ERROR, E_PARSE, E_COMPILE_ERROR, E_USER_ERROR, E_RECOVERABLE_ERROR ),
				true
			)
		) {
			/**
			 * Using WC Error Logger
			 *
			 * @TODO Create our own logger.
			 */
			$logger = sxp_get_logger();
			$logger->critical(
			/* translators: 1: error message 2: file name and path 3: line number */
				sprintf( __( '%1$s in %2$s on line %3$s', 'salexpresso' ), $error['message'], $error['file'], $error['line'] ) . PHP_EOL,
				array(
					'source' => 'sxp-fatal-errors',
				)
			);
			
			/**
			 * Fires before shutting down ... with errors
			 *
			 * @param array $error
			 */
			do_action( 'salexpresso_shutdown_error', $error );
		}
	}
	
	/**
	 * Define WC Constants.
	 */
	private function define_constants() {
		if ( ! defined( 'SXP_VERSION' ) ) {
			/**
			 * Plugin Version
			 *
			 * @var string
			 */
			define( 'SXP_VERSION', $this->version );
		}
		
		if ( ! defined( 'SXP_ABSPATH' ) ) {
			/**
			 * Plugin path with trailing slash
			 *
			 * @var string
			 */
			define( 'SXP_ABSPATH', dirname( SXP_PLUGIN_FILE ) . '/' );
		}
		
		if ( ! defined( 'SXP_PLUGIN_BASENAME' ) ) {
			/**
			 * Plugin basename
			 *
			 * @var string
			 */
			define( 'SXP_PLUGIN_BASENAME', plugin_basename( SXP_PLUGIN_FILE ) );
		}
		
		if ( ! defined( 'SXP_UPLOAD_DIR' ) ) {
			$sxp_upload_dir = wp_upload_dir( null, false );
			/**
			 * Base Writable (?) Directory to use.
			 *
			 * @var string
			 */
			define( 'SXP_UPLOAD_DIR', $sxp_upload_dir['basedir'] . '/salexpresso' );
		}
		
		if ( ! defined( 'SXP_LOG_DIR' ) ) {
			/**
			 * Log Directory To Use
			 *
			 * @var string
			 */
			define( 'SXP_LOG_DIR', SXP_UPLOAD_DIR . '/sxp-logs' );
		}
		
		if ( ! defined( 'SXP_CACHE_DIR' ) ) {
			define( 'SXP_CACHE_DIR', SXP_UPLOAD_DIR . '/cache' );
		}
		
		if ( ! defined( 'SXP_NOTICE_MIN_PHP_VERSION' ) ) {
			/**
			 * Minimum PHP Version
			 *
			 * @var string
			 */
			define( 'SXP_NOTICE_MIN_PHP_VERSION', '7.2.5' );
		}
		
		if ( ! defined( 'SXP_NOTICE_MIN_WP_VERSION' ) ) {
			/**
			 * Minimum WP Version
			 *
			 * @var string
			 */
			define( 'SXP_NOTICE_MIN_WP_VERSION', '5.0' );
		}
		
		if ( ! defined( 'SXP_NOTICE_MIN_WC_VERSION' ) ) {
			/**
			 * Minimum WC Version
			 *
			 * @var string
			 */
			define( 'SXP_NOTICE_MIN_WC_VERSION', '4.0.0' );
		}
		
		if ( ! defined( 'SXP_PHP_WP_MIN_REQUIREMENTS_NOTICE' ) ) {
			/**
			 * Min PHP WP Notice Slug
			 *
			 * @var string
			 */
			define( 'SXP_PHP_WP_MIN_REQUIREMENTS_NOTICE', 'sxp_min_wp_php_requirements_' . SXP_NOTICE_MIN_PHP_VERSION . '_' . SXP_NOTICE_MIN_WP_VERSION );
		}
		
		if ( ! defined( 'SXP_WC_MIN_REQUIREMENTS_NOTICE' ) ) {
			/**
			 * Plugin WC Notice Slug
			 *
			 * @var string
			 */
			define( 'SXP_WC_MIN_REQUIREMENTS_NOTICE', 'sxp_min_wc_requirements_' . SXP_NOTICE_MIN_PHP_VERSION . '_' . SXP_NOTICE_MIN_WP_VERSION );
		}
	}
	
	/**
	 * Register Tables with WordPress db object
	 *
	 * @return void
	 */
	public function register_tables() {
		global $wpdb;
		
		$tables = [
			'user_term_relationships' => 'user_term_relationships',
		];
		
		foreach ( $tables as $k => $v ) {
			$wpdb->$k       = $wpdb->prefix . $v;
			$wpdb->tables[] = $v;
		}
	}
	
	/**
	 * Returns true if the request is a non-legacy REST API request.
	 *
	 * @todo: replace this function once core WP function is available: https://core.trac.wordpress.org/ticket/42061.
	 *
	 * @return bool
	 */
	public function is_rest_api_request() {
		if ( empty( $_SERVER['REQUEST_URI'] ) ) {
			return false;
		}
		
		$rest_prefix         = trailingslashit( rest_get_url_prefix() );
		$is_rest_api_request = ( false !== strpos( $_SERVER['REQUEST_URI'], $rest_prefix ) ); // phpcs:disable WordPress.Security.ValidatedSanitizedInput.MissingUnslash, WordPress.Security.ValidatedSanitizedInput.InputNotSanitized
		
		return apply_filters( 'salexpresso_is_rest_api_request', $is_rest_api_request );
	}
	
	/**
	 * What type of request is this?
	 *
	 * @param  string $type admin, ajax, cron or frontend.
	 * @return bool
	 */
	private function is_request( $type ) {
		switch ( $type ) {
			case 'admin':
				return is_admin();
			case 'ajax':
				return defined( 'DOING_AJAX' );
			case 'cron':
				return defined( 'DOING_CRON' );
			case 'frontend':
				return ( ! is_admin() || defined( 'DOING_AJAX' ) ) && ! defined( 'DOING_CRON' ) && ! $this->is_rest_api_request();
		}
		return false;
	}
	
	/**
	 * Include required core files for both admin and frontend
	 *
	 * @return void
	 */
	private function includes() {
		
		$this->load_file( SXP_ABSPATH . 'vendor/autoload.php', true, true );
		
		require_once 'deprecated-functions.php';
		require_once 'user-taxonomy-helper.php';
		require_once 'helper.php';
		require_once 'hooks.php';
		require_once 'classes/class-sxp-file-handler.php';
		require_once 'interfaces/interface-sxp-admin-page.php';
		require_once 'abstracts/class-sxp-admin-page.php';
		require_once 'classes/class-sxp-admin-notices.php';
		require_once 'classes/class-sxp-admin-menus.php';
		require_once 'classes/class-sxp-assets.php';
		require_once 'classes/class-sxp-admin-settings-page.php';
		require_once 'classes/class-sxp-install.php';
		require_once 'classes/class-sxp-post-types.php';
		require_once 'classes/class-sxp-user-taxonomy.php';
		require_once 'classes/class-sxp-rules.php';
		require_once 'classes/class-sxp-views.php';
		require_once 'classes/class-sxp-settings.php';
		
		// Core.
		require_once 'classes/customer/class-sxp-customer-list-table.php';
		require_once 'classes/customer/class-sxp-customer-group-table.php';
		require_once 'classes/customer/class-sxp-customer-group-rule.php';
		require_once 'classes/customer/class-sxp-customer-type-table.php';
		require_once 'classes/customer/class-sxp-customer-type-rule.php';
		require_once 'classes/customer/class-sxp-customer-tag-table.php';
		require_once 'classes/customer/class-sxp-customer-tag-rule.php';
		require_once 'classes/customer/class-sxp-customer-profile-table.php';
		require_once 'classes/product/class-sxp-product-list-table.php';
		require_once 'classes/order/class-sxp-order-list-table.php';
		require_once 'classes/order/class-sxp-order-single-table.php';
		require_once 'classes/campaign/class-sxp-campaign-list-table.php';
		require_once 'classes/campaign/class-sxp-campaign-new-table.php';
		
		// Admin pages.
		require_once 'classes/dashboard/class-sxp-dashboard-page.php';
		require_once 'classes/customer/class-sxp-customers-page.php';
		require_once 'classes/product/class-sxp-product-page.php';
		require_once 'classes/order/class-sxp-order-page.php';
		require_once 'classes/campaign/class-sxp-campaign-page.php';
		require_once 'classes/settings/class-sxp-settings-page.php';
		require_once 'classes/settings/class-sxp-status-page.php';
		
	}
	
	/**
	 * Get the plugin url.
	 *
	 * @return string
	 */
	public function plugin_url() {
		return untrailingslashit( plugins_url( '/', SXP_PLUGIN_FILE ) );
	}
	
	/**
	 * Get the plugin path.
	 *
	 * @return string
	 */
	public function plugin_path() {
		return untrailingslashit( plugin_dir_path( SXP_PLUGIN_FILE ) );
	}
	
	/**
	 * Check if plugin assets are built and minified
	 *
	 * @return bool
	 */
	public function check_assets() {
		if ( ! file_exists( SXP_ABSPATH . 'vendor/autoload.php' ) ) {
			if ( defined( 'WP_DEBUG' ) && WP_DEBUG ) {
				error_log( 'Vendor Autoloader Missing' ); // phpcs:ignore WordPress.PHP.DevelopmentFunctions.error_log_error_log
			}
			return false;
		}
		
		if (
			// Check if we have compiled CSS.
			! file_exists( SXP_ABSPATH . 'assets/css/admin.css' )
			||
			// Check if we have minified JS.
			! file_exists( SXP_ABSPATH . 'assets/js/admin.js' )
		) {
			if ( defined( 'WP_DEBUG' ) && WP_DEBUG ) {
				error_log( 'Vendor Autoloader Missing' ); // phpcs:ignore WordPress.PHP.DevelopmentFunctions.error_log_error_log
			}
			return false;
		}
		
		return true;
	}
	
	/**
	 * Output a admin notice when build dependencies not met.
	 *
	 * @return void
	 */
	public function dependencies_notice() {
		if ( $this->check_assets() ) {
			return;
		}
		
		$message_one = sprintf(
			/* translators: 1: opening code tag 3: closing code tag */
			esc_html__( 'You have installed a development version of SaleXpresso which requires files to be built and minified. From the plugin directory, execute %1$snpm run build%2$s to build and minify assets.', 'salexpresso' ),
			'<code>',
			'</code>'
		);
		$message_two = sprintf(
			/* translators: 1: is a link to WordPress.org Repository 2: is a link to the GitHub Repository release page. 3: closing link */
			esc_html__( 'Or you can download a pre-built version of the plugin from the %1$sWordPress.org repository%3$s or by visiting %2$sthe releases page in the GitHub repository%3$s.', 'salexpresso' ),
			'<a href="https://wordpress.org/plugins/salexpresso/" target="_blank" rel="noopener noreferrer">',
			'<a href="https://github.com/salexpresso/salexpresso/releases" target="_blank" rel="noopener noreferrer">',
			'</a>'
		);
		printf( '<div class="error"><p>%s %s</p></div>', $message_one, $message_two ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
	}
	
	/**
	 * The Internal Autoloader
	 *
	 * @param string $class_name Class to load.
	 * @return void
	 */
	public function autoload( $class_name ) {
		
		/* don't autoload this class. */
		if ( __CLASS__ === $class_name ) {
			return;
		}
		/* Only autoload classes from this namespace */
		if ( false === strpos( $class_name, __NAMESPACE__ ) ) {
			return;
		}
		
		$base_path   = SXP_ABSPATH;
		$base_ns     = __NAMESPACE__;
		$file_prefix = 'class-';
		
		if ( strpos( $class_name, 'Interfaces', 12 ) ) {
			$base_path  .= 'includes/interfaces/';
			$base_ns    .= '\\Interfaces';
			$file_prefix = 'interface-';
			$class_name  = str_replace( '_Interface', '', $class_name );
		} elseif ( strpos( $class_name, 'Abstracts', 12 ) ) {
			$base_path .= 'includes/abstracts/';
			$base_ns   .= '\\Abstracts';
		} elseif ( strpos( $class_name, 'Models', 12 ) ) {
			$base_path .= 'includes/model/';
			$base_ns   .= '\\Models';
		} elseif ( strpos( $class_name, 'Controllers', 12 ) ) {
			$base_path .= 'includes/controllers/';
			$base_ns   .= '\\Controllers';
		} elseif ( strpos( $class_name, 'Libraries', 12 ) ) {
			$base_path .= 'includes/libraries/';
			$base_ns   .= '\\Libraries';
		} else {
			$base_path .= 'includes/classes/';
		}
		/* Remove namespace from class name */
		$class_file = str_replace( $base_ns . '\\', '', $class_name );
		
		/* Convert class name format to file name format */
		$class_file = strtolower( $class_file );
		$class_file = str_replace( '_', '-', $class_file );
		
		/* Convert sub-namespaces into directories */
		$class_path = explode( '\\', $class_file );
		$class_file = array_pop( $class_path );
		$class_path = implode( '/', $class_path );
		
		if ( ! empty( $class_path ) ) {
			$class_path .= '/';
		}
		
		/* Load the class */
		$this->load_file( $base_path . $class_path . $file_prefix . $class_file . '.php', true, true, true );
	}
	
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
	public function load_file( $file, $if_exists = true, $required = false, $once = false ) {
		if ( true === $if_exists ) {
			if ( ! file_exists( $file ) ) {
				return false;
			}
		}
		if ( false === $once ) {
			/** @noinspection PhpIncludeInspection */ // phpcs:ignore Generic.Commenting.DocComment.MissingShort
			return ! $required ? include $file : require $file;
		} else {
			/** @noinspection PhpIncludeInspection */ // phpcs:ignore Generic.Commenting.DocComment.MissingShort
			return ! $required ? include_once $file : require_once $file;
		}
	}
	
	/**
	 * Check debug flag.
	 *
	 * @return bool
	 */
	public function is_debugging() {
		return $this->debug;
	}
	
	/**
	 * Check debug log flag.
	 *
	 * @return bool
	 */
	public function is_debug_log() {
		return $this->debug_log;
	}
	
	/**
	 * Check debug script flag.
	 *
	 * @return bool
	 */
	public function is_debug_script() {
		return $this->debug_script;
	}
	
	/**
	 * Check debug display flag.
	 *
	 * @return bool
	 */
	public function is_debug_display() {
		return $this->debug_display;
	}
}

// End of file class-salexpresso.php.
