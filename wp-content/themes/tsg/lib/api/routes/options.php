<?php

namespace TSG\Api\Routes;

class Options
{
	public function getOptions() {
		$data = array();

		$data = get_fields('options');
		wp_send_json_success($data);
	}
}
