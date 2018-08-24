<?php

namespace TSG\Api\Routes;

use TSG\Api\Utils\Transform;

class Venues
{
	public function getVenues($req) {
		$perPage = !empty($req->get_param('perPage')) ? $req->get_param('perPage') : 24;
		$page = intval($req->get_param('page'));

		$offset = ($page - 1) * $perPage;

		$venues = get_posts(array(
			'post_type' => 'venue',
			'orderby' => 'title',
			'order' => 'ASC',
			'posts_per_page' => $perPage,
			'offset' => $offset
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

		wp_send_json_success($res);
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
				return Transform::transformImage($img);
			}, get_field('venueGallery', $venue->ID))
		);

		$data = array_merge($venueData, $acfData);

		wp_send_json_success($data);
	}

	private function transformVenue($venue) {
		return array(
			'id' => $venue->ID,
			'slug' => $venue->post_name,
			'title' => $venue->post_title,
			'image' => Transform::getFeaturedMedia($venue),
			'location' => get_field('venueLocation', $venue->ID)
		);
	}
}
