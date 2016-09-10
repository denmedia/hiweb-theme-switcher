<?php
	$excludePostTypes = array('hw_theme_switcher' => 0,'revision' => 1,'nav_menu_item' => 2);
    $post_meta = get_post_meta(get_the_ID(), 'hw_theme_switcher_post_types', true);
    if(is_array($post_meta)) $values = array_flip($post_meta);
?>
<label>Select post types :
	<select id="<?php echo HW_THEME_SWITCHER_PREFIX . '_post_types' ?>" name="<?php echo HW_THEME_SWITCHER_PREFIX ?>_post_types[]" multiple="multiple">
		<?php foreach( get_post_types() as $postType ): if(isset($excludePostTypes[$postType])) continue; ?>
			<option value="<?php echo $postType ?>" <?php echo isset($values[$postType]) ? 'selected' : '' ?>><?php echo $postType ?></option>
		<?php endforeach; ?>
	</select>
	<div id="<?php echo HW_THEME_SWITCHER_PREFIX ?>_post_types_tabs"></div>
</label>