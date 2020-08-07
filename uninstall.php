<?php
/**
 * Fired when the plugin is uninstalled.
 *
 * @package SaleXpresso
 * @author  SaleXpresso <support@salexpresso.com>
 * @version 1.0.0
 * @since   SaleXpresso 1.0.0
 */

// If uninstall not called from WordPress, then exit.
use SaleXpresso\SXP_Install;

if ( ! defined( 'WP_UNINSTALL_PLUGIN' ) ) {
	exit;
}
// phpcs:disable
// @TODO uninstallation procedure
/**
 * Only remove ALL product and page data if SXP_REMOVE_ALL_DATA constant is set to true in user's
 * wp-config.php. This is to prevent data loss when deleting the plugin from the backend
 * and to ensure only the site owner can perform this action.
 */
if ( defined( 'SXP_REMOVE_ALL_DATA' ) && true === SXP_REMOVE_ALL_DATA ) {
	global $wpdb;
	
	// Remove custom roles & capabilities.
	SXP_Install::remove_roles_caps();
	
	// Delete Options.
	$wpdb->query( "DELETE FROM $wpdb->options WHERE option_name LIKE 'salexpresso\_%';" );
	
	// Delete term taxonomies.
	foreach ( [ 'user_group', 'user_type', 'user_tag' ] as $_taxonomy ) {
		$wpdb->delete(
			$wpdb->term_taxonomy,
			array(
				'taxonomy' => $_taxonomy,
			)
		);
	}
	
	// Delete orphan relationships.
	$wpdb->query(
		"
		DELETE tr FROM {$wpdb->term_relationships} tr
		LEFT JOIN {$wpdb->posts} posts ON posts.ID = tr.object_id
		WHERE posts.ID IS NULL;
		"
	);
	
	// Delete orphan terms.
	$wpdb->query(
		"
		DELETE t FROM {$wpdb->terms} t
		LEFT JOIN {$wpdb->term_taxonomy} tt ON t.term_id = tt.term_id
		WHERE tt.term_id IS NULL;
		"
	);
	
	// Delete orphan term meta.
	$wpdb->query(
		"
			DELETE tm FROM {$wpdb->termmeta} tm
			LEFT JOIN {$wpdb->term_taxonomy} tt ON tm.term_id = tt.term_id
			WHERE tt.term_id IS NULL;
			"
	);
	
	// Drop tables.
	SXP_Install::drop_tables();
	SXP_Install::delete_options();
	
	// Clear any cached data that has been removed.
	wp_cache_flush();
}
// End of file uninstall.php.
