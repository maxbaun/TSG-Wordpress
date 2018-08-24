<?php

namespace TSG;

use TSG\Routes;

class Routes
{
	public function __construct() {
		require_once(__DIR__ . '/venues.php');
		require_once(__DIR__ . '/menu.php');
		require_once(__DIR__ . '/page.php');

		new Routes\Venues();
		new Routes\Menu();
		new Routes\Page();
	}
}
