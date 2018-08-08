<?php

namespace TSG;

class NetlifyDeploy
{
	public function __construct() {
		require_once(__DIR__ . '/admin-scripts.php');
		require_once(__DIR__ . '/admin-bar.php');

		new NetlifyDeployAdminScripts();
		new NetlifyDeployAdminBar();
	}
}
