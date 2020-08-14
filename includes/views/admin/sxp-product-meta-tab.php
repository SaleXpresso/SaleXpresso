<?php

if ( ! function_exists( 'sxp_product_write_panel_tab' ) ) {
	function sxp_product_write_panel_tab () {
		echo '<li class="product_tabs_lite_tab"><a href="#salexpresso_product_tab"><span>' . __( 'SaleXpresso Tab', 'salexpresso' ) . '</span></a></li>';
	}
}

if ( ! function_exists( 'sxp_product_write_panel' ) ) {
	function sxp_product_write_panel () {
		echo '<div id="salexpresso_product_tab" class="panel wc-metaboxes-wrapper woocommerce_options_panel">';
		global $post;
		$product = wc_get_product( $post );

		$groups = sxp_get_all_user_groups();
		foreach ( $groups as $group ) {
			$group_name = $group->name;
			$group_ID   = $group->term_id;

			woocommerce_wp_checkbox( array(
				'id'          => '_sxp_usergroup_purchase_ability_' . $group_ID,
				'label'       => __( $group_name, 'salexpresso' ),
				'description' => __( 'If checked this user group does not purchase the product', 'salexpresso' ),
			));

			$usergroup_purchase_ability = $product->get_meta( '_sxp_usergroup_purchase_ability_' . $group_ID, true, 'view' );

			if ( $usergroup_purchase_ability === '' ) {
				woocommerce_wp_text_input( array(
					'id'          => '_sxp_usergroup_quantity_' . $group_ID,
					'label'       => __( 'Purchase Quantity', 'salexpresso' ),
					'description' => __( 'Rules', 'salexpresso' ),
					'value'       => ''
				));

				woocommerce_wp_text_input( array(
					'id'          => '_sxp_usergroup_amount_' . $group_ID,
					'label'       => __( 'Purchase Amount', 'salexpresso' ),
					'description' => __( 'Rules', 'salexpresso' ),
					'value'       => ''
				));
			}
		}

		echo '</div>';
	}
}

if ( ! function_exists( 'sxp_product_save_data' ) ) {
	function sxp_product_save_data ( $post_id, $post ) {
		$product = wc_get_product( $post_id );

		$groups = sxp_get_all_user_groups();
		foreach ( $groups as $group ) {
			$group_ID   = $group-> term_id;
			$sxp_usergroup_purchase_ability   = stripslashes( $_POST['_sxp_usergroup_purchase_ability_' . $group_ID] );

			if ( empty( $sxp_usergroup_purchase_ability ) ) {
				// clean up if the meta are removed
				$product->delete_meta_data( '_sxp_usergroup_purchase_ability_' . $group_ID );
				$product->save();
			} elseif ( ! empty( $sxp_usergroup_purchase_ability ) ) {
				// save the meta to the database
				$product->update_meta_data( '_sxp_usergroup_purchase_ability_' . $group_ID, $sxp_usergroup_purchase_ability );
				$product->save();
			}

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
