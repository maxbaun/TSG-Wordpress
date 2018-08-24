<?php

namespace TSG\Api\Routes;

use TSG\Api\Utils\Transform;

class DJs
{
	public function getDjs($req) {
		$slug = $req->get_param('slug');

		$djs = get_posts(array(
			'post_type' => 'dj',
			'posts_per_page' => -1
		));

		$djs = array_map(array($this, 'transformDj'), $djs);

		wp_send_json_success($djs);
	}

	private function transformDj($dj) {
		if (!isset($dj) || empty($dj)) {
			return array();
		}

		return array(
			'id' => $dj->ID,
			'title' => $dj->post_title,
			'content' => apply_filters('the_content', $dj->post_content),
			'excerpt' => $dj->post_excerpt,
			'slug' => $dj->post_name,
			'image' => Transform::getFeaturedMedia($dj)
		);
	}
}
