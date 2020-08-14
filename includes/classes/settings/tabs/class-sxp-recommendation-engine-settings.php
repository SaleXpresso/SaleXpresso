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
class SXP_Recommendation_Engine_Settings extends SXP_Settings_Tab {
	/**
	 * Constructor.
	 */
	public function __construct() {
		$this->id    = 'recommendation-engine';
		$this->label = __( 'Recommendation Engine', 'salexpresso' );
		parent::__construct();
	}
	
	/**
	 * Get settings array.
	 *
	 * @param string $current_section Current Section Slug.
	 * @return array
	 */
	public function get_settings( $current_section = '' ) {
		
		$settings = [
			[
				'type'  => 'title',
				'title' => __( 'General Settings', 'salexpresso' ),
				'desc'  => '',
				'id'    => 'salexpresso_recommendation_engine_general',
			],
			[
				'title'    => __( 'Enable Recommendation Engine', 'salexpresso' ),
				'desc'     => '',
				'id'       => 'salexpresso_recommendation_engine_is_enabled',
				'default'  => 'yes',
				'type'     => 'checkbox',
				'autoload' => false,
			],
			[
				'title'    => __( 'Include out of stock', 'salexpresso' ),
				'desc'     => '',
				'id'       => 'salexpresso_recommendation_engine_outofstock',
				'default'  => 'yes',
				'type'     => 'checkbox',
				'autoload' => false,
			],
			[
				'title'    => __( 'Include backorders', 'salexpresso' ),
				'desc'     => '',
				'id'       => 'salexpresso_recommendation_engine_backorders',
				'default'  => 'yes',
				'type'     => 'checkbox',
				'autoload' => false,
			],
			[
				'title'    => __( 'Order Status', 'salexpresso' ),
				'desc'     => __( 'Select which Orders will be used to train the AI.', 'salexpresso' ),
				'id'       => 'salexpresso_recommendation_engine_order_status',
				'default'  => [ 'wc-processing', 'wc-completed' ],
				'class'    => 'selectize regular-text',
				'options'  => wc_get_order_statuses(),
				'type'     => 'multiselect',
				'autoload' => false,
			],
			[
				'title'    => __( 'Number of Orders', 'salexpresso' ),
				'desc'     => __( 'Maximum Number of orders to user for training. Higher the slower.', 'salexpresso' ),
				'id'       => 'salexpresso_recommendation_engine_order_count',
				'default'  => 1000,
				'type'     => 'number',
				'autoload' => false,
				'custom_attributes' => [ 'min' => -1, 'step' => 1 ],
			],
			[
				'title'    => __( 'Support', 'salexpresso' ),
				'id'       => 'salexpresso_recommendation_engine_support',
				'desc'     => sprintf(
					__( '<a href="%s">Support</a> is an indication of how frequently the itemset appears in the dataset.', 'salexpresso' ),
					'https://en.wikipedia.org/wiki/Association_rule_learning#Support'
				),
				'default'  => 0.6, //https://www.quora.com/How-do-I-pick-appropriate-support-confidence-value-when-doing-basket-analysis-with-Apriori-algorithm
				'type'     => 'number',
				'autoload' => false,
				'custom_attributes' => [ 'min' => 0 ],
			],
			[
				'title'    => __( 'Confidence', 'salexpresso' ),
				'id'       => 'salexpresso_recommendation_engine_confidence',
				'desc'     => sprintf(
					__( '<a href="%s">Confidence</a> is an indication of how often the rule has been found to be true.', 'salexpresso' ),
					'https://en.wikipedia.org/wiki/Association_rule_learning#Confidence'
				),
				'default'  => 0.4, // 40%
				'type'     => 'number',
				'autoload' => false,
				'custom_attributes' => [ 'min' => 0 ],
			],
			[
				'type' => 'sectionend',
				'id'   => 'salexpresso_recommendation_engine_general',
			],
		];
		
		$settings = apply_filters( 'salexpresso_recommendation_enginecommendation_engine_settings', $settings );
		
		return apply_filters( 'salexpresso_get_settings_' . $this->id, $settings );
	}
	
	/**
	 * Save Settings.
	 */
	public function save() {
		parent::save();
		do_action( 'salexpresso_recommendation_engine_schedule_training');
	}
}
new SXP_Recommendation_Engine_Settings();
// End of file class-sxp-recommendation-engine-settings.php.
