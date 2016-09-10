<?php
	/**
	 * Created by PhpStorm.
	 * User: denmedia
	 * Date: 16.10.15
	 * Time: 13:24
	 */
	
	/*
	Plugin Name: hiWeb Theme Switcher
	Plugin URI: http://hiweb.moscow/theme-switcher
	Description: Changing the theme on selected pages and posts
	Version: 1.0.1.0
	Author: Den Media
	Author URI: http://hiweb.moscow
	*/
	
	
	include_once 'inc/define.php';
	include_once 'inc/class.php';
	include_once 'inc/post-type.php';
	include_once 'inc/ajax.php';
	include_once 'inc/columns.php';
	include_once 'inc/meta-boxes.php';

	if (!is_admin()) hw_theme_switcher()->init();
