<?php

namespace TSG;

class NetlifyDeployAdminScripts
{
	public function __construct() {
		add_action('admin_enqueue_scripts', array($this, 'adminScripts'));
	}

	public function adminScripts() {
		wp_enqueue_script('deployScripts', get_template_directory_uri() . '/lib/deploy/deploy.js', null, true);

		$constants = array(
			'NetlifyProductionHook' => 'https://api.netlify.com/build_hooks/5b86ba05c6aed67f4f6d5f7c',
			'NetlifyStagingHook' => 'https://api.netlify.com/build_hooks/5b86b9fdc6aed646e66d6008'
		);

		wp_localize_script('deployScripts', 'WordpressGlobalConstants', $constants);
	}
}
