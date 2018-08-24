<?php

namespace TSG\Api\Routes;

use TSG\Api\Utils\Transform;

class Posts
{
	public function getPosts($req) {
		$perPage = !empty($req->get_param('perPage')) ? $req->get_param('perPage') : 10;
		$page = !empty(intval($req->get_param('page'))) ? intval($req->get_param('page')) : 1;
		$slug = $req->get_param('slug');

		$offset = ($page - 1) * $perPage;

		$posts = get_posts(array(
			'post_type' => 'post',
			'orderby' => 'post_date',
			'order' => 'DESC',
			'posts_per_page' => $perPage,
			'offset' => $offset,
			'name' => $slug
		));

		$data = array();

		foreach ($posts as $post) {
			$data[] = $this->transformPost($post);
		}

		$totalPosts = wp_count_posts('post');

		$total = intval($totalPosts->publish);
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

	private function transformPost($post) {
		return array(
			'id' => $post->ID,
			'slug' => $post->post_name,
			'title' => $post->post_title,
			'content' => apply_filters('the_content', $post->post_content),
			'excerpt' => $post->post_excerpt,
			'image' => Transform::getFeaturedMedia($post)
		);
	}
}
