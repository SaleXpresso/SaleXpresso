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
		add_filter( 'salexpresso_abundant_cart_settings', [ $this, 'exclude_user_role_checkboxes' ], 10, 1 );
		parent::__construct();
	}
	
	/**
	 * Get settings array.
	 *
	 * @return array
	 */
	public function get_settings() {
		
		$settings = apply_filters(
			'salexpresso_abundant_cart_settings',
			[
				[
					'type'  => 'title',
					'desc'  => __( 'Abundant Cart Management Settings.', 'salexpresso' ),
					'id'    => 'abundant_cart',
				],
				[
					'title'         => __( 'Enable Cart Tracking', 'salexpresso' ),
					'desc'          => __( 'Allow SaleXpresso to track abandoned carts.', 'salexpresso' ),
					'id'            => 'salexpresso_enable_abundant_cart',
					'default'       => 'yes',
					'type'          => 'checkbox',
				],
				[
					'title'         => __( 'Send Data To Recommendation Engine', 'salexpresso' ),
					'desc'          => __( 'User abundant cart item data for recommendation engine (AI).', 'salexpresso' ),
					'id'            => 'salexpresso_use_abundant_cart_for_recommendation',
					'default'       => 'no',
					'type'          => 'checkbox',
				],
				[
					'title'         => __( 'Keep Cart Without Email', 'salexpresso' ),
					'desc'          => __( 'Keep abandon cart information if no email is captured.', 'salexpresso' ),
					'id'            => 'salexpresso_keep_guest_abundant_cart',
					'default'       => 'yes',
					'type'          => 'checkbox',
				],
				[
					'title'         => __( 'Notify Admin', 'salexpresso' ),
					'desc'          => __( 'Admin will get an email notification when cart is abandoned.', 'salexpresso' ),
					'id'            => 'salexpresso_notify_abundant_cart',
					'default'       => 'no',
					'type'          => 'checkbox',
					'checkboxgroup' => 'start'
				],
				[
					'desc'          => __( 'Admin will get an email notification when cart is recovered.', 'salexpresso' ),
					'id'            => 'salexpresso_notify_abundant_cart_recovered',
					'default'       => 'no',
					'type'          => 'checkbox',
					'checkboxgroup' => 'end'
				],
				[
					'title'         => __( 'Cart Expiration Time', 'salexpresso' ),
					'desc'          => __( 'How long to keep the abandoned orders before deleted.', 'salexpresso' ),
					'id'            => 'salexpresso_abundant_cart_expiration_time',
					'default'       => [
						'number' => 15,
						'unit'   => 'days',
					],
					'type'          => 'relative_date_selector',
				],
				[
					'title'             => __( 'Cart Abandonment Countdown', 'salexpresso' ),
					'desc'              => __( 'Minimum time to consider a cart as abandoned. Minimum time limit 15 minutes.', 'salexpresso' ),
					'id'                => 'salexpresso_abundant_cart_timeout',
					'default'           => 15,
					'type'              => 'number',
					'suffix'            => '/Minutes',
					'custom_attributes' => [
						'step' => 1,
					],
				],
				[
					'type' => 'sectionend',
					'id'   => 'abundant_cart',
				],
				
			]
		);
		
		return apply_filters( 'salexpresso_get_settings_' . $this->id, $settings );
	}
	
	/**
	 * Add Checkboxes for excluding user roles from abundant cart tracking.
	 *
	 * @param array $settings
	 *
	 * @return array
	 */
	public function exclude_user_role_checkboxes( $settings ) {
		$settings[] = [
			'type'  => 'title',
			'desc'  => __( 'Advanced Settings.', 'salexpresso' ),
			'id'    => 'abundant_cart_advanced',
		];
		
		global $wp_roles;
		$all_roles = $wp_roles->roles;
		$count = count( $wp_roles->roles );
		$i = 0;
		foreach ($all_roles as $role_key => $role_value) {
			$set = [
				'desc'              => sprintf( __( 'Exclude %s', 'salexpresso' ), $role_value['name'] ),
				'id'                => sprintf( 'salexpresso_abundant_cart_by_role[%s]', $role_key ),
				'default'           => 'no',
				'type'              => 'checkbox',
				'checkboxgroup'     => '',
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
			'id'   => 'abundant_cart_advanced',
		];
		
		return $settings;
	}
}
new SXP_Abundant_Cart_Settings();
// End of file class-sxp-abundant-cart-settings.php.
