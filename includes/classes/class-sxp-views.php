<?php
/**
 * Views
 *
 * @package SaleXpresso
 * @version 1.0.0
 * @since   SaleXpresso v1.0.0
 */

namespace SaleXpresso;

use Pug\Pug;

if ( ! defined( 'ABSPATH' ) ) {
	header( 'Status: 403 Forbidden' );
	header( 'HTTP/1.1 403 Forbidden' );
	die();
}

/**
 * Class SXP_Views
 *
 * @package SaleXpresso
 */
class SXP_Views {
	
	/**
	 * Singleton instance.
	 *
	 * @var SXP_Views
	 */
	protected static $instance;
	
	/**
	 * SaleXpresso.
	 *
	 * @var SaleXpresso
	 */
	private $main;
	
	/**
	 * Debugging Flag.
	 *
	 * @var bool
	 */
	protected $debug;
	
	/**
	 * The Pug Engine.
	 *
	 * @var Pug
	 */
	protected $engine;
	
	/**
	 * Get Singleton instance of SXP_Views.
	 *
	 * @param SaleXpresso $main main instance of SaleXpresso.
	 *
	 * @return SXP_Views
	 */
	public static function get_instance( SaleXpresso $main ) {
		if ( is_null( self::$instance ) ) {
			self::$instance = new self( $main );
		}
		
		return self::$instance;
	}
	
	/**
	 * SXP_Views constructor.
	 *
	 * @param SaleXpresso $main main instance of SaleXpresso.
	 */
	private function __construct( SaleXpresso $main ) {
		$this->main  = $main;
		$this->debug = $this->main->is_debugging();
		// Initialize pug instance.
		$this->engine = new Pug(
			[
				'environment'        => $this->debug ? 'development' : 'production',
				'paths'              => [ $this->get_base_dir() ],
				'basedir'            => $this->get_base_dir(),
				'cache'              => $this->get_cache_dir(),
				'extension'          => '.pug',
				'expressionLanguage' => 'php',
				'pretty'             => ! $this->debug,
				'not_found_template' => '',
			]
		);
		
		// engine - > requirements.
	}
	
	/**
	 * Render any vew (pug) file and return or print the content.
	 *
	 * @param string $view  View file name without extension.
	 * @param array  $args  [optional] variables to pass to the view file.
	 * @param bool   $echo return or print the output..
	 *
	 * @return string|void return or print rendered html
	 */
	public function render_file( $view, $args = [], $echo = false ) {
		try {
			if ( $echo ) {
				$this->engine->display( $view, $args );
				return;
			}
			return $this->engine->renderFile( $view, $args );
		} catch ( \Exception $e ) {
			if ( $this->main->is_debug_log() ) {
				error_log( $e->getMessage() ); // phpcs:ignore WordPress.PHP.DevelopmentFunctions.error_log_error_log
			}
			return false;
		}
	}
	
	/**
	 * Render any vew (pug) file and print the content.
	 *
	 * @param string $view  View file name without extension.
	 * @param array  $args  [optional] variables to pass to the view file.
	 *
	 * @return void
	 */
	public function display_file( $view, $args = [] ) {
		$this->render_file( $view, $args );
	}
	
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
	public function render( $view, $args = [], $path = '', $echo = false ) {
		try {
			$file = $this->get_view( $view, $path );
			if ( ! $file ) {
				if ( $this->debug ) {
					error_log( 'Invalid View File. Unable to load ' . $view . ' from ' . $path ); // phpcs:ignore WordPress.PHP.DevelopmentFunctions.error_log_error_log
				}
				return;
			}
			return $this->render_file( $file, $args, $echo );
		} catch ( \Exception $e ) {
			if ( $this->main->is_debug_log() ) {
				error_log( $e->getMessage() ); // phpcs:ignore WordPress.PHP.DevelopmentFunctions.error_log_error_log
			}
			return false;
		}
	}
	
	/**
	 * Render view (pug) files from the includes directory and print the content.
	 *
	 * @param string $view  View file name without extension.
	 * @param array  $args  [optional] variables to pass to the view file.
	 * @param string $path  [optional] path to find the view file relative to includes/views.
	 *
	 * @return void
	 */
	public function display( $view, $args = [], $path ) {
		$this->render( $view, $args, $path, true );
	}
	
	/**
	 * Get View File
	 *
	 * @param string $view file name.
	 * @param string $path file path relative to includes/views/.
	 *
	 * @return string|false
	 */
	private function get_view( $view, $path = '' ) {
		$view = unleadingslashit( $view );
		$view = untrailingslashit( $view );
		$path = unleadingslashit( $path );
		$path = untrailingslashit( $path );
		if ( false === strpos( $view, '.pug' ) ) {
			$view .= '.pug';
		}
		$view = SXP_ABSPATH . 'includes/views/' . $path . '/' . $view;
		if ( ! file_exists( $view ) ) {
			return false;
		}
		return $view;
	}
	
	/**
	 * Return the cache directory path.
	 *
	 * @return false|string
	 */
	public function get_cache_dir() {
		return sxp_get_upload_path( 'cache', true );
	}
	
	/**
	 * Return the cache directory path.
	 *
	 * @return false|string
	 */
	public function get_base_dir() {
		return SXP_ABSPATH . 'views/';
	}
}

// End of file class-sxp-admin-page.php.
