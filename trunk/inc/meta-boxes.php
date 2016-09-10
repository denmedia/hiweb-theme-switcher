<?php


	add_action( 'add_meta_boxes', '_hw_theme_switcher_add_meta_boxes' );

	function _hw_theme_switcher_add_meta_boxes() {
		add_meta_box( 'hw_theme_switcher_meta_box', 'Rule Options', '_hw_theme_switcher_add_meta_boxes_echo', 'hw_theme_switcher', 'normal' );
	}


	function _hw_theme_switcher_add_meta_boxes_echo() {
		wp_nonce_field( plugin_basename( __FILE__ ), 'hw_theme_switcher' );

		wp_register_style( 'chosen-min', plugin_dir_url( dirname( __FILE__ ) ) . '/asset/chosen.min.css' );
		wp_enqueue_style( 'chosen-min' );
		wp_register_script( 'chosen-jquery-min', plugin_dir_url( dirname( __FILE__ ) ) . '/asset/chosen.jquery.min.js', 'jquery' );
		wp_enqueue_script( 'chosen-jquery-min' );

		wp_register_style( 'jquery-tabselect-min', plugin_dir_url( dirname( __FILE__ ) ) . '/asset/jquery.tabSelect.min.css' );
		wp_enqueue_style( 'jquery-tabselect-min' );
		wp_register_script( 'jquery-tabselect-min', plugin_dir_url( dirname( __FILE__ ) ) . '/asset/jquery.tabSelect.min.js', 'jquery' );
		wp_enqueue_script( 'jquery-tabselect-min' );

		wp_register_script( 'hw-theme-switcher-backend', plugin_dir_url( dirname( __FILE__ ) ) . '/inc/backend.js', 'jquery' );
		wp_enqueue_script( 'hw-theme-switcher-backend' );

		include HW_THEME_SWITCHER_DIR . '/templates/meta_boxes.php';
	}