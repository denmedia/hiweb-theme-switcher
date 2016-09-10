<?php
	/**
	 * Created by PhpStorm.
	 * User: hiweb
	 * Date: 21.05.2016
	 * Time: 17:47
	 */


	foreach (hw_theme_switcher()->getArr_rulePostTypes() as $postType) {

		if ($postType == 'post') $postType = 'posts';
		if ($postType == 'page') $postType = 'pages';

		add_action('manage_' . $postType . '_columns', '_hw_theme_switcher_columns', 10, 2);
		add_action('manage_' . $postType . '_custom_column', '_hw_theme_switcher_custom_column', 10, 2);

	}


	function _hw_theme_switcher_columns($columns) {
		$columns[HW_THEME_SWITCHER_PREFIX . '_column'] =  (get_current_screen()->post_type == HW_THEME_SWITCHER_POST_TYPE) ? 'Switch to Theme' : 'Theme Switch';
		return $columns;
	}

	function _hw_theme_switcher_custom_column($column, $post_id) {
		if ($column == HW_THEME_SWITCHER_PREFIX . '_column') {
			if (get_current_screen()->post_type == HW_THEME_SWITCHER_POST_TYPE) {
				$rules = hw_theme_switcher()->getArr_rules();
				if(isset($rules[$post_id])) {
					echo '<span class="dashicons dashicons-admin-appearance" title="Theme Switcher Rule: ' . $rules[$post_id]['theme_slug'] . '"></span> ' . $rules[$post_id]['theme']->Name;
				}
			} else {
				$rule = hw_theme_switcher()->get_ruleByPostId($post_id);
				if (is_array($rule)) {
					echo '<span class="dashicons dashicons-admin-appearance" title="Theme Switcher Rule: ' . $rule['theme_slug'] . '"></span> ' . $rule['theme']->Name;
				}
			}
		}
	}