<?php

namespace TSG\Api\Routes;

use TSG\Api\Utils;

class Page
{
	public function getPage($req) {
		// wp_send_json_success('here');
		$page = get_page_by_path($req->get_param('slug'));

		$flexContent = new Utils\FlexibleContent($page->ID);

		$page->template = get_page_template_slug($page->ID);
		$page->slug = $page->post_name;

		$page->acf = array(
			'flexibleContent' => $flexContent->getContent(),
			'hasCallToAction' => get_field('hasCallToAction', $page->ID)
		);

		wp_send_json_success($page);
	}
}
