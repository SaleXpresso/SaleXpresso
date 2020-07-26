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
use SaleXpresso\SXP_IP;

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
class SXP_Session_Tracking_Settings extends SXP_Settings_Tab {
	/**
	 * Constructor.
	 */
	public function __construct() {
		$this->id    = 'session-tracking';
		$this->label = __( 'Session Tracking', 'salexpresso' );
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
			'advanced' => __( 'Advanced', 'salexpresso' ),
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
		
		if ( 'advanced' === $current_section ) {
			$settings = [
				[
					'type'  => 'title',
					'title' => __( 'Advanced Settings', 'salexpresso' ),
					'desc'  => '',
					'id'    => 'salexpresso_st_advanced',
				],
				[
					'title'    => __( 'Cart Expiration Time', 'salexpresso' ),
					'desc'     => __( 'How long to keep the abandoned orders in database.', 'salexpresso' ),
					'id'       => 'salexpresso_st_customer_id_expiration',
					'default'  => [
						'number' => 2,
						'unit'   => 'years',
					],
					'type'     => 'relative_date_selector',
					'autoload' => false,
				],
				[
					'title'    => __( 'Track User IP', 'salexpresso' ),
					'desc'     => __( 'Store user IP address with tracking data.', 'salexpresso' ),
					'id'       => 'salexpresso_st_track_ip',
					'default'  => 'yes',
					'type'     => 'checkbox',
					'autoload' => false,
				],
				[
					'title'    => __( 'How does SaleXpresso Get IPs', 'salexpresso' ),
					'desc'     => sprintf(
						__( 'Store user IP address with tracking data.<br>Detected IP(s): %s<br>Your IP with this setting: %s', 'salexpresso' ),
						SXP_IP::get_ip_preview(),
						SXP_IP::get_ip()
					),
					'id'       => 'salexpresso_st_ip_request_field',
					'default'  => '',
					'options'  => [
						'' => esc_html__('Let SaleXpresso use the most secure method to get visitor IP addresses. Prevents spoofing and works with most sites (Recommended).', 'salexpresso'),
						'REMOTE_ADDR' => esc_html__('Use PHP\'s built in REMOTE_ADDR and don\'t use anything else. Very secure if this is compatible with your site.', 'salexpresso'),
						'HTTP_X_FORWARDED_FOR' => esc_html__('Use the X-Forwarded-For HTTP header. Only use if you have a front-end proxy or spoofing may result.', 'salexpresso'),
						'HTTP_X_REAL_IP' => esc_html__('Use the X-Real-IP HTTP header. Only use if you have a front-end proxy or spoofing may result.', 'salexpresso'),
						'HTTP_CF_CONNECTING_IP' => esc_html__('Use the Cloudflare "CF-Connecting-IP" HTTP header to get a visitor IP. Only use if you\'re using Cloudflare.', 'salexpresso'),
					],
					'type'     => 'select',
					'autoload' => false,
				],
				[
					'title'    => __( 'Trusted Proxies', 'salexpresso' ),
					'desc'     => __( 'These IPs (or CIDR ranges) will be ignored when determining the requesting IP via the X-Forwarded-For HTTP header. Enter one IP or CIDR range per line.', 'salexpresso' ),
					'id'       => 'salexpresso_st_trusted_proxies',
					'default'  => '',
					'type'     => 'textarea',
					'custom_attributes' => [
						'spellcheck'     => 'false',
						'autocapitalize' => 'none',
						'autocomplete'   => 'off',
					],
					'autoload' => false,
				],
				[
					'type' => 'sectionend',
					'id'   => 'salexpresso_st_advanced',
				]
			];
		} else {
			$settings = [
				[
					'type'  => 'title',
					'title' => __( 'General Settings', 'salexpresso' ),
					'desc'  => '',
					'id'    => 'salexpresso_st',
				],
				[
					'title'    => __( 'Enable Tracking', 'salexpresso' ),
					'desc'     => __( 'Allow SaleXpresso to track visitor sessions (analytics).', 'salexpresso' ),
					'id'       => 'salexpresso_st_enable',
					'default'  => 'yes',
					'type'     => 'checkbox',
					'autoload' => false,
				],
			];
			
			$settings[] = [
				'title'    => __( 'Exclude User IDs', 'salexpresso' ),
				'desc'     => __( 'Exclude Users by ID, Multiple Id must be separated with comma', 'salexpresso' ),
				'id'       => 'salexpresso_st_exclude_ids',
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
					'id'            => sprintf( 'salexpresso_st_exclude_role[%s]', $role_key ),
					'default'       => 'no',
					'type'          => 'checkbox',
					'autoload'      => false,
					'checkboxgroup' => '',
				];
				
				if ( ( $count - 1 ) === $i ) {
					$set['checkboxgroup'] = 'end';
				}
				
				if ( 0 === $i ) {
					$set['title'] = __( 'Exclude From Tracking', 'salexpresso' );
					$set['checkboxgroup'] = 'start';
				}
				
				$settings[] = $set;
				$i++;
			}
			
			$settings[] = [
				'type' => 'sectionend',
				'id'   => 'salexpresso_st',
			];
		}
		
		$settings = apply_filters( 'salexpresso_session_tracking_settings', $settings );
		
		return apply_filters( 'salexpresso_get_settings_' . $this->id, $settings );
	}
	
	/**
	 * Save settings.
	 * @return void
	 */
	public function save() {
		// phpcs:disable
		if ( isset( $_POST['salexpresso_st_trusted_proxies'] ) && ! empty( $_POST['salexpresso_st_trusted_proxies'] ) ) {
			$trusted_proxies = sanitize_textarea_field( $_POST['salexpresso_st_trusted_proxies'] );
			
			$validIPs = preg_split('/[\r\n,]+/', $trusted_proxies);
			$validIPs = array_filter($validIPs); //Already validated above
			
			$invalid_IPs = [];
			foreach ( $validIPs as $val ) {
				if ( ! ( SXP_IP::is_valid_ip( $val ) || SXP_IP::is_valid_cidr_range( $val ) ) ) {
					$invalid_IPs[] = $val;
				}
			}
			if ( count( $invalid_IPs ) > 0 ) {
				SXP_Admin_Settings::add_error( __( 'Invalid IPs/ranges provide in trusted proxies list.', 'salexpresso' ) );
				return;
			}
			$_POST['salexpresso_st_trusted_proxies'] = $trusted_proxies;
		}
		if ( isset( $_POST['salexpresso_st_exclude_ids'] ) ) {
			$_POST['salexpresso_st_exclude_ids'] = sxp_sanitize_csv_ids( $_POST['salexpresso_st_exclude_ids'], 'absint' );
		}
		// phpcs:enable
		
		parent::save();
		
		/**
		 * Update WP_Roles Capabilities
		 */
		do_action( 'salexpresso_update_roles_and_caps' );
	}
}
new SXP_Session_Tracking_Settings();
// End of file class-sxp-session-tracking-settings.php.
