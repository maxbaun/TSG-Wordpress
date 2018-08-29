<?php

namespace TSG\Api\Routes;

use TSG\Api\Utils\Transform;
use TSG\Yoast;

class Venues
{
	public function __construct() {
		$this->yoast = new Yoast();
	}
	public function getVenues($req) {
		$perPage = !empty($req->get_param('posts_per_page')) ? $req->get_param('posts_per_page') : 24;
		$page = !empty(intval($req->get_param('page'))) ? intval($req->get_param('page')) : 1;

		$offset = ($page - 1) * $perPage;

		$venues = get_posts(array(
			'post_type' => 'venue',
			'orderby' => 'title',
			'order' => 'ASC',
			'posts_per_page' => -1
		));

		$data = array();

		foreach ($venues as $venue) {
			$data[] = $this->transformVenue($venue);
		}

		$totalVenues = wp_count_posts('venue');

		$total = intval($totalVenues->publish);
		$perPage = $perPage;
		$previousPage = 1;
		$currentPage = $page;
		$nextPage = $currentPage + 1;
		$totalPages = ceil($total / $perPage);

		if ($currentPage != 1) {
			$previousPage = $currentPage - 1;
		}

		if ($nextPage > $totalPages) {
			$nextPage = $totalPages;
		}

		$meta = array(
			'perPage' => $perPage,
			'nextPage' => $nextPage,
			'previousPage' => $previousPage,
			'totalPages' => $totalPages,
			'total' => $total,
			'currentPage' => $currentPage
		);

		$res = array(
			'data' => $data,
			'meta' => $meta
		);

		$res = $data;

		wp_send_json($res);
	}

	public function getVenue($request) {
		$slug = $request->get_param('slug');

		$venues = get_posts(array(
			'name' => $slug,
			'post_type' => 'venue'
		));

		$venue = $venues[0];

		$venueData = $this->transformVenue($venue);
		$acfData = array(
			'gallery' => array_map(function ($img) {
				return array(
					'id' => $img['ID'],
					'url' => $img['url'],
					'mediaDetails' => array(
						'height' => $img['height'],
						'width' => $img['width']
					)
				);
			}, get_field('venueGallery', $venue->ID))
		);

		$data = array_merge($venueData, $acfData);

		wp_send_json_success($data);
	}

	private function transformVenue($venue) {
		$vid = $venue->ID;
		$image = Transform::getFeaturedMedia($venue);

		return array(
			'id' => $venue->ID,
			'slug' => $venue->post_name,
			'title' => $venue->post_title,
			'link' => get_permalink($venue->ID),
			'image' => array(
				'id' => $image['id'],
				'url' => $image['url'],
				'mediaDetails' => array(
					'height' => $image['height'],
					'width' => $image['width']
				)
			),
			'location' => get_field('venueLocation', $venue->ID),
			'yoast' => $this->yoast->getYoastData($venue)
		);
	}
}
