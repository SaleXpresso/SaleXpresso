<?php
/**
 * User Taxonomy Helper Functions
 *
 * @author   SaleXpresso
 * @category Core
 * @package  SaleXpresso\Taxonomy
 * @version  1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	header( 'Status: 403 Forbidden' );
	header( 'HTTP/1.1 403 Forbidden' );
	die();
}

use SaleXpresso\SXP_User_Taxonomy;

// Shadow Copy.
if ( ! function_exists( 'get_user_taxonomy_post_type' ) ) {
	/**
	 * Get the post type of shadow copy post for WP User.
	 *
	 * @return string
	 */
	function get_user_taxonomy_post_type() {
		return SXP_User_Taxonomy::POST_TYPE;
	}
}
if ( ! function_exists( 'get_user_taxonomy_post' ) ) {
	/**
	 * Get User's ShadowCopy (post) ID
	 *
	 * @param int  $user_id User id to get the shadow post id for.
	 * @param bool $create Optional. Default false, if true will try to create the shadow post if doesn't exists.
	 *
	 * @return int|false
	 */
	function get_user_taxonomy_post( $user_id, $create = false ) {
		$user_id = absint( $user_id );
		$user    = get_userdata( $user_id );
		if ( false === $user ) {
			return false;
		}
		$shadow_copy = get_user_meta( $user->ID, SXP_User_Taxonomy::POST_KEY, true );
		$shadow_copy = get_post( $shadow_copy );
		if ( is_wp_post( $shadow_copy ) && SXP_User_Taxonomy::POST_TYPE === $shadow_copy->post_type ) {
			return $shadow_copy->ID;
		}
		global $wpdb;
		// search post table for shadow copy.
		$meta_query = get_meta_sql(
			[
				'relation' => 'AND',
				[
					'key'   => SXP_User_Taxonomy::USER_KEY,
					'value' => absint( $user_id ),
				],
			],
			'post',
			$wpdb->posts,
			'id'
		);
		// validate meta query.
		$join        = ! empty( $meta_query['join'] ) ? $meta_query['join'] : '';
		$where       = ! empty( $meta_query['where'] ) ? $meta_query['where'] : '';
		$where_type  = $wpdb->prepare( "AND {$wpdb->posts}.post_type = %s", SXP_User_Taxonomy::POST_TYPE );
		$shadow_copy = (int) $wpdb->get_var( "SELECT ID FROM {$wpdb->posts} {$join} WHERE 1=1 {$where_type} {$where} LIMIT 1" ); // phpcs:ignore WordPress.DB.PreparedSQL.InterpolatedNotPrepared, WordPress.DB.DirectDatabaseQuery.NoCaching, WordPress.DB.DirectDatabaseQuery.DirectQuery
		// create if not exists.
		if ( ! $shadow_copy && true === $create ) {
			$shadow_copy = create_user_taxonomy_post( $user_id, false );
		}
		// update user meta for quick access.
		if ( $shadow_copy ) {
			update_user_meta( $user->ID, SXP_User_Taxonomy::POST_KEY, $shadow_copy );
		}
		
		return $shadow_copy ? $shadow_copy : false;
	}
}
if ( ! function_exists( 'create_user_taxonomy_post' ) ) {
	/**
	 * Create User Shadow Copy.
	 *
	 * @param int  $user_id  User ID.
	 * @param bool $wp_error Optional. Whether to return a WP_Error on failure. Default false.
	 *
	 * @return int|false|WP_Error The post ID on success. The value false or WP_Error on failure.
	 */
	function create_user_taxonomy_post( $user_id, $wp_error = false ) {
		// check before create.
		$sh = get_user_taxonomy_post( $user_id );
		if ( false === $sh ) {
			$user = get_userdata( $user_id );
			if ( false === $user ) {
				if ( $wp_error ) {
					return new WP_Error( 'invalid_user', __( 'Invalid user ID.', 'salexpresso' ) );
				}
				return false;
			}
			// insert the shadow post.
			$sh = wp_insert_post( [
				'post_title'  => $user->display_name,
				'post_name'   => 'shadow-copy-' . $user->user_login,
				'post_type'   => SXP_User_Taxonomy::POST_TYPE,
				'post_status' => 'publish',
			] );
			if ( is_wp_error( $sh ) ) {
				if ( $wp_error ) {
					return $sh;
				}
				return false;
			}
			update_post_meta( $sh, SXP_User_Taxonomy::USER_KEY, $user_id );
			update_user_meta( $user_id, SXP_User_Taxonomy::POST_KEY, $sh );
		}
		// return the id.
		return $sh;
	}
}
if ( ! function_exists( 'sync_user_taxonomy_post' ) ) {
	/**
	 * Sync User Data to shadow copy.
	 *
	 * @param int  $user_id  User ID.
	 * @param bool $create   Optional. Default true, if true it will try to create shadow copy
	 *                       if doesn't exists.
	 * @param bool $wp_error Optional. Whether to return a WP_Error on failure. Default false.
	 *
	 * @return int|false|WP_Error The post ID on success. The value false or WP_Error on failure.
	 */
	function sync_user_taxonomy_post( $user_id, $create = true, $wp_error = false ) {
		$user = get_userdata( $user_id );
		if ( false === $user ) {
			if ( $wp_error ) {
				return new WP_Error( 'invalid_user', __( 'Invalid user ID.', 'salexpresso' ) );
			}
			return false;
		}
		
		$sh = get_user_taxonomy_post( $user_id );
		if ( false !== $sh ) {
			return wp_update_post( [
				'ID'         => $sh,
				'post_title' => $user->display_name,
				'post_name'  => 'shadow-copy-' . $user->user_login,
				'post_type'  => SXP_User_Taxonomy::POST_TYPE,
			], $wp_error );
		} else {
			if ( true === $create ) {
				$sh = create_user_taxonomy_post( $create, true );
				if ( is_wp_error( $sh ) ) {
					if ( $wp_error ) {
						return $sh;
					}
					return false;
				}
				return $sh;
			} else {
				if ( $wp_error ) {
					return new WP_Error( 'invalid_shadow_copy', __( 'Shadow copy does not exists.', 'salexpresso' ) );
				}
				return false;
			}
		}
	}
}
if ( ! function_exists( 'delete_user_taxonomy_post' ) ) {
	/**
	 * Delete User ShadowCopy Post.
	 *
	 * @param int $user_id User ID.
	 *
	 * @return bool return true on success. false on failure or if the shadow doesn't exists.
	 */
	function delete_user_taxonomy_post( $user_id ) {
		$sh = get_user_taxonomy_post( $user_id );
		if ( false !== $sh ) {
			return is_wp_post( wp_delete_post( $sh, true ) );
		}
		return false;
	}
}

// Taxonomy.
if ( ! function_exists( 'get_user_taxonomies' ) ) {
	/**
	 * Get All Users Taxonomies.
	 *
	 * @param string $output Optional. The type of output to return in the array. Accepts either taxonomy 'names'
	 *                       or 'objects'. Default 'names'.
	 * @return string[]|WP_Taxonomy[]
	 */
	function get_user_taxonomies( $output = 'names' ) {
		return get_object_taxonomies( SXP_User_Taxonomy::POST_TYPE, $output );
	}
}
if ( ! function_exists( 'register_user_taxonomy' ) ) {
	/**
	 * Register Taxonomy for WP User.
	 *
	 * Register Taxonomy with the user shadow copy.
	 *
	 * @param string       $taxonomy     Taxonomy key, must not exceed 32 characters.
	 * @param array|string $args         Optional. Array or query string of arguments for registering a taxonomy
	 *                                   Same as the 3rd parameter of register_taxonomy function.
	 * @param array|string $_object_type Optional. Extra object types to attache the taxonomy with.
	 *                                   Default to User ShadowCopy Post Type. if not empty object type
	 *                                   Object type or array of object types with which the taxonomy should be associated.
	 *
	 * @return WP_Error|WP_Taxonomy
	 * @see register_taxonomy()
	 */
	function register_user_taxonomy( $taxonomy, $args, $_object_type = '' ) {
		$_object_type = (array) $_object_type;
		if ( ! in_array( SXP_User_Taxonomy::POST_TYPE, $_object_type ) ) {
			$_object_type[] = SXP_User_Taxonomy::POST_TYPE;
		}
		
		if ( ! isset( $args['update_count_callback'] ) ) {
			$args['update_count_callback'] = '';
		}
		
		if ( ! is_callable( $args['update_count_callback'] ) ) {
			/**
			 * Original Callback
			 *
			 * @see _update_post_term_count
			 */
			$args['update_count_callback'] = '_update_post_term_count';
		}
		
		if ( ! isset( $args['hierarchical'] ) ) {
			$args['hierarchical'] = false;
		}
		
		if ( isset( $args['meta_box_cb'] ) && is_callable( $args['meta_box_cb'] ) ) {
			add_action( "display_user_{$taxonomy}_taxonomy_table", $args['meta_box_cb'], 10, 2 );
		} else {
			if ( $args['hierarchical'] ) {
				add_action( "display_user_{$taxonomy}_taxonomy_table", 'display_user_category_meta_box', 10, 2 );
			} else {
				add_action( "display_user_{$taxonomy}_taxonomy_table", 'display_user_tags_meta_box', 10, 2 );
			}
		}
		
		$args['meta_box_cb'] = null;
		
		if ( isset( $args['meta_box_sanitize_cb'] ) && ! is_callable( $args['meta_box_sanitize_cb'] ) ) {
			$args['meta_box_sanitize_cb'] = null;
		}
		
		return register_taxonomy( $taxonomy, $_object_type, $args );
	}
}
if ( ! function_exists( 'register_taxonomy_for_users' ) ) {
	/**
	 * Add an already registered taxonomy to the WP Users.
	 *
	 * @param string $taxonomy    Name of taxonomy object.
	 * @return bool True if successful, false if not.
	 */
	function register_taxonomy_for_users( $taxonomy ) {
		return register_taxonomy_for_object_type( $taxonomy, SXP_User_Taxonomy::POST_TYPE );
	}
}
if ( ! function_exists( 'unregister_taxonomy_for_users' ) ) {
	/**
	 * Remove an already registered taxonomy from WP Users.
	 *
	 * @param string $taxonomy    Name of taxonomy object.
	 * @return bool True if successful, false if not.
	 */
	function unregister_taxonomy_for_users( $taxonomy ) {
		return unregister_taxonomy_for_object_type( $taxonomy, SXP_User_Taxonomy::POST_TYPE );
	}
}
if ( ! function_exists( 'display_user_category_meta_box' ) ) {
	/**
	 * User Category Metabox.
	 *
	 * @see post_categories_meta_box
	 *
	 * @param int         $shadow_id User of the user being edited, or 0 for new user.
	 * @param WP_Taxonomy $taxonomy  Taxonomy Object.
	 *
	 * @return void
	 */
	function display_user_category_meta_box( $shadow_id, $taxonomy ) {
		$tax_name = esc_attr( $taxonomy->name );
		?>
		<table class="form-table" role="presentation">
			<tr>
				<th><h2><?php echo esc_html( $taxonomy->label ); ?></h2></th>
				<td>
				<div id="taxonomy-<?php echo $tax_name; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>" class="categorydiv">
					<ul id="<?php echo $tax_name; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>-tabs" class="category-tabs">
						<li class="tabs"><a href="#<?php echo $tax_name; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>-all"><?php echo esc_html( $taxonomy->labels->all_items ); ?></a></li>
						<li class="hide-if-no-js"><a href="#<?php echo $tax_name; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>-pop"><?php echo esc_html( $taxonomy->labels->most_used ); ?></a></li>
					</ul>
					<div id="<?php echo $tax_name; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>-pop" class="tabs-panel" style="display: none;">
						<ul id="<?php echo $tax_name; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>checklist-pop" class="categorychecklist form-no-clear" >
							<?php $popular_ids = wp_popular_terms_checklist( $tax_name ); ?>
						</ul>
					</div>
					<div id="<?php echo $tax_name; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>-all" class="tabs-panel">
						<input type='hidden' name='tax_input[<?php echo $tax_name; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>][]' value='0' />
						<ul id="<?php echo $tax_name; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>checklist" data-wp-lists="list:<?php echo $tax_name; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>" class="categorychecklist form-no-clear">
							<?php
							wp_terms_checklist(
								$shadow_id,
								[
									'taxonomy'     => $tax_name,
									'popular_cats' => $popular_ids,
								]
							);
							?>
						</ul>
					</div>
					<?php if ( current_user_can( $taxonomy->cap->edit_terms ) ) : ?>
						<div id="<?php echo $tax_name; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>-adder" class="wp-hidden-children">
							<a id="<?php echo $tax_name; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>-add-toggle" href="#<?php echo $tax_name; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>-add" class="hide-if-no-js taxonomy-add-new">
								<?php
								/* translators: %s: Add New taxonomy label. */
								printf( esc_html__( '+ %s' ), esc_html( $taxonomy->labels->add_new_item ) ); // phpcs:ignore WordPress.WP.I18n.MissingArgDomain
								?>
							</a>
							<p id="<?php echo $tax_name; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>-add" class="category-add wp-hidden-child">
								<label class="screen-reader-text" for="new<?php echo $tax_name; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>"><?php echo esc_html( $taxonomy->labels->add_new_item ); ?></label>
								<input type="text" name="new<?php echo $tax_name; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>" id="new<?php echo $tax_name; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>" class="form-required form-input-tip" value="<?php echo esc_attr( $taxonomy->labels->new_item_name ); ?>" aria-required="true"/>
								<label class="screen-reader-text" for="new<?php echo $tax_name; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>_parent">
									<?php echo esc_html( $taxonomy->labels->parent_item_colon ); ?>
								</label>
								<?php
								$parent_dropdown_args = [
									'taxonomy'         => $tax_name,
									'hide_empty'       => 0,
									'name'             => 'new' . $tax_name . '_parent',
									'orderby'          => 'name',
									'hierarchical'     => 1,
									'show_option_none' => '&mdash; ' . $taxonomy->labels->parent_item . ' &mdash;',
								];
								
								/**
								 * Filters the arguments for the taxonomy parent dropdown on the Post Edit page.
								 *
								 * @since 4.4.0
								 *
								 * @param array $parent_dropdown_args {
								 *     Optional. Array of arguments to generate parent dropdown.
								 *
								 *     @type string   $taxonomy         Name of the taxonomy to retrieve.
								 *     @type bool     $hide_if_empty    True to skip generating markup if no
								 *                                      categories are found. Default 0.
								 *     @type string   $name             Value for the 'name' attribute
								 *                                      of the select element.
								 *                                      Default "new{$tax_name}_parent".
								 *     @type string   $orderby          Which column to use for ordering
								 *                                      terms. Default 'name'.
								 *     @type bool|int $hierarchical     Whether to traverse the taxonomy
								 *                                      hierarchy. Default 1.
								 *     @type string   $show_option_none Text to display for the "none" option.
								 *                                      Default "&mdash; {$parent} &mdash;",
								 *                                      where `$parent` is 'parent_item'
								 *                                      taxonomy label.
								 * }
								 */
								$parent_dropdown_args = apply_filters( 'user_edit_category_parent_dropdown_args', $parent_dropdown_args ); // phpcs:ignore WordPress.NamingConventions.PrefixAllGlobals.NonPrefixedHooknameFound
								
								wp_dropdown_categories( $parent_dropdown_args );
								?>
								<input type="button" id="<?php echo $tax_name; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>-add-submit" data-wp-lists="add:<?php echo $tax_name; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>checklist:<?php echo $tax_name; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>-add" class="button category-add-submit" value="<?php echo esc_attr( $taxonomy->labels->add_new_item ); ?>" />
								<?php wp_nonce_field( 'add-' . $tax_name, '_ajax_nonce-add-' . $tax_name, false ); ?>
								<span id="<?php echo $tax_name; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>-ajax-response"></span>
							</p>
						</div>
					<?php endif; ?>
				</div>
			</td>
			</tr>
		</table>
		<?php
	}
}
if ( ! function_exists( 'display_user_tags_meta_box' ) ) {
	/**
	 * User Tags Metabox.
	 *
	 * @see post_tags_meta_box
	 *
	 * @param int         $shadow_id User of the user being edited, or 0 for new user.
	 * @param WP_Taxonomy $taxonomy  Taxonomy Object.
	 *
	 * @return void
	 */
	function display_user_tags_meta_box( $shadow_id, $taxonomy ) {
		
		$tax_name              = esc_attr( $taxonomy->name );
		$user_can_assign_terms = current_user_can( $taxonomy->cap->assign_terms );
		$comma                 = _x( ',', 'tag delimiter' ); // phpcs:ignore WordPress.WP.I18n.MissingArgDomain
		$terms_to_edit         = $shadow_id ? get_terms_to_edit( $shadow_id, $tax_name ) : false;
		if ( ! is_string( $terms_to_edit ) ) {
			$terms_to_edit = '';
		}
		?>
		<table class="form-table" role="presentation">
			<tr>
				<th><h2><?php echo esc_html( $taxonomy->label ); ?></h2></th>
				<td>
				<div class="tagsdiv" id="<?php echo $tax_name; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>">
					<div class="jaxtag">
						<div class="nojs-tags hide-if-js">
							<label for="tax-input-<?php echo $tax_name; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>"><?php echo esc_html( $taxonomy->labels->add_or_remove_items ); ?></label>
							<p><textarea name="<?php echo 'tax_input[' . $tax_name . ']'; ?>" rows="3" cols="20" class="the-tags" id="tax-input-<?php echo $tax_name; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>" <?php disabled( ! $user_can_assign_terms ); ?> aria-describedby="new-tag-<?php echo $tax_name; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>-desc"><?php
									// textarea_escaped by esc_attr().
									echo str_replace( ',', $comma . ' ', $terms_to_edit ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
							?></textarea></p>
						</div>
						<?php if ( $user_can_assign_terms ) : ?>
							<div class="ajaxtag hide-if-no-js">
								<label class="screen-reader-text" for="new-tag-<?php echo $tax_name; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>"><?php echo esc_html( $taxonomy->labels->add_new_item ); ?></label>
								<input data-wp-taxonomy="<?php echo $tax_name; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>" type="text" id="new-tag-<?php echo $tax_name; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>" name="newtag[<?php echo $tax_name; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>]" class="newtag form-input-tip" size="16" autocomplete="off" aria-describedby="new-tag-<?php echo $tax_name; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>-desc" value="" />
								<input type="button" class="button tagadd" value="<?php esc_attr_e( 'Add' ); // phpcs:ignore WordPress.WP.I18n.MissingArgDomain ?>" />
							</div>
							<p class="howto" id="new-tag-<?php echo $tax_name; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>-desc"><?php echo esc_html( $taxonomy->labels->separate_items_with_commas ); ?></p>
						<?php elseif ( empty( $terms_to_edit ) ) : ?>
							<p><?php echo esc_html( $taxonomy->labels->no_terms ); ?></p>
						<?php endif; ?>
					</div>
					<ul class="tagchecklist" role="list"></ul>
				</div>
				<?php if ( $user_can_assign_terms ) : ?>
					<p class="hide-if-no-js"><button type="button" class="button-link tagcloud-link" id="link-<?php echo $tax_name; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>" aria-expanded="false"><?php echo esc_html( $taxonomy->labels->choose_from_most_used ); ?></button></p>
				<?php endif; ?>
			</td>
			</tr>
		</table>
		<?php
	}
}

// Terms.
if ( ! function_exists( 'wp_get_user_terms' ) ) {
	/**
	 * Retrieves the terms for a user.
	 *
	 * @param int          $user_id  User ID.
	 * @param string|array $taxonomy The taxonomy slug or array of slugs for which to retrieve terms.
	 * @param array        $args     {
	 *     Optional. Term query parameters. See WP_Term_Query::__construct() for supported arguments.
	 *
	 *     @type string $fields Term fields to retrieve. Default 'all'.
	 * }
	 * @return array|int[]|WP_Term[]|WP_Error Array of WP_Term objects on success or empty array if no terms were found.
	 *                        WP_Error object if `$taxonomy` doesn't exist.
	 */
	function wp_get_user_terms( $user_id, $taxonomy, $args = [] ) {
		$sh       = (int) get_user_taxonomy_post( (int) $user_id, false );
		$defaults = [ 'fields' => 'all' ];
		$args     = wp_parse_args( $args, $defaults );
		
		return wp_get_object_terms( $sh, $taxonomy, $args );
	}
}
if ( ! function_exists( 'sxp_get_user_groups' ) ) {
	/**
	 * Retrieve the groups for a user.
	 *
	 * There is only one default for this function, called 'fields' and by default
	 * is set to 'all'. There are other defaults that can be overridden in
	 * sxp_get_user_groups().
	 *
	 * @param int   $user_id User ID.
	 * @param array $args    Optional. Tag query parameters. Default empty array.
	 *                       See WP_Term_Query::__construct() for supported arguments.
	 * @return array|int[]|WP_Term[]|WP_Error Array of WP_Term objects on success or empty array if no tags were found.
	 *                        WP_Error object if 'post_tag' taxonomy doesn't exist.
	 */
	function sxp_get_user_groups( $user_id, $args = [] ) {
		return wp_get_user_terms( $user_id, 'user_group', $args );
	}
}
if ( ! function_exists( 'sxp_get_user_group' ) ) {
	/**
	 * Retrieve the groups for a user.
	 *
	 * There is only one default for this function, called 'fields' and by default
	 * is set to 'all'. There are other defaults that can be overridden in
	 * sxp_get_user_groups().
	 *
	 * @param int|WP_User $user   User ID.
	 * @param string      $output Optional. The required return type. One of OBJECT, ARRAY_A, or ARRAY_N, which correspond to
	 *                            a WP_Term object, an associative array, or a numeric array, respectively. Default OBJECT.
	 * @param string      $filter Optional, default is raw or no WordPress defined filter will applied.
	 *
	 * @return WP_Term|array|WP_Error|false Object of the type specified by `$output` on success. When `$output` is 'OBJECT',
	 *                                     a WP_Term instance is returned. If taxonomy does not exist, a WP_Error is
	 *                                     returned. Returns false for miscellaneous failure.
	 * @see sanitize_term_field() The $context param lists the available values for get_term_by() $filter param.
	 */
	function sxp_get_user_group( $user, $output = OBJECT, $filter = 'raw' ) {
		if ( $user instanceof WP_User ) {
			$user = $user->ID;
		}
		$user   = absint( $user );
		$groups = sxp_get_user_groups( $user, [ 'fields' => 'ids' ] );
		if ( is_wp_error( $groups ) ) {
			return $groups;
		}
		if ( ! empty( $groups ) ) {
			return get_term( $groups[0], 'user_group', $output, $filter );
		}
		return false;
	}
}
if ( ! function_exists( 'sxp_get_user_types' ) ) {
	/**
	 * Retrieve the types for a user.
	 *
	 * There is only one default for this function, called 'fields' and by default
	 * is set to 'all'. There are other defaults that can be overridden in
	 * sxp_get_user_groups().
	 *
	 * @param int   $user_id User ID.
	 * @param array $args    Optional. Tag query parameters. Default empty array.
	 *                       See WP_Term_Query::__construct() for supported arguments.
	 * @return array|int[]|WP_Term[]|WP_Error Array of WP_Term objects on success or empty array if no tags were found.
	 *                        WP_Error object if 'post_tag' taxonomy doesn't exist.
	 */
	function sxp_get_user_types( $user_id, $args = [] ) {
		return wp_get_user_terms( $user_id, 'user_type', $args );
	}
}
if ( ! function_exists( 'sxp_get_user_tags' ) ) {
	/**
	 * Retrieve the tags for a user.
	 *
	 * There is only one default for this function, called 'fields' and by default
	 * is set to 'all'. There are other defaults that can be overridden in
	 * sxp_get_user_groups().
	 *
	 * @param int   $user_id User ID.
	 * @param array $args    Optional. Tag query parameters. Default empty array.
	 *                       See WP_Term_Query::__construct() for supported arguments.
	 * @return array|int[]|WP_Term[]|WP_Error Array of WP_Term objects on success or empty array if no tags were found.
	 *                        WP_Error object if 'post_tag' taxonomy doesn't exist.
	 */
	function sxp_get_user_tags( $user_id, $args = [] ) {
		return wp_get_user_terms( $user_id, 'user_tag', $args );
	}
}
if ( ! function_exists( 'sxp_get_term_background_color' ) ) {
	/**
	 * Get Background Color For Term.
	 *
	 * @param WP_Term|int $term Term.
	 *
	 * @return mixed
	 */
	function sxp_get_term_background_color( $term ) {
		if ( $term instanceof WP_Term ) {
			$term = $term->term_id;
		}
		return get_term_meta( $term, '__sxp_term_color', true );
	}
}

// Set User Terms.
if ( ! function_exists( 'sxp_set_user_groups' ) ) {
	/**
	 * Set User Group.
	 *
	 * @param int    $user_id User ID.
	 * @param string $groups   Tag list to add.
	 * @param bool   $append  Replace or append the tag list.
	 *
	 * @return array|false|WP_Error
	 */
	function sxp_set_user_groups( $user_id, $groups, $append = false ) {
		$sh = (int) get_user_taxonomy_post( (int) $user_id, true );
		return wp_set_post_terms( $sh, $groups, 'user_group', $append );
	}
}
if ( ! function_exists( 'sxp_set_user_types' ) ) {
	/**
	 * Set User Types.
	 *
	 * @param int    $user_id User ID.
	 * @param string $types   Tag list to add.
	 * @param bool   $append  Replace or append the tag list.
	 *
	 * @return array|false|WP_Error
	 */
	function sxp_set_user_types( $user_id, $types = '', $append = false ) {
		$sh = (int) get_user_taxonomy_post( (int) $user_id, true );
		return wp_set_post_terms( $sh, $types, 'user_type', $append );
	}
}
if ( ! function_exists( 'sxp_set_user_tags' ) ) {
	/**
	 * Set User Tags.
	 *
	 * @param int    $user_id User ID.
	 * @param string $tags    Tag list to add.
	 * @param bool   $append  Replace or append the tag list.
	 *
	 * @return array|false|WP_Error
	 */
	function sxp_set_user_tags( $user_id, $tags = '', $append = false ) {
		$sh = (int) get_user_taxonomy_post( (int) $user_id, true );
		return wp_set_post_terms( $sh, $tags, 'user_tag', $append );
	}
}

// CRUD.
if ( ! function_exists( 'sxp_add_user_group' ) ) {
	/**
	 * Create New User Group Term.
	 *
	 * @param string $name Term Name to create a term with.
	 *
	 * @return int|WP_Error
	 */
	function sxp_add_user_group( $name ) {
		$id = wp_create_term( $name, 'user_group' );
		if ( is_wp_error( $id ) ) {
			return $id;
		}
		return $id['term_id'];
	}
}
if ( ! function_exists( 'sxp_add_user_type' ) ) {
	/**
	 * Create New User Type Term.
	 *
	 * @param string $name Term Name to create a term with.
	 *
	 * @return int|WP_Error
	 */
	function sxp_add_user_type( $name ) {
		$id = wp_create_term( $name, 'user_type' );
		if ( is_wp_error( $id ) ) {
			return $id;
		}
		return $id['term_id'];
	}
}
if ( ! function_exists( 'sxp_add_user_tag' ) ) {
	/**
	 * Create New User Tag Term.
	 *
	 * @param string $name Term Name to create a term with.
	 *
	 * @return int|WP_Error
	 */
	function sxp_add_user_tag( $name ) {
		$id = wp_create_term( $name, 'user_tag' );
		if ( is_wp_error( $id ) ) {
			return $id;
		}
		return $id['term_id'];
	}
}
if ( ! function_exists( 'sxp_update_user_group' ) ) {
	/**
	 * Update User Group Term.
	 *
	 * @param int   $term_id Term ID.
	 * @param array $args Data to update.
	 *
	 * @return int|WP_Error
	 */
	function sxp_update_user_group( $term_id, $args ) {
		$id = wp_update_term( $term_id, 'user_group', $args );
		if ( is_wp_error( $id ) ) {
			return $id;
		}
		return $id['term_id'];
	}
}
if ( ! function_exists( 'sxp_update_user_type' ) ) {
	/**
	 * Update User Type Term.
	 *
	 * @param int   $term_id Term ID.
	 * @param array $args Data to update.
	 *
	 * @return int|WP_Error
	 */
	function sxp_update_user_type( $term_id, $args ) {
		$id = wp_update_term( $term_id, 'user_type', $args );
		if ( is_wp_error( $id ) ) {
			return $id;
		}
		return $id['term_id'];
	}
}
if ( ! function_exists( 'sxp_update_user_tag' ) ) {
	/**
	 * Update User Tag Term.
	 *
	 * @param int   $term_id Term ID.
	 * @param array $args Data to update.
	 *
	 * @return int|WP_Error
	 */
	function sxp_update_user_tag( $term_id, $args ) {
		$id = wp_update_term( $term_id, 'user_tag', $args );
		if ( is_wp_error( $id ) ) {
			return $id;
		}
		return $id['term_id'];
	}
}
if ( ! function_exists( 'sxp_delete_user_group' ) ) {
	/**
	 * Delete User Group.
	 *
	 * @param int $term_id Term ID.
	 *
	 * @return bool|int|WP_Error
	 */
	function sxp_delete_user_group( $term_id ) {
		return wp_delete_term( $term_id, 'user_group' );
	}
}
if ( ! function_exists( 'sxp_delete_user_type' ) ) {
	/**
	 * Delete User Type.
	 *
	 * @param int $term_id Term ID.
	 *
	 * @return bool|int|WP_Error
	 */
	function sxp_delete_user_type( $term_id ) {
		return wp_delete_term( $term_id, 'user_tag' );
	}
}
if ( ! function_exists( 'sxp_delete_user_tag' ) ) {
	/**
	 * Delete User Tag.
	 *
	 * @param int $term_id Term ID.
	 *
	 * @return bool|int|WP_Error
	 */
	function sxp_delete_user_tag( $term_id ) {
		return wp_delete_term( $term_id, 'user_tag' );
	}
}

// Term rules.
if ( ! function_exists( 'sxp_get_term_rules' ) ) {
	/**
	 * Get Saved Rules For Term.
	 *
	 * @param WP_Term|int $term Term.
	 *
	 * @return array
	 */
	function sxp_get_term_rules( $term ) {
		if ( $term instanceof WP_Term ) {
			$term = $term->term_id;
		}
		$rules = get_term_meta( $term, '__sxp_term_rules', true );
		return $rules ? (array) $rules : [];
	}
}
if ( ! function_exists( 'sxp_get_compiled_term_rules' ) ) {
	/**
	 * Get Saved Compiled Rules For Term.
	 *
	 * @param WP_Term|int $term Term.
	 *
	 * @return string
	 */
	function sxp_get_compiled_term_rules( $term ) {
		if ( $term instanceof WP_Term ) {
			$term = $term->term_id;
		}
		return get_term_meta( $term, '__sxp_compiled_term_rules', true );
	}
}
if ( ! function_exists( 'sxp_save_term_rules' ) ) {
	/**
	 * Save Rules for Term.
	 *
	 * @param WP_Term|int $term Term.
	 * @param array       $data Rule data.
	 *
	 * @return bool|int|WP_Error
	 */
	function sxp_save_term_rules( $term, $data ) {
		if ( $term instanceof WP_Term ) {
			$term = $term->term_id;
		}
		sxp_save_compiled_term_rules( $term, '' );
		return update_term_meta( $term, '__sxp_term_rules', $data );
	}
}
if ( ! function_exists( 'sxp_save_compiled_term_rules' ) ) {
	/**
	 * Save Rules for Term.
	 *
	 * @param WP_Term|int $term Term.
	 * @param string       $data Rule data.
	 *
	 * @return bool|int|WP_Error
	 */
	function sxp_save_compiled_term_rules( $term, $data ) {
		if ( $term instanceof WP_Term ) {
			$term = $term->term_id;
		}
		return update_term_meta( $term, '__sxp_compiled_term_rules', $data );
	}
}

// Mics.
if ( ! function_exists( 'sxp_get_all_user_groups' ) ) {
	/**
	 * Get All Groups.
	 *
	 * @return WP_Term[]|WP_Error List of WP_Term instances and their children. Will return WP_Error, if any of taxonomies
	 *                                do not exist.
	 */
	function sxp_get_all_user_groups() {
		return get_terms( [
			'taxonomy'   => 'user_group',
			'hide_empty' => false,
		] );
	}
}
if ( ! function_exists( 'sxp_get_all_user_types' ) ) {
	/**
	 * Get All Types.
	 *
	 * @return WP_Term[]|WP_Error List of WP_Term instances and their children. Will return WP_Error, if any of taxonomies
	 *                                do not exist.
	 */
	function sxp_get_all_user_types() {
		return get_terms( [
			'taxonomy'   => 'user_type',
			'hide_empty' => false,
		] );
	}
}
if ( ! function_exists( 'sxp_get_all_user_tags' ) ) {
	/**
	 * Get All Tags.
	 *
	 * @return WP_Term[]|WP_Error List of WP_Term instances and their children. Will return WP_Error, if any of taxonomies
	 *                                do not exist.
	 */
	function sxp_get_all_user_tags() {
		return get_terms( [
			'taxonomy'   => 'user_tag',
			'hide_empty' => false,
		] );
	}
}
// @todo use get_tax_sql() to add tax args to user query.
// End of file user-taxonomy-helper.php.
