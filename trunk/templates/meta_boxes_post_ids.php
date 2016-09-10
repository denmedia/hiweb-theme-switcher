<label for="<?php echo HW_THEME_SWITCHER_PREFIX ?>_post_ids"><?php echo __( "Switch on select posts", 'hw_theme_switcher' ) ?></label>
<input type="hidden" value="<?php echo get_post_meta( get_the_ID(), HW_THEME_SWITCHER_PREFIX . '_posts', true ) ?>">
<select id="<?php echo HW_THEME_SWITCHER_PREFIX ?>_post_ids" multiple="multiple" name="<?php echo HW_THEME_SWITCHER_PREFIX ?>_post_ids[]">
	<?php
		$postTypes =  get_post_meta( get_the_ID(), 'hw_theme_switcher_post_types', true );
		$values = array_flip( get_post_meta( get_the_ID(), 'hw_theme_switcher_post_ids', true ) );
		$posts = get_posts( array( 'posts_per_page' => 999, 'post_type' => $postTypes ) );
		$groupPosts = array();
		foreach( $posts as $post ){
			$groupPosts[ $post->post_type ][] = $post;
		}
		foreach( $groupPosts as $groupName => $group ): ?>
			<optgroup label="<?php echo $groupName ?>">
				<?php foreach($group as $post){
					?><option value="<?php echo $post->ID ?>" <?php echo isset($values[$post->ID]) ? 'selected' : '' ?>><?php echo $post->post_title ?></option><?php
				} ?>
			</optgroup>
		<?php endforeach; ?>
</select>
<div>Select pages or posts on which you want to change the theme</div>