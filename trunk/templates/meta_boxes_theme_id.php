<?php

$value = get_post_meta(get_the_ID(), 'hw_theme_switcher_theme_id', true);


?>
<label for="<?php echo HW_THEME_SWITCHER_PREFIX ?>_theme_id"><?php echo __("Switch to theme", 'hw_theme_switcher') ?></label>
<select id="<?php echo HW_THEME_SWITCHER_PREFIX ?>_theme_id" name="<?php echo HW_THEME_SWITCHER_PREFIX ?>_theme_id">
	<?php foreach (wp_get_themes(array('errors' => false, 'allowed' => null, 'blog_id' => 0)) as $theme):
		/** @var $theme WP_Theme */
		$append = hw_theme_switcher()->getStr_currentThemeSlug() == $theme->get('TextDomain') ? ' (current)' : '';
		echo '<option value="' . $theme->get('TextDomain') . '" ' . ($value == $theme->get('TextDomain') ? 'selected' : '') . '>' . $theme->get('Name') . $append . '</option>';
	endforeach; ?>
</select>