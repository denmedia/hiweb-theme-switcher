<?php
	/**
	 * Created by PhpStorm.
	 * User: d9251
	 * Date: 15.04.2016
	 * Time: 18:07
	 */


	add_action( 'init', '_hw_theme_switcher_register_post_type' );

	function _hw_theme_switcher_register_post_type() {
		register_post_type( HW_THEME_SWITCHER_POST_TYPE, array(
			'labels' => array(
				'name' => 'hiWeb Theme Switcher',
				'singular_name' => 'Rule',
				'add_new' => 'Add rule',
				'add_new_item' => 'Add new rule',
				'edit_item' => 'Edit rule',
				'new_item' => 'New rule',
				'view_item' => 'View rule',
				'search_items' => 'Search rule',
				'not_found' => 'Rules not found',
				'not_found_in_trash' => 'Not found in trash',
				'menu_name' => 'hiWeb Theme Switcher'
			),
			'public' => false,
			'publicly_queryable' => null,
			'exclude_from_search' => null,
			'show_ui' => true,
			'show_in_menu' => 'themes.php',
			'menu_position' => null,
			'menu_icon' => null,
			//'capability_type'   => 'post',
			//'capabilities'      => 'post', // массив дополнительных прав для этого типа записи
			//'map_meta_cap'      => null, // Ставим true чтобы включить дефолтный обработчик специальных прав
			'hierarchical' => false,
			'supports' => array( 'title' ),
			'taxonomies' => array(),
			'has_archive' => false,
			'rewrite' => true,
			'query_var' => true,
			'show_in_nav_menus' => null
		) );
	}
	
	
	add_action('save_post', '_hw_theme_switcher_register_post_type_save');
	
	function _hw_theme_switcher_register_post_type_save($post_id){
		if ( wp_is_post_revision( $post_id ) )
			return;
		if( !isset($_POST['post_type']) || $_POST['post_type'] != 'hw_theme_switcher' ) return;
		///
		update_post_meta($post_id, 'hw_theme_switcher_theme_id', $_POST['hw_theme_switcher_theme_id']);
		update_post_meta($post_id, 'hw_theme_switcher_post_types', $_POST['hw_theme_switcher_post_types']);
		update_post_meta($post_id, 'hw_theme_switcher_post_ids', $_POST['hw_theme_switcher_post_ids']);
	}