<?php
/**
 * SaleXpresso
 *
 * @package SaleXpresso\Settings
 * @version 1.0.0
 * @since   SaleXpresso v1.0.0
 */

namespace SaleXpresso\Settings;

use SaleXpresso\Abstracts\SXP_Settings_Tab;

if ( ! defined( 'ABSPATH' ) ) {
	header( 'Status: 403 Forbidden' );
	header( 'HTTP/1.1 403 Forbidden' );
	die();
}

/**
 * Class SXP_General_Settings
 *
 * @package SaleXpresso\Settings
 * @see \WC_Settings_General
 */
class SXP_General_Settings extends SXP_Settings_Tab {
	/**
	 * Constructor.
	 */
	public function __construct() {
		$this->id    = 'general';
		$this->label = __( 'General', 'salexpresso' );
		
		parent::__construct();
	}
	
	/**
	 * Get settings array.
	 *
	 * @param string $current_section Optional. Current section slug.
	 *
	 * @return array
	 */
	public function get_settings( $current_section = '' ) {
		global $hide_save_button;
		$hide_save_button = true;
		
		$settings = apply_filters(
			'salexpresso_general_settings',
			[
				[
					'title' => __( 'General Settings', 'salexpresso' ),
					'type'  => 'title',
					'desc'  => '',
					'id'    => 'general_settings',
				],
				[
					'type' => 'sectionend',
					'id'   => 'general_settings',
				],
			]
		);
		
		return apply_filters( 'salexpresso_get_settings_' . $this->id, $settings );
	}
}
new SXP_General_Settings();
// End of file class-sxp-general-settings.php.
