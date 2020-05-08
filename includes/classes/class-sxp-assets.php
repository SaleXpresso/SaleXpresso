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
 * Class SXP_Assets
 *
 * @package SaleXpresso
 */
class SXP_Assets {
	
	/**
	 * The single instance of the class.
	 *
	 * @var SXP_Assets
	 * @since 1.0.0
	 */
	protected static $instance = null;
	
	/**
	 * Debugging status
	 *
	 * @var bool
	 */
	protected $is_debugging = false;
	/**
	 * File name suffix based on debugging status
	 *
	 * @var string
	 */
	protected $file_suffix = '';
	
	/**
	 * Main SaleXpresso Instance.
	 *
	 * Ensures only one instance of SaleXpresso is loaded or can be loaded.
	 *
	 * @since 1.0.0
	 * @return SXP_Assets - Main instance.
	 */
	public static function get_instance() {
		if ( is_null( self::$instance ) ) {
			self::$instance = new self();
		}
		return self::$instance;
	}
	
	/**
	 * SXP_Assets constructor.
	 */
	private function __construct() {
		$this->is_debugging = ( defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG );
		$this->file_suffix  = $this->is_debugging ? '.min' : '';
		add_action( 'admin_enqueue_scripts', [ $this, 'admin_scripts' ], 10, 1 );
		add_action( 'wp_enqueue_scripts', [ $this, 'public_scripts' ], 10 );
	}
	
	/**
	 * Admin Scripts
	 *
	 * @param string $hook_suffix The current admin page.
	 */
	public function admin_scripts( $hook_suffix ) {
		wp_register_script(
			'sxp-admin',
			$this->get_url( 'admin' . $this->file_suffix . '.js' ),
			[
				'jquery',
				'moment',
				'wp-api-fetch',
				'wp-data',
				'wp-element',
				'wp-hooks',
				'wp-html-entities',
				'wp-i18n',
				'wp-keycodes',
			],
			$this->get_file_version( 'admin' . $this->file_suffix . '.js' ),
			true
		);
		
		$js_opts = apply_filters( 'salexpresso_admin_js_opts', [] );
		wp_enqueue_script('moment', 'https://cdn.jsdelivr.net/momentjs/latest/moment.min.js', 'jquery', '3.4', 'true');
		wp_enqueue_script('daterangepicker', 'https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js', 'jquery', '3.4', 'true');
		wp_enqueue_script('feather-icon', 'https://unpkg.com/feather-icons', 'jquery', '4.28.0', 'true');
		wp_enqueue_script( 'sxp-admin' );
		wp_localize_script( 'sxp-admin', 'SaleXpresso', $js_opts );
		wp_enqueue_style(
			'sxp-admin',
			$this->get_url( 'admin' . $this->file_suffix . '.css' ),
			[],
			$this->get_file_version( 'admin' . $this->file_suffix . '.css' )
		);
		wp_enqueue_style( 'sxp-google-font', 'https://fonts.googleapis.com/css?family=Public+Sans:400,700&display=swap', '', '1.0.0' );
		wp_enqueue_style( 'sxp-fontawesome', 'https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css', '', '4.7.0' );
	}
	
	/**
	 * Public Scripts
	 */
	public function public_scripts() {
		wp_register_script(
			'sxp-scripts',
			$this->get_url( 'scripts' . $this->file_suffix . '.js' ),
			[
				'jquery',
			],
			$this->get_file_version( 'scripts' . $this->file_suffix . '.js' ),
			true
		);
		$js_opts = apply_filters( 'salexpresso_js_opts', [] );
		wp_localize_script( 'sxp-scripts', 'SaleXpresso', $js_opts );
		wp_enqueue_script( 'sxp-scripts' );
		wp_enqueue_style( 'sxp-styles', $this->get_url( 'styles' . $this->file_suffix . '.css' ), [], $this->get_file_version( 'styles' . $this->file_suffix . '.css' )
		);
	}
	
	/**
	 * Gets the file modified time as a cache buster if we're in dev mode, or the plugin version otherwise.
	 *
	 * @param string $file Local path to the file.
	 * @return string The cache buster value to use for the given file.
	 */
	public function get_file_version( $file ) {
		if ( defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ) {
			$file = trim( $file, '/' );
			
			return filemtime( $this->get_full_path( $file ) );
		}
		
		return SXP_VERSION;
	}
	
	/**
	 * Gets the URL to an asset file.
	 *
	 * @param  string $file name.
	 * @return string URL to asset.
	 */
	public function get_url( $file ) {
		return plugins_url( $this->get_path( $file ) . $file, SXP_PLUGIN_FILE );
	}
	
	/**
	 * Gets the path for the asset depending on file type.
	 *
	 * @param  string $file name.
	 * @return string|bool Folder path of asset or false.
	 */
	private function get_path( $file ) {
		$assets = 'assets/';
		if ( '.css' === substr( $file, -4 ) ) {
			return $assets . 'css/';
		} elseif ( '.js' === substr( $file, -3 ) ) {
			return $assets . 'js/';
		} elseif (
			in_array( substr( $file, -4 ), [ '.jpg', '.png', '.gif', '.svg' ] ) ||
			in_array( substr( $file, -5 ), [ '.svgz', '.webp' ] )
		) {
			return $assets . 'images/';
		} else {
			return false;
		}
	}

	/**
	 * Get full path.
	 *
	 * @param string $file name.
	 *
	 * @return bool|string
	 */
	private function get_full_path( $file ) {
		$path = $this->get_path( $file );
		if ( false !== $path ) {
			$path = SXP_ABSPATH . $path;
		}
		return $path;
	}
}

// End of file class-sxp-assets.php.
