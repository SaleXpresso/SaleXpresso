<?php

if ( ! function_exists( 'sxp_product_write_panel_tab' ) ) {
	function sxp_product_write_panel_tab () {
		echo '<li class="product_tabs_lite_tab hide_if_grouped hide_if_external"><a href="#salexpresso_product_tab"><span>' . __( 'SaleXpresso Tab', 'salexpresso' ) . '</span></a></li>';
	}
}

if ( ! function_exists( 'sxp_product_write_panel' ) ) {
	function sxp_product_write_panel () {
		echo '<div id="salexpresso_product_tab" class="panel wc-metaboxes-wrapper woocommerce_options_panel hide_if_grouped hide_if_external">';
		global $post;
		$product = wc_get_product( $post );

		$groups = sxp_get_all_user_groups();
		$cb_ids = [];
		foreach ( $groups as $group ) {
			$cb_id = sprintf( '_sxp_group_%s_no_purchase', $group->term_id );
			$cb_ids[] = $cb_id;
			woocommerce_wp_checkbox( [
				'id'          => $cb_id,
				'label'       => $group->name,
				'description' => sprintf(
					/* translators: 1. User Group Name */
					__( 'Check to disallow %s from purchasing this product.', 'salexpresso' ),
					$group->name
				),
				'value'       => $product->get_meta( $cb_id, true, 'view' ),
				'cbvalue'     => 'yes',
			] );
			
			echo sprintf( '<div class="hide_if_%s">', $cb_id );
			
			woocommerce_wp_text_input( [
				'id'          => sprintf( '_sxp_group_%s_purchase_min_quantity', $group->term_id ),
				'label'       => __( 'Minimum Purchase Quantity', 'salexpresso' ),
				'description' => '',
				'value'       => '',
			] );
			
			woocommerce_wp_text_input( [
				'id'          => sprintf( '_sxp_group_%s_purchase_max_quantity', $group->term_id ),
				'label'       => __( 'Maximum Purchase Quantity', 'salexpresso' ),
				'description' => '',
				'value'       => '',
			] );
			
			echo '</div>';
		}
		if ( ! empty( $cb_ids ) ) {
			?>
			<script>
				(function($) {
					var ids = '<?php echo implode( ',', $cb_ids ); ?>'.split(',');
					
					function show_hide_fn() {
						for( var id of ids ) {
							var _id = $('#' + id + ':checked' ).length;
							if ( _id ) {
								$('.show_if_' + id ).show();
								$('.hide_if_' + id ).hide();
							} else {
								$('.show_if_' + id ).hide();
								$('.hide_if_' + id ).show();
							}
						}
					}
					if ( ids && ids.length ) {
						show_hide_fn();
						$(document).on( 'change', ids.map( function ( id ) { return '#' + id } ).join(','), function () {
							show_hide_fn();
						} );
					}
				})(jQuery);
			</script>
			<?php
		}
		echo '</div>';
	}
}

if ( ! function_exists( 'sxp_product_save_data' ) ) {
	function sxp_product_save_data ( $post_id, $post ) {
		$groups = sxp_get_all_user_groups();
		$product = wc_get_product( $post_id );
		foreach ( $groups as $group ) {
			$_no_purchase = sprintf( '_sxp_group_%s_no_purchase', $group->term_id );
			$min          = sprintf( '_sxp_group_%s_purchase_min_quantity', $group->term_id );
			$max          = sprintf( '_sxp_group_%s_purchase_max_quantity', $group->term_id );
			$no_purchase  = stripslashes( $_POST[ $_no_purchase ] );
			if ( 'yes' == $no_purchase ) {
				// save the meta to the database
				$product->update_meta_data( $_no_purchase, $no_purchase );
				$product->delete_meta_data( $min );
				$product->delete_meta_data( $max );
			} else {
				// clean up if the meta are removed
				$product->delete_meta_data( $_no_purchase );
				if ( isset( $_POST[ $min ] ) && ! empty( $_POST[ $min ] ) ) {
					$min_qty = absint( $_POST[ $min ] );
					if ( $min_qty ) {
						$product->update_meta_data( $min, $min_qty );
					}
				}
				if ( isset( $_POST[ $max ] ) && ! empty( $_POST[ $max ] ) ) {
					$max_qty = absint( $_POST[ $max ] );
					if ( $max_qty ) {
						$product->update_meta_data( $max, $max_qty );
					}
				}
			}
			$product->save();
		}

	}
}

if ( ! function_exists( 'sxp_product_meta_tab' ) ) {
	function sxp_product_meta_tab () {
		add_action( 'woocommerce_product_write_panel_tabs', 'sxp_product_write_panel_tab' );
		add_action( 'woocommerce_product_data_panels',      'sxp_product_write_panel' );
		add_action( 'woocommerce_process_product_meta',     'sxp_product_save_data', 10, 2 );
	}
}

add_action( 'woocommerce_init', 'sxp_product_meta_tab' );
