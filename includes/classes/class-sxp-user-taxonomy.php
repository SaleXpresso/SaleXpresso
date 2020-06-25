<?php
/**
 * SaleXpresso
 *
 * @package SaleXpresso
 * @version 1.0.0
 * @since   1.0.0
 */

namespace SaleXpresso;

use WP_Taxonomy;
use WP_User;

if ( ! defined( 'ABSPATH' ) ) {
	header( 'Status: 403 Forbidden' );
	header( 'HTTP/1.1 403 Forbidden' );
	die();
}

/**
 * Class SXP_User_Taxonomy
 * @TODO fix taxonomy term count.
 */
final class SXP_User_Taxonomy {
	
	/**
	 * User Shadow Post Type.
	 *
	 * @var string
	 */
	const POST_TYPE = 'taxonomy_user';
	
	/**
	 * Shadow post meta key for user id.
	 *
	 * @var string
	 */
	const USER_KEY = 'tax_user_id';
	
	/**
	 * User meta key for shadow post id.
	 *
	 * @var string
	 */
	const POST_KEY = 'tax_post_id';
	
	/**
	 * Singleton instance.
	 *
	 * @var SXP_User_Taxonomy
	 */
	protected static $instance;
	
	/**
	 * Singleton instance for this class.
	 *
	 * @return SXP_User_Taxonomy
	 */
	public static function get_instance() {
		if ( is_null( self::$instance ) ) {
			self::$instance = new self();
		}
		
		return self::$instance;
	}
	
	/**
	 * Cloning is forbidden.
	 *
	 * @since 1.0.0
	 */
	public function __clone() {
		sxp_doing_it_wrong( __METHOD__, __( 'Cloning is forbidden.', 'salexpresso' ), '2.1' );
	}
	
	/**
	 * Unserializing instances of this class is forbidden.
	 *
	 * @since 1.0.0
	 */
	public function __wakeup() {
		sxp_doing_it_wrong( __METHOD__, __( 'Unserializing instances of this class is forbidden.', 'salexpresso' ), '2.1' );
	}
	
	/**
	 * SXP_User_Taxonomy constructor.
	 */
	private function __construct() {
		add_action( 'init', [ __CLASS__, 'register' ], -1 );
		add_action( 'registered_taxonomy', [ __CLASS__, 'register_menu' ], -1, 1 );
		
		add_action( 'admin_enqueue_scripts', [ __CLASS__, 'enqueue_scripts' ], -1, 1 );
		
		// profile edit (user editing own profile).
		add_action( 'show_user_profile', [ __CLASS__, 'user_tax_meta_box' ], -1, 1 );
		// user edit (admin editing other user profile).
		add_action( 'edit_user_profile', [ __CLASS__, 'user_tax_meta_box' ], -1, 1 );
		// new user.
		add_action( 'user_new_form', [ __CLASS__, 'user_tax_meta_box' ], -1, 1 );
		
		// save data.
		// personal_options_update, edit_user_profile_update, edit_user_created_user
		// profile_update & user_register covers all of the 3 actions.
		add_action( 'profile_update', [ __CLASS__, 'save' ], 10, 1 );
		add_action( 'user_register', [ __CLASS__, 'save' ], 10, 1 );
	}
	
	/**
	 * Register the shadow post type for user taxonomy.
	 *
	 * @return void
	 */
	public static function register() {
		if ( ! is_blog_installed() || post_type_exists( self::POST_TYPE ) ) {
			return;
		}
		register_post_type(
			self::POST_TYPE,
			apply_filters(
				'salexpresso_user_taxonomy_shadow_post_type',
				[
					'label'               => __( 'Users', 'salexpresso' ),
					'public'              => true,
					'publicly_queryable'  => false,
					'hierarchical'        => false,
					'has_archive'         => false,
					'show_in_rest'        => false,
					'exclude_from_search' => true,
					'supports'            => false,
					'capability_type'     => 'page',
					'map_meta_cap'        => false,
					'capabilities'        => [
						'edit_post'          => 'promote_users',
						'read_post'          => 'read_post',
						'delete_post'        => 'delete_users',
						'edit_posts'         => 'edit_users',
						'edit_others_posts'  => 'edit_users',
						'delete_posts'       => 'delete_users',
						'publish_posts'      => 'promote_users',
						'read_private_posts' => 'read_private_posts',
						'create_posts'       => 'promote_users',
					],
					'rewrite'             => false,
				]
			)
		);
	}
	
	/**
	 * Fires after a taxonomy is registered.
	 *
	 * @param string $taxonomy Taxonomy slug.
	 *
	 * @return void
	 */
	public static function register_menu( $taxonomy ) {
		// taxonomies are already registered.
		$taxonomy = get_taxonomy( $taxonomy );
		
		if ( $taxonomy && ( ! in_array( $taxonomy->name, get_user_taxonomies() ) || ! $taxonomy->show_ui || ! $taxonomy->show_in_menu ) ) {
			return;
		}
		
		add_action( 'admin_menu', function () use ( $taxonomy ) {
			add_users_page(
				$taxonomy->labels->menu_name,
				$taxonomy->labels->menu_name,
				$taxonomy->cap->manage_terms,
				'edit-tags.php?taxonomy=' . $taxonomy->name
			);
		}, 10 );
		add_filter( 'parent_file', function ( $parent = '' ) use ( $taxonomy ) {
			global $pagenow;
			if ( isset( $_GET['taxonomy'] ) && $taxonomy->name === $_GET['taxonomy'] && 'edit-tags.php' === $pagenow ) {  // phpcs:ignore WordPress.Security.NonceVerification.Recommended
				// @TODO handle network admin page, non superadmin admin (how has add-user cap)
				// profile.php.
				$parent = 'users.php';
			}
			
			return $parent;
		}, 10, 1 );
	}
	
	/**
	 * Enqueue admin scripts and styles for user taxonomies.
	 *
	 * @param string $hook current page hook.
	 * @return void
	 */
	public static function enqueue_scripts( $hook ) {
		if ( in_array( $hook, [ 'profile.php', 'user-edit.php', 'user-new.php' ] ) ) {
			wp_enqueue_script( 'post' );
			wp_enqueue_script( 'tags-box' );
			wp_enqueue_script( 'tags-suggest' );
		}
	}
	
	/**
	 * Add Metabox to User edit/add screen.
	 *
	 * @param string|WP_User $user User Object or string.
	 * @return void
	 */
	public static function user_tax_meta_box( $user ) {
		if ( is_multisite() ) {
			// No support for multisite for now.
			// @TODO Multisite support.
			return;
		}
		// get the shadow id.
		$shadow_id = ( $user instanceof WP_User ) ? get_user_taxonomy_post( $user->ID ) : 0;
		// tags-box tags-suggest.
		// loop usr taxonomies.
		foreach ( get_user_taxonomies( 'objects' ) as $taxonomy ) {
			// don't show if tax doesn't have ui or current user can't have permission to to promote user (add/edit).
			if ( ! $taxonomy->show_ui || ! current_user_can( 'promote_users' ) ) {
				continue;
			}
			
			/**
			 * Display User taxonomy Metabox (table)
			 *
			 * @param int         $shadow_id Shadow ID (post)
			 * @param WP_Taxonomy $taxonomy  Taxonomy Object.
			 */
			do_action( "display_user_{$taxonomy->name}_taxonomy_table", $shadow_id, $taxonomy );
		}
	}
	
	/**
	 * Save Use Taxonomies.
	 *
	 * @param int $user_id User id.
	 *
	 * @return void
	 */
	public static function save( $user_id ) {
		if ( ! is_admin() ) {
			// exit if it's not a admin execution as the nonce verification
			// will fail and wp_insert_user will fail to return.
			return;
		}
		// get the shadow id.
		$shadow_id = get_user_taxonomy_post( $user_id, true );
		if ( ! $shadow_id ) {
			return;
		}
		if ( isset( $_POST['tax_input'], $_REQUEST['action'] ) ) {
			$shadow = [ 'ID' => $shadow_id ];
			// validate nonce based on action.
			if ( 'adduser' == $_REQUEST['action'] ) {
				check_admin_referer( 'add-user', '_wpnonce_add-user' );
			}
			if ( 'createuser' == $_REQUEST['action'] ) {
				check_admin_referer( 'create-user', '_wpnonce_create-user' );
			}
			if ( 'update' == $_REQUEST['action'] ) {
				check_admin_referer( 'update-user_' . $user_id );
			}
			
			$shadow['tax_input'] = [];
			foreach ( (array) $_POST['tax_input'] as $taxonomy => $terms ) { // phpcs:ignore WordPress.Security.ValidatedSanitizedInput.InputNotSanitized
				$tax_object = get_taxonomy( $taxonomy );
				if ( $tax_object && isset( $tax_object->meta_box_sanitize_cb ) ) {
					$shadow['tax_input'][ $taxonomy ] = call_user_func_array( $tax_object->meta_box_sanitize_cb, array( $taxonomy, $terms ) );
				}
			}
			if ( isset( $shadow['tax_input'] ) ) {
				wp_update_post( $shadow );
			}
		}
	}
}
// Initialize.
SXP_User_Taxonomy::get_instance();
// End of file class-sxp-user-taxonomy.php.
