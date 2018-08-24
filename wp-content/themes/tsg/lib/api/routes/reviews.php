<?php

namespace TSG\Api\Routes;

use TSG\Api\Utils\Transform;

class Reviews
{
	public function getReview($req) {
		$slug = $req->get_param('slug');

		$reviews = get_posts(array(
			'post_type' => 'review',
			'name' => $slug
		));

		$review = $this->transformReview($reviews[0]);

		wp_send_json_success($review);
	}

	private function transformReview($review) {
		if (!isset($review) || empty($review)) {
			return array();
		}

		return array(
			'id' => $review->ID,
			'title' => $review->post_title,
			'content' => apply_filters('the_content', $review->post_content),
			'excerpt' => $review->post_excerpt,
			'slug' => $review->post_name,
			'services' => get_field('reviewServices', $review->ID),
			'rating' => get_field('reviewRating', $review->ID),
			'location' => get_field('reviewLocation', $review->ID)
		);
	}
}
