<?php


	add_action('wp_ajax_hw_theme_switcher','hw_theme_switch_posts');
	//add_action('wp_ajax_nopriv_hw_theme_switcher','hw_theme_switch_posts');

	function hw_theme_switch_posts(){
		$posts = get_posts(array('posts_per_page' => 999, 'post_type' => $_POST['postTypes']));
		$R = array();
		foreach($posts as $P){
			$R[$P->post_type][] = array('value' => $P->ID, 'text' => $P->post_title);
		}
		echo json_encode($R);
		die;
	}