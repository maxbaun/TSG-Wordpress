<?php

namespace TSG;

class Setup
{
	public function __construct() {
		add_action('after_setup_theme', array($this, 'addThemeSupport'));
	}

	public function addThemeSupport() {
		add_theme_support('post-thumbnails');
		add_theme_support('menus');
	}
}
