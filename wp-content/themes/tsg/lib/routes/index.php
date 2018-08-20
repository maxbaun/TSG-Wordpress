<?php

namespace TSG;

use TSG\Routes;

class Routes
{
	public function __construct() {
		require_once(__DIR__ . '/venues.php');

		new Routes\Venues();
	}
}
