<?php

namespace TSG;

class NetlifyDeployAdminBar
{
	public function __construct() {
		add_action('admin_bar_menu', array($this, 'addDeployMenu'), 100);
	}

	public function addDeployMenu($adminBar) {
		$adminBar->add_menu(array(
			'id' => 'netlifyDeploy',
			'title' => 'Deploy To'
		));

		$adminBar->add_menu(array(
			'id' => 'netlifyDeployStaging',
			'title' => 'Staging',
			'parent' => 'netlifyDeploy',
			'href' => '#',
			'meta' => array(
				'class' => 'netlifyDeployStaging',
				'id' => 'netlifyDeployStaging'
			)
		));

		$adminBar->add_menu(array(
			'id' => 'netlifyDeployProduction',
			'title' => 'Production',
			'parent' => 'netlifyDeploy',
			'href' => '#',
			'meta' => array(
				'class' => 'netlifyDeployProduction',
				'id' => 'netlifyDeployProduction'
			)
		));

		return $adminBar;
	}
}
