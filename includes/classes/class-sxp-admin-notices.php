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
 * Class SXP_Admin_Notices
 *
 * @package SaleXpresso
 */
class SXP_Admin_Notices {
	
	/**
	 * Stores notices.
	 *
	 * @var array
	 */
	private static $notices = array();
	
	/**
	 * Array of notices - name => callback.
	 *
	 * @var array
	 */
	private static $core_notices = array(
		'install'                          => 'install_notice',
		SXP_PHP_WP_MIN_REQUIREMENTS_NOTICE => 'min_wp_php_requirements_notice',
		SXP_WC_MIN_REQUIREMENTS_NOTICE     => 'min_wc_requirements_notice',
		
	);
	
	/**
	 * Initialize.
	 */
	public static function init() {
		self::$notices = get_option( 'salexpresso_admin_notices', array() );
		add_action( 'switch_theme', array( __CLASS__, 'reset_admin_notices' ) );
		add_action( 'salexpresso_installed', array( __CLASS__, 'reset_admin_notices' ) );
		add_action( 'wp_loaded', array( __CLASS__, 'hide_notices' ) );
		// @TODO: This prevents Action Scheduler async jobs from storing empty list of notices during WC installation.
		// That could lead to OBW not starting and 'Run setup wizard' notice not appearing in WP admin, which we want
		// to avoid.
		if ( ! sxp_is_running_from_async_action_scheduler() ) {
			add_action( 'shutdown', array( __CLASS__, 'store_notices' ) );
		}
		
		if ( current_user_can( 'manage_woocommerce' ) ) {
			add_action( 'admin_print_styles', array( __CLASS__, 'add_notices' ) );
		}
	}
	
	/**
	 * Store notices to DB
	 */
	public static function store_notices() {
		update_option( 'salexpresso_admin_notices', self::get_notices() );
	}
	
	/**
	 * Get notices
	 *
	 * @return array
	 */
	public static function get_notices() {
		return self::$notices;
	}
	
	/**
	 * Remove all notices.
	 */
	public static function remove_all_notices() {
		self::$notices = array();
	}
	
	/**
	 * Reset notices for themes when switched or a new version of WC is installed.
	 */
	public static function reset_admin_notices() {
		self::add_min_version_notice();
	}
	
	/**
	 * Show a notice.
	 *
	 * @param string $name Notice name.
	 * @param bool   $force_save Force saving inside this method instead of at the 'shutdown'.
	 */
	public static function add_notice( $name, $force_save = false ) {
		self::$notices = array_unique( array_merge( self::get_notices(), array( $name ) ) );
		
		if ( $force_save ) {
			// Adding early save to prevent more race conditions with notices.
			self::store_notices();
		}
	}
	
	/**
	 * Remove a notice from being displayed.
	 *
	 * @param string $name Notice name.
	 * @param bool   $force_save Force saving inside this method instead of at the 'shutdown'.
	 */
	public static function remove_notice( $name, $force_save = false ) {
		self::$notices = array_diff( self::get_notices(), array( $name ) );
		delete_option( 'salexpresso_admin_notice_' . $name );
		
		if ( $force_save ) {
			// Adding early save to prevent more race conditions with notices.
			self::store_notices();
		}
	}
	
	/**
	 * See if a notice is being shown.
	 *
	 * @param string $name Notice name.
	 *
	 * @return boolean
	 */
	public static function has_notice( $name ) {
		return in_array( $name, self::get_notices(), true );
	}
	
	/**
	 * Hide a notice if the GET variable is set.
	 */
	public static function hide_notices() {
		if ( isset( $_GET['sxp-hide-notice'] ) && isset( $_GET['_sxp_notice_nonce'] ) ) { // phpcs:ignore
			if ( ! wp_verify_nonce( sanitize_key( wp_unslash( $_GET['_sxp_notice_nonce'] ) ), 'salexpresso_hide_notices_nonce' ) ) {
				wp_die( esc_html__( 'Action failed. Please refresh the page and retry.', 'salexpresso' ) );
			}
			
			if ( ! current_user_can( 'manage_woocommerce' ) ) {
				wp_die( esc_html__( 'You don&#8217;t have permission to do this.', 'salexpresso' ) );
			}
			
			$hide_notice = sanitize_text_field( wp_unslash( $_GET['sxp-hide-notice'] ) );
			
			self::remove_notice( $hide_notice );
			
			update_user_meta( get_current_user_id(), 'dismissed_' . $hide_notice . '_notice', true );
			
			do_action( 'salexpresso_hide_' . $hide_notice . '_notice' );
		}
	}
	
	/**
	 * Add notices + styles if needed.
	 */
	public static function add_notices() {
		$notices = self::get_notices();
		if ( empty( $notices ) ) {
			return;
		}
		$screen          = get_current_screen();
		$screen_id       = $screen ? $screen->id : '';
		$show_on_screens = array( 'dashboard', 'plugins' );
		
		// Notices should only show on salexpresso screens, the main dashboard, and on the plugins screen.
		if ( ! in_array( $screen_id, sxp_get_screen_ids_for_admin_notice(), true ) && ! in_array( $screen_id, $show_on_screens, true ) ) {
			return;
		}
		// phpcs:disable
		
		// wp_enqueue_style( 'salexpresso-activation', plugins_url( '/assets/css/activation.css', WC_PLUGIN_FILE ), array(), Constants::get_constant( 'WC_VERSION' ) );
		//
		// Add RTL support.
		// wp_style_add_data( 'salexpresso-activation', 'rtl', 'replace' );

		// phpcs:enable
		
		foreach ( $notices as $notice ) {
			if ( ! empty( self::$core_notices[ $notice ] ) && apply_filters( 'salexpresso_show_admin_notice', true, $notice ) ) {
				add_action( 'admin_notices', array( __CLASS__, self::$core_notices[ $notice ] ) );
			} else {
				add_action( 'admin_notices', [ __CLASS__, 'output_custom_notices' ] );
			}
		}
	}
	
	/**
	 * Add a custom notice.
	 *
	 * @param string $name        Notice name.
	 * @param string $notice_html Notice HTML.
	 */
	public static function add_custom_notice( $name, $notice_html ) {
		self::add_notice( $name );
		update_option( 'salexpresso_admin_notice_' . $name, wp_kses_post( $notice_html ) );
	}
	
	/**
	 * Output any stored custom notices.
	 */
	public static function output_custom_notices() {
		$notices = self::get_notices();
		
		if ( ! empty( $notices ) ) {
			foreach ( $notices as $notice ) {
				if ( empty( self::$core_notices[ $notice ] ) ) {
					$notice_html = get_option( 'salexpresso_admin_notice_' . $notice );
					
					if ( $notice_html ) {
						?>
						<div id="message" class="updated salexpresso-message">
							<a class="salexpresso-message-close notice-dismiss" href="<?php echo esc_url( wp_nonce_url( add_query_arg( 'sxp-hide-notice', $notice ), 'salexpresso_hide_notices_nonce', '_sxp_notice_nonce' ) ); ?>">
								<span class="screen-reader-text"><?php esc_html_e( 'Dismiss', 'salexpresso' ); ?></span>
							</a>
							<?php echo wp_kses_post( wpautop( $notice_html ) ); ?>
						</div>
						<?php
					}
				}
			}
		}
	}
	
	/**
	 * If we have just installed, show a message with the install pages button.
	 */
	public static function install_notice() {
		?>
		<div id="message" class="updated woocommerce-message wc-connect">
			<p><?php _e( '<strong>Welcome to SaleXpresso</strong> &#8211; You&lsquo;re setup is almost ready :)', 'salexpresso' ); ?></p>
			<p class="submit"><a href="<?php echo esc_url( admin_url( 'admin.php?page=sxp-setup' ) ); ?>" class="button-primary"><?php _e( 'Run the Setup Wizard', 'salexpresso' ); ?></a> <a class="button-secondary skip" href="<?php echo esc_url( wp_nonce_url( add_query_arg( 'wc-hide-notice', 'install' ), 'salexpresso_hide_notices_nonce', '_sxp_notice_nonce' ) ); ?>"><?php _e( 'Skip setup', 'salexpresso' ); ?></a></p>
		</div>
		<?php
	}
	
	/**
	 * Add notice about minimum PHP and WordPress requirement.
	 */
	public static function add_min_version_notice() {
		if ( version_compare( phpversion(), SXP_NOTICE_MIN_PHP_VERSION, '<' ) || version_compare( get_bloginfo( 'version' ), SXP_NOTICE_MIN_WP_VERSION, '<' ) ) {
			self::add_notice( SXP_PHP_WP_MIN_REQUIREMENTS_NOTICE );
			self::add_notice( SXP_WC_MIN_REQUIREMENTS_NOTICE );
		}
	}
	
	/**
	 * Notice about WordPress and PHP minimum requirements.
	 *
	 * @return void
	 */
	public static function min_wp_php_requirements_notice() {
		if ( apply_filters( 'salexpresso_hide_min_wp_php_requirements_nag', get_user_meta( get_current_user_id(), 'dismissed_' . SXP_PHP_WP_MIN_REQUIREMENTS_NOTICE . '_notice', true ) ) ) {
			self::remove_notice( SXP_PHP_WP_MIN_REQUIREMENTS_NOTICE );
			return;
		}
		
		// Get wc version.
		$old_wc_version = self::get_plugin_version( 'woocommerce/woocommerce.php');
		// Version status.
		$old_php = version_compare( phpversion(), SXP_NOTICE_MIN_PHP_VERSION, '<' );
		$old_wp  = version_compare( get_bloginfo( 'version' ), SXP_NOTICE_MIN_WP_VERSION, '<' );
		$old_wc  = version_compare( $old_wc_version, SXP_NOTICE_MIN_WC_VERSION, '<' );
		
		// Both PHP and WordPress up to date version => no notice.
		if ( ! $old_php && ! $old_wp && ! $old_wc ) {
			return;
		}
		$msg = '';
		if ( $old_php && $old_wp ) {
			$msg = sprintf(
				/* translators: 1: Minimum PHP version 2: Minimum WordPress version */
				__( 'Update required: SaleXpresso will soon require PHP version %1$s and WordPress version %2$s or newer.', 'salexpresso' ),
				SXP_NOTICE_MIN_PHP_VERSION,
				SXP_NOTICE_MIN_WP_VERSION
			);
		} elseif ( $old_php ) {
			$msg = sprintf(
				/* translators: %s: Minimum PHP version */
				__( 'Update required: SaleXpresso will soon require PHP version %s or newer.', 'salexpresso' ),
				SXP_NOTICE_MIN_PHP_VERSION
			);
		} elseif ( $old_wp ) {
			$msg = sprintf(
				/* translators: %s: Minimum WordPress version */
				__( 'Update required: SaleXpresso will soon require WordPress version %s or newer.', 'salexpresso' ),
				SXP_NOTICE_MIN_WP_VERSION
			);
		}
		
		?>
		<div id="message" class="updated salexpresso-message">
			<a class="salexpresso-message-close notice-dismiss" href="<?php echo esc_url( wp_nonce_url( add_query_arg( 'sxp-hide-notice', SXP_PHP_WP_MIN_REQUIREMENTS_NOTICE ), 'salexpresso_hide_notices_nonce', '_sxp_notice_nonce' ) ); ?>">
				<span class="screen-reader-text"><?php esc_html_e( 'Dismiss', 'salexpresso' ); ?></span>
			</a>
			<p>
				<?php
				echo wp_kses_post(
					sprintf(
						$msg . '<p><a href="%s" class="button button-primary">' . __( 'Learn how to upgrade', 'salexpresso' ) . '</a></p>',
						add_query_arg(
							array(
								'utm_source'   => 'wp_php_update_banner',
								'utm_medium'   => 'product',
								'utm_campaign' => 'salexpresso_plugin',
								'utm_content'  => 'docs',
							),
							''
						)
					)
				);
				?>
			</p>
		</div>
		<?php
	}
	
	/**
	 * Notice for WC Dependency
	 *
	 * @return void
	 */
	public static function min_wc_requirements_notice() {
		if ( apply_filters( 'salexpresso_hide_min_wc_requirements_nag', get_user_meta( get_current_user_id(), 'dismissed_' . SXP_WC_MIN_REQUIREMENTS_NOTICE . '_notice', true ) ) ) {
			self::remove_notice( SXP_WC_MIN_REQUIREMENTS_NOTICE );
			return;
		}
		
		// Get wc version.
		$has_wc     = self::is_plugin_active( 'woocommerce/woocommerce.php' );
		$wc_version = self::get_plugin_version( 'woocommerce/woocommerce.php' );
		$old_wc     = version_compare( $wc_version, SXP_NOTICE_MIN_WC_VERSION, '<' );
		
		// Both PHP and WordPress up to date version => no notice.
		if ( $has_wc && ! $old_wc ) {
			return;
		}
		$msg = '';
		if ( ! $has_wc ) {
			$msg = sprintf(
			/* translators: 1: Required WooCommerce Version. 2: Opening tag for installation link 3: Closing tag. */
				__( 'SaleXpresso requires WooCommerce version %1$s to be installed and active. You WooCommerce installed/activate %2$shere%3$s.', 'salexpresso' ),
				SXP_NOTICE_MIN_WC_VERSION,
				'<a href="' . esc_url( self_admin_url( 'plugin-install.php?s=woocommerce&tab=search&type=term' ) ) . '" rel="noopener noreferrer">',
				'</a>'
			);
		} elseif ( $old_wc ) {
			$msg = sprintf(
			/* translators: 1: Required WooCommerce Version. 2: Opening tag for installation link 3: Closing tag. */
				__( 'Update required: SaleXpresso will soon require WooCommerce version %1$s or newer. Click %2$shere%3$s. To Update WooCommerce', 'salexpresso' ),
				SXP_NOTICE_MIN_WC_VERSION,
				'<a href="' . esc_url( self_admin_url( 'plugin-install.php?s=woocommerce&tab=search&type=term' ) ) . '" rel="noopener noreferrer">',
				'</a>'
			);
		}
		if ( ! empty( $msg ) ) {
			?>
		<div id="message" class="updated salexpresso-message">
			<a class="salexpresso-message-close notice-dismiss" href="<?php echo esc_url( wp_nonce_url( add_query_arg( 'sxp-hide-notice', SXP_WC_MIN_REQUIREMENTS_NOTICE ), 'salexpresso_hide_notices_nonce', '_sxp_notice_nonce' ) ); ?>">
				<span class="screen-reader-text"><?php esc_html_e( 'Dismiss', 'salexpresso' ); ?></span>
			</a>
			<p>
				<?php
				echo wp_kses_post(
					sprintf(
						$msg . '<p><a href="%s" class="button button-primary">' . __( 'Learn how to upgrade', 'salexpresso' ) . '</a></p>',
						add_query_arg(
							array(
								'utm_source'   => 'sxp_update_banner',
								'utm_medium'   => 'product',
								'utm_campaign' => 'salexpresso_plugin',
								'utm_content'  => 'docs',
							),
							''
						)
					)
				);
				?>
			</p>
		</div>
			<?php
		}
	}
	
	/**
	 * Wrapper for is_plugin_active.
	 *
	 * @param string $plugin Plugin to check.
	 * @return boolean
	 */
	protected static function is_plugin_active( $plugin ) {
		if ( ! function_exists( 'is_plugin_active' ) ) {
			include_once ABSPATH . 'wp-admin/includes/plugin.php';
		}
		return is_plugin_active( $plugin );
	}
	
	/**
	 * Get Installed plugin Version.
	 *
	 * @param string $plugin plugin slug (plugin_dir_name/main_file.php).
	 *
	 * @return bool|string
	 */
	protected static function get_plugin_version( $plugin ) {
		if ( ! function_exists( 'get_plugins' ) ) {
			require_once ABSPATH . 'wp-admin/includes/plugin.php';
		}
		$plugins = get_plugins();
		if ( array_key_exists( $plugin, $plugins ) ) {
			return $plugins[ $plugin ]['Version'];
		}
		return false;
	}
}

// End of file class-sxp-admin-notices.php.
