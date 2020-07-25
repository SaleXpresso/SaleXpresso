<?php
/**
 * SaleXpresso Settings Page Tab And Field Generator.
 *
 * @package SaleXpresso/Admin
 * @version 1.0.0
 * @since   SaleXpresso v1.0.0
 */

namespace SaleXpresso\Abstracts;
use SaleXpresso\Settings\SXP_Admin_Settings;

if ( ! defined( 'ABSPATH' ) ) {
	header( 'Status: 403 Forbidden' );
	header( 'HTTP/1.1 403 Forbidden' );
	die();
}

/**
 * Class SXP_Settings_Tab.
 * Extended from WC/Admin Package.
 *
 * @see \WC_Settings_Page
 *
 * @author      SaleXpresso
 * @author      WooThemes (WooCommerce)
 */
abstract class SXP_Settings_Tab {
	
	/**
	 * Setting page id.
	 *
	 * @var string
	 */
	protected $id = '';
	
	/**
	 * Setting page label.
	 *
	 * @var string
	 */
	protected $label = '';
	
	/**
	 * Constructor.
	 */
	public function __construct() {
		add_filter( 'salexpresso_settings_tabs_array', [ $this, 'add_settings_page' ], 20 );
		add_action( 'salexpresso_sections_' . $this->id, [ $this, 'output_sections' ] );
		add_action( 'salexpresso_settings_' . $this->id, [ $this, 'output' ] );
		add_action( 'salexpresso_settings_save_' . $this->id, [ $this, 'save' ] );
	}
	
	/**
	 * Get settings page ID.
	 *
	 * @return string
	 */
	public function get_id() {
		return $this->id;
	}
	
	/**
	 * Get settings page label.
	 *
	 * @return string
	 */
	public function get_label() {
		return $this->label;
	}
	
	/**
	 * Add this page to settings.
	 *
	 * @param array $pages
	 *
	 * @return mixed
	 */
	public function add_settings_page( $pages ) {
		$pages[ $this->id ] = $this->label;
		
		return $pages;
	}
	
	/**
	 * Get settings array.
	 *
	 * @param string $current_section Optional. Current Settings Section
	 *
	 * @return array
	 */
	public function get_settings( $current_section = '' ) {
		return apply_filters( 'salexpresso_get_settings_' . $this->id, [] );
	}
	
	/**
	 * Get sections.
	 *
	 * @return array
	 */
	public function get_sections() {
		return apply_filters( 'salexpresso_get_sections_' . $this->id, [] );
	}
	
	/**
	 * Output sections.
	 * @return void
	 */
	public function output_sections() {
		global $current_section;
		
		$sections = $this->get_sections();
		
		if ( empty( $sections ) || 1 === sizeof( $sections ) ) {
			return;
		}
		
		echo '<ul class="subsubsub">';
		
		$array_keys = array_keys( $sections );
		
		foreach ( $sections as $id => $label ) {
			printf(
				'<li><a href="%s" class="%s">%s</a> %s </li>',
				esc_url( admin_url( 'admin.php?page=sxp-settings&tab=' . $this->id . '&section=' . sanitize_title( $id ) ) ),
				( $current_section == $id ? 'current' : '' ),
				esc_html( $label ),
				( end( $array_keys ) == $id ? '' : '|' )
			);
		}
		
		echo '</ul><br class="clear" />';
	}
	
	/**
	 * Output the settings.
	 * @return void
	 */
	public function output() {
		global $current_section;
		$settings = $this->get_settings( $current_section );
		
		SXP_Admin_Settings::output_fields( $settings );
	}
	
	/**
	 * Save settings.
	 * @return void
	 */
	public function save() {
		global $current_section;
		
		$settings = $this->get_settings( $current_section );
		SXP_Admin_Settings::save_fields( $settings );
		
		if ( $current_section ) {
			do_action( 'salexpresso_update_options_' . $this->id . '_' . $current_section );
		}
	}
}
// End of file class-sxp-settings-tab.php.
