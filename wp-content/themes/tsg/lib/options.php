<?php

namespace TSG;

/**
 * Options
 */
class Options
{

	public function __construct() {
		if (function_exists('acf_add_options_page')) {
			acf_add_options_page(array(
				'page_title' 	=> 'Theme General Settings',
				'menu_title'	=> 'Theme Settings',
				'menu_slug' 	=> 'theme-general-settings',
				'capability'	=> 'edit_posts',
				'autoload'  	=> true,
				'redirect'		=> false
			));

			acf_add_options_sub_page(array(
				'page_title' 	=> 'Common Settings',
				'menu_title'	=> 'Common Settings',
				'parent_slug'	=> 'theme-general-settings',
			));

			acf_add_options_sub_page(array(
				'page_title' 	=> 'Footer Settings',
				'menu_title'	=> 'Footer Settings',
				'parent_slug'	=> 'theme-general-settings',
			));

			acf_add_options_sub_page(array(
				'page_title' 	=> 'Contact Settings',
				'menu_title'	=> 'Contact Settings',
				'parent_slug'	=> 'theme-general-settings',
			));
		}
	}
}
