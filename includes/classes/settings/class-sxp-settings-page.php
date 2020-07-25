<?php
/**
 * SaleXpresso
 *
 * @package SaleXpresso\Settings
 * @version 1.0.0
 * @since   SaleXpresso v1.0.0
 */

namespace SaleXpresso\Settings;

use SaleXpresso\Abstracts\SXP_Admin_Page;

if ( ! defined( 'ABSPATH' ) ) {
	header( 'Status: 403 Forbidden' );
	header( 'HTTP/1.1 403 Forbidden' );
	die();
}

/**
 * Class SXP_Settings_Page
 *
 * @package SaleXpresso\Settings
 */
class SXP_Settings_Page extends SXP_Admin_Page {
	
	protected $settings_tabs;
	/**
	 * SXP_Settings_Page constructor.
	 *
	 * @param string $plugin_page Current page slug.
	 */
	public function __construct( $plugin_page = null ) {
		parent::__construct( $plugin_page );
		
		add_filter( "salexpresso_admin_{$this->hook_slug}_page_class", function ( $classes ) {
			$classes[] = 'sxp-settings';
			return $classes;
		}, 10, 1 );
	}
	
	/**
	 * Init Page Load.
	 * Useful for setting screen options
	 *
	 * @hooked load-hookname
	 * @return void
	 */
	public function page_actions() {
		global $current_section, $current_tab;
		$this->settings_tabs = apply_filters( 'salexpresso_settings_tabs_array', [] );
		// Check if current tab is registered with our settings.
		$tab_exists = isset( $this->settings_tabs[ $current_tab ] ) || has_action( 'salexpresso_sections_' . $current_tab ) || has_action( 'salexpresso_settings_' . $current_tab ) || has_action( 'salexpresso_settings_tabs_' . $current_tab );
		if ( ! $tab_exists ) {
			wp_safe_redirect( admin_url( 'admin.php?page=sxp-settings' ) );
			exit;
		}
		// Set Wrapper Class.
		add_filter( 'salexpresso_admin_settings_page_class', [ $this, 'set_wrapper_class' ], 10, 1 );
	}
	
	/**
	 * Set Page Wrapper Class.
	 *
	 * @param string[] $classes
	 *
	 * @return string[]
	 */
	public function set_wrapper_class( $classes ) {
		$classes[] = 'salexpresso';
		return $classes;
	}
	
	/**
	 * Print HTML Content for the page
	 *
	 * @return void
	 */
	public function render_page_content() {
		global $current_section, $current_tab;
		// Get tabs for the settings page.
		$current_tab_label = isset( $this->settings_tabs[ $current_tab ] ) ? $this->settings_tabs[ $current_tab ] : '';
		?>
		<?php do_action( 'salexpresso_before_settings_' . $current_tab ); ?>
		<form method="<?php echo esc_attr( apply_filters( 'salexpresso_settings_form_method_tab_' . $current_tab, 'post' ) ); ?>" id="mainform" action="" enctype="multipart/form-data">
			<nav class="nav-tab-wrapper sxp-nav-tab-wrapper">
				<?php
				foreach ( $this->settings_tabs as $slug => $label ) {
					echo '<a href="' . esc_html( admin_url( 'admin.php?page=sxp-settings&tab=' . esc_attr( $slug ) ) ) . '" class="nav-tab ' . ( $current_tab === $slug ? 'nav-tab-active' : '' ) . '">' . esc_html( $label ) . '</a>';
				}
				do_action( 'salexpresso_settings_tabs' );
				?>
			</nav>
			<h2 class="screen-reader-text"><?php echo esc_html( $current_tab_label ); ?></h2>
			<?php
			do_action( 'salexpresso_sections_' . $current_tab );
			
			SXP_Admin_Settings::show_messages();
			
			do_action( 'salexpresso_settings_' . $current_tab );
			?>
			<p class="submit">
				<?php if ( empty( $GLOBALS['hide_save_button'] ) ) : ?>
					<button name="save" class="button-primary salexpresso-save-button" type="submit" value="<?php esc_attr_e( 'Save changes', 'salexpresso' ); ?>"><?php esc_html_e( 'Save changes', 'salexpresso' ); ?></button>
				<?php endif; ?>
				<?php wp_nonce_field( 'salexpresso-settings' ); ?>
			</p>
		</form>
		<?php do_action( 'salexpresso_after_settings_' . $current_tab ); ?>
		<?php
	}
}

// End of file class-sxp-settings.php.
