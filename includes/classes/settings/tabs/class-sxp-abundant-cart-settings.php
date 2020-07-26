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
class SXP_Abundant_Cart_Settings extends SXP_Settings_Tab {
	/**
	 * Constructor.
	 */
	public function __construct() {
		$this->id    = 'abundant-cart';
		$this->label = __( 'Abundant Cart', 'salexpresso' );
		parent::__construct();
	}
	
	/**
	 * Get sections.
	 *
	 * @return array
	 */
	public function get_sections() {
		return apply_filters( 'salexpresso_get_sections_' . $this->id, array(
			''          => __( 'General', 'salexpresso' ),
			'exclusion' => __( 'Exclusion', 'salexpresso' ),
		) );
	}
	
	/**
	 * Get settings array.
	 *
	 * @param string $current_section Current Section Slug.
	 * @return array
	 */
	public function get_settings( $current_section = '' ) {
		global $wp_roles;
		
		if ( 'exclusion' === $current_section ) {
			$settings[] = [
				'type'  => 'title',
				'title' => __( 'Exclusion Settings', 'salexpresso' ),
				'desc'  => '',
				'id'    => 'salexpresso_ac_exclusion',
			];
			
			$settings[] = [
				'title'    => __( 'Exclude User IDs', 'salexpresso' ),
				'desc'     => __( 'Exclude Users by ID, Multiple Id must be separated with comma', 'salexpresso' ),
				'id'       => 'salexpresso_ac_exclude_ids',
				'type'     => 'text',
				'default'  => '',
				'autoload' => false,
				'desc_tip' => true,
			];
			
			$count = count( $wp_roles->roles );
			$i = 0;
			foreach ( $wp_roles->roles as $role_key => $role_value ) {
				$set = [
					'desc'          => sprintf( __( 'Exclude %s', 'salexpresso' ), $role_value['name'] ),
					'id'            => sprintf( 'salexpresso_ac_exclude_role[%s]', $role_key ),
					'default'       => 'no',
					'type'          => 'checkbox',
					'autoload'      => false,
					'checkboxgroup' => '',
				];
				
				if ( 0 === $i ) {
					$set['title'] = __( 'Exclude From Tracking', 'salexpresso' );
					$set['checkboxgroup'] = 'start';
				}
				
				if ( ( $count - 1 ) === $i ) {
					$set['checkboxgroup'] = 'end';
				}
				
				$settings[] = $set;
				$i++;
			}
			
			$settings[] = [
				'type' => 'sectionend',
				'id'   => 'salexpresso_ac_exclusion',
			];
		} else {
			$settings = [
				[
					'type'  => 'title',
					'title' => __( 'General Settings', 'salexpresso' ),
					'desc'  => '',
					'id'    => 'salexpresso_ac',
				],
				[
					'title'    => __( 'Enable Cart Tracking', 'salexpresso' ),
					'desc'     => __( 'Allow SaleXpresso to track abandoned carts.', 'salexpresso' ),
					'id'       => 'salexpresso_ac_enable',
					'default'  => 'yes',
					'type'     => 'checkbox',
					'autoload' => false,
				],
				[
					'title'    => __( 'Use Abundant Cart For Recommendation', 'salexpresso' ),
					'desc'     => __( 'User abundant cart item data for recommendation engine (AI).', 'salexpresso' ),
					'id'       => 'salexpresso_ac_recommendation',
					'default'  => 'no',
					'type'     => 'checkbox',
					'autoload' => false,
				],
				[
					'title'    => __( 'Keep Cart Without Email', 'salexpresso' ),
					'desc'     => __( 'Keep abandon cart information if no email is captured.', 'salexpresso' ),
					'id'       => 'salexpresso_ac_keep_no_email',
					'default'  => 'yes',
					'type'     => 'checkbox',
					'autoload' => false,
				],
				[
					'title'         => __( 'Notify Admin', 'salexpresso' ),
					'desc'          => __( 'Admin will get an email notification when cart is abandoned.', 'salexpresso' ),
					'id'            => 'salexpresso_ac_notify_new',
					'default'       => 'no',
					'type'          => 'checkbox',
					'autoload'      => false,
					'checkboxgroup' => 'start',
				],
				[
					'desc'          => __( 'Admin will get an email notification when cart is recovered.', 'salexpresso' ),
					'id'            => 'salexpresso_ac_notify_recovered',
					'default'       => 'no',
					'type'          => 'checkbox',
					'autoload'      => false,
					'checkboxgroup' => 'end',
				],
				[
					'title'    => __( 'Cart Expiration Time', 'salexpresso' ),
					'desc'     => __( 'How long to keep the abandoned orders in database.', 'salexpresso' ),
					'id'       => 'salexpresso_ac_expiration',
					'default'  => [
						'number' => 15,
						'unit'   => 'days',
					],
					'type'     => 'relative_date_selector',
					'autoload' => false,
				],
				[
					'title'             => __( 'Cart Abandonment Countdown', 'salexpresso' ),
					'desc'              => __( 'Minimum time to consider a cart as abandoned. Minimum time limit 15 minutes.', 'salexpresso' ),
					'id'                => 'salexpresso_ac_timeout',
					'default'           => 15,
					'type'              => 'number',
					'suffix'            => '/Minutes',
					'custom_attributes' => [ 'step' => 1 ],
					'autoload'          => false,
				],
				[
					'type' => 'sectionend',
					'id'   => 'salexpresso_ac',
				],
			
			];
		}
		
		$settings = apply_filters( 'salexpresso_abundant_cart_settings', $settings );
		
		return apply_filters( 'salexpresso_get_settings_' . $this->id, $settings );
	}
	
	/**
	 * Save Settings.
	 */
	public function save() {
		// phpcs:disable
		if ( isset( $_POST['salexpresso_ac_exclude_ids'] ) && ! empty( $_POST['salexpresso_ac_exclude_ids'] ) ) {
			$_POST['salexpresso_ac_exclude_ids'] = sxp_sanitize_csv_ids( $_POST['salexpresso_ac_exclude_ids'], 'absint' );
		}
		// phpcs:enable
		
		parent::save();
		
		/**
		 * Update WP_Roles Capabilities
		 */
		do_action( 'salexpresso_update_roles_and_caps' );
	}
}
new SXP_Abundant_Cart_Settings();
// End of file class-sxp-abundant-cart-settings.php.
