<?php
/**
 * SaleXpresso
 *
 * @package SaleXpresso\Campaign
 * @version 1.0.0
 * @since   SaleXpresso v
 */

namespace SaleXpresso\Campaign;

use SaleXpresso\Abstracts\SXP_Admin_Page;

if ( ! defined( 'ABSPATH' ) ) {
	header( 'Status: 403 Forbidden' );
	header( 'HTTP/1.1 403 Forbidden' );
	die();
}

/**
 * Class SXP_Dashboard_Page
 *
 * @package SaleXpresso\Campaign
 */
class SXP_Campaign_page  extends SXP_Admin_Page{

	/**
	 * Add new button url for current page.
	 *
	 * @var string
	 */
	protected $add_new_url = '';

	/**
	 * SXP_Customer_List constructor.
	 *
	 * @param string $plugin_page Current page slug.
	 */
	public function __construct( $plugin_page = null ) {
		parent::__construct( $plugin_page );
		$this->add_new_label = '';
		$this->js_tabs       = false;
	}

	/**
	 * Set Tabs and tab content.
	 */
	protected function set_tabs() {
		$tabs = [
			'campaigns-lists' => [
				'label'   => __( 'Campaigns', 'salexpresso' ),
				'content' => [$this, 'render_campaign_list'],
			],
			'campaigns-new' => [
				'label'   => __( 'New Campaign', 'salexpresso' ),
				'content' => [$this, 'render_campaign_new'],
			],
		];

		// This filter documented in  includes/abstracts/class-sxp-admin-page.php.
		$this->tabs = apply_filters( "salexpresso_admin_{$this->hook_slug}_page_tabs", $tabs );
	}

	/**
	 * Render the Campaign list
	 */
	protected function render_campaign_list() {
		$list = new SXP_Campaign_List_Table();
	}

	/**
	 * Render the Campaign list
	 */
	protected function render_campaign_new() {
		$list = new SXP_Campaign_new_Table();
	}

}

// End of file class-sxp-campaign-page.php.