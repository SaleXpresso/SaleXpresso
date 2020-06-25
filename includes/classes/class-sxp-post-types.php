<?php
/**
 * SaleXpresso
 *
 * @package SaleXpresso
 * @version 1.0.0
 * @since   1.0.0
 */

namespace SaleXpresso;

use WP_User;

if ( ! defined( 'ABSPATH' ) ) {
	header( 'Status: 403 Forbidden' );
	header( 'HTTP/1.1 403 Forbidden' );
	die();
}

/**
 * Class SXP_Post_Types
 */
class SXP_Post_Types {
	
	/**
	 * Initialize things.
	 */
	public static function init() {
		add_action( 'init', [ __CLASS__, 'register_taxonomies' ], 5 );
	}
	
	/**
	 * Register Taxonomies
	 */
	public static function register_taxonomies() {
		// user group.
		$args = [
			'labels'            => [
				'name'                       => _x( 'Groups', 'Taxonomy General Name', 'salexpresso' ),
				'singular_name'              => _x( 'Group', 'Taxonomy Singular Name', 'salexpresso' ),
				'menu_name'                  => __( 'Groups', 'salexpresso' ),
				'all_items'                  => __( 'All Groups', 'salexpresso' ),
				'parent_item'                => __( 'Parent Group', 'salexpresso' ),
				'parent_item_colon'          => __( 'Parent Group:', 'salexpresso' ),
				'new_item_name'              => __( 'New Group Name', 'salexpresso' ),
				'add_new_item'               => __( 'Add New Group', 'salexpresso' ),
				'edit_item'                  => __( 'Edit Group', 'salexpresso' ),
				'update_item'                => __( 'Update Group', 'salexpresso' ),
				'view_item'                  => __( 'View Group', 'salexpresso' ),
				'separate_items_with_commas' => __( 'Separate groups with commas', 'salexpresso' ),
				'add_or_remove_items'        => __( 'Add or remove groups', 'salexpresso' ),
				'choose_from_most_used'      => __( 'Choose from the most used', 'salexpresso' ),
				'popular_items'              => __( 'Popular Groups', 'salexpresso' ),
				'search_items'               => __( 'Search Groups', 'salexpresso' ),
				'not_found'                  => __( 'Not Found', 'salexpresso' ),
				'no_terms'                   => __( 'No groups', 'salexpresso' ),
				'items_list'                 => __( 'Groups list', 'salexpresso' ),
				'items_list_navigation'      => __( 'Groups list navigation', 'salexpresso' ),
			],
			'hierarchical'      => true,
			'public'            => true,
			'show_ui'           => true,
			'show_admin_column' => true,
			'show_in_nav_menus' => false,
			'show_tagcloud'     => false,
		];
		register_user_taxonomy( 'user_group', $args );
		// user type.
		$args = [
			'labels'            => [
				'name'                       => _x( 'Types', 'Taxonomy General Name', 'salexpresso' ),
				'singular_name'              => _x( 'Type', 'Taxonomy Singular Name', 'salexpresso' ),
				'menu_name'                  => __( 'Types', 'salexpresso' ),
				'all_items'                  => __( 'All Types', 'salexpresso' ),
				'parent_item'                => __( 'Parent Type', 'salexpresso' ),
				'parent_item_colon'          => __( 'Parent Type:', 'salexpresso' ),
				'new_item_name'              => __( 'New Type Name', 'salexpresso' ),
				'add_new_item'               => __( 'Add New Type', 'salexpresso' ),
				'edit_item'                  => __( 'Edit Type', 'salexpresso' ),
				'update_item'                => __( 'Update Type', 'salexpresso' ),
				'view_item'                  => __( 'View Type', 'salexpresso' ),
				'separate_items_with_commas' => __( 'Separate type with commas', 'salexpresso' ),
				'add_or_remove_items'        => __( 'Add or remove types', 'salexpresso' ),
				'choose_from_most_used'      => __( 'Choose from the most used', 'salexpresso' ),
				'popular_items'              => __( 'Popular Types', 'salexpresso' ),
				'search_items'               => __( 'Search Types', 'salexpresso' ),
				'not_found'                  => __( 'Not Found', 'salexpresso' ),
				'no_terms'                   => __( 'No groups', 'salexpresso' ),
				'items_list'                 => __( 'Types list', 'salexpresso' ),
				'items_list_navigation'      => __( 'Types list navigation', 'salexpresso' ),
			],
			'hierarchical'      => true,
			'public'            => true,
			'show_ui'           => true,
			'show_admin_column' => true,
			'show_in_nav_menus' => false,
			'show_tagcloud'     => false,
		];
		register_user_taxonomy( 'user_type', $args );
		// user tag.
		$args = [
			'labels'            => [
				'name'                       => _x( 'Tags', 'Taxonomy General Name', 'salexpresso' ),
				'singular_name'              => _x( 'Tag', 'Taxonomy Singular Name', 'salexpresso' ),
				'menu_name'                  => __( 'Tags', 'salexpresso' ),
				'all_items'                  => __( 'All Tags', 'salexpresso' ),
				'parent_item'                => __( 'Parent Tag', 'salexpresso' ),
				'parent_item_colon'          => __( 'Parent Tag:', 'salexpresso' ),
				'new_item_name'              => __( 'New Tag Name', 'salexpresso' ),
				'add_new_item'               => __( 'Add New Tag', 'salexpresso' ),
				'edit_item'                  => __( 'Edit Tag', 'salexpresso' ),
				'update_item'                => __( 'Update Tag', 'salexpresso' ),
				'view_item'                  => __( 'View Tag', 'salexpresso' ),
				'separate_items_with_commas' => __( 'Separate tags with commas', 'salexpresso' ),
				'add_or_remove_items'        => __( 'Add or remove tags', 'salexpresso' ),
				'choose_from_most_used'      => __( 'Choose from the most used', 'salexpresso' ),
				'popular_items'              => __( 'Popular Tags', 'salexpresso' ),
				'search_items'               => __( 'Search Tags', 'salexpresso' ),
				'not_found'                  => __( 'Not Found', 'salexpresso' ),
				'no_terms'                   => __( 'No tags', 'salexpresso' ),
				'items_list'                 => __( 'Tags list', 'salexpresso' ),
				'items_list_navigation'      => __( 'Tags list navigation', 'salexpresso' ),
			],
			'hierarchical'      => false,
			'public'            => true,
			'show_ui'           => true,
			'show_admin_column' => true,
			'show_in_nav_menus' => false,
			'show_tagcloud'     => false,
		];
		register_user_taxonomy( 'user_tag', $args );
	}
}

SXP_Post_Types::init();
// End of file class-sxp-post-types.php.
