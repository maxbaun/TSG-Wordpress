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
			'NetlifyProductionHook' => 'https://api.netlify.com/build_hooks/5b6ae494b13fb11454ac2bbc',
			'NetlifyStagingHook' => 'https://api.netlify.com/build_hooks/5b6ae488dd28ef1190386ec8'
		);

		wp_localize_script('deployScripts', 'WordpressGlobalConstants', $constants);
	}
}
