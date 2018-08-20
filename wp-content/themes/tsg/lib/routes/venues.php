<?php

namespace TSG\Routes;

class Venues
{
	public function __construct() {
		add_action('rest_api_init', function () {
			register_rest_route('tsg/v1', '/venues/', array(
				'methods' => 'GET',
				'callback' => array($this, 'getVenues'),
			));
			register_rest_route('tsg/v1', '/venueImages/', array(
				'methods' => 'GET',
				'callback' => array($this, 'getVenueImages'),
			));
		});
	}

	public function getVenues() {
		$venues = get_posts(array(
			'post_type' => 'venue',
			'orderby' => 'title',
			'order' => 'ASC',
			'posts_per_page' => -1
		));

		$data = array();

		foreach ($venues as $venue) {
			$image = wp_get_attachment_image_src(get_post_thumbnail_id($venue->ID));
			$data[] = array(
				'id' => $venue->ID,
				'slug' => $venue->post_name,
				'title' => $venue->post_title,
				'image' => $image ? $image[0] : ''
			);
		}
		wp_send_json_success($data);
	}

	public function getVenueImages($request) {
		$id = $request->get_param('id');

		$data = array(
			'gallery' => get_field('venueGallery', $id),
			'location' => get_field('venueLocation', $id)
		);

		wp_send_json_success($data);
	}
}
