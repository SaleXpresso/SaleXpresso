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
		global $wpdb;
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
				'post_title' => $user->display_name,
				'post_name'  => 'shadow-copy-' . $user->user_login,
				'post_type'  => SXP_User_Taxonomy::POST_TYPE,
			] );
			if ( is_wp_error( $sh ) ) {
				if ( $wp_error ) {
					return  $sh;
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
// @todo use get_tax_sql() to add tax args to user query
// End of file user-taxonomy-helper.php.
