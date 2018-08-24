<?php

namespace TSG\Api\Utils;

class Transform
{
	public static function transformImage($image) {
		if (empty($image)) {
			return null;
		}

		return array(
			'id' => isset($image['id']) ? $image['id'] : null,
			'url' => isset($image['url']) ? $image['url'] : null,
			'width' => $image['width'],
			'height' => $image['height']
		);
	}

	public static function transformVideo($video) {
		if (empty($video) || empty($video[0]) || empty($video[0]['url'])) {
			return null;
		}

		$vid = $video[0];

		return array(
			'thumbnail' => self::transformImage($vid['thumbnail']),
			'url' => $vid['url']
		);
	}

	public static function transformContent($content) {
		if (empty($content) || empty($content[0])) {
			return array();
		}

		$cont = $content[0];

		return $cont;
	}

	public static function getFeaturedMedia($post) {
		$imgId = get_post_thumbnail_id($post->ID);

		if (empty($imgId)) {
			return null;
		}

		$img = wp_get_attachment_metadata(get_post_thumbnail_id($post->ID));

		if (empty($img)) {
			return null;
		}
		$imgMeta = self::transformImage($img);
		$imgUrl = array('id' => $imgId, 'url' => wp_get_attachment_url($imgId));

		return array_merge($imgMeta, $imgUrl);
	}

	public static function transformSectionContent($rows) {
		$data = array();
		foreach ($rows as $row) {
			switch ($row['acf_fc_layout']) {
				case 'image':
					$data[] = array(
						'image' => self::transformImage($row['image'])
					);
					break;
				case 'video':
					$data[] = array(
						'video' => self::transformVideo($row['video'])
					);
					break;
				case 'content':
					$data[] = array(
						'content' => self::transformContent($row['content'])
					);
					break;
				default:
					break;
			}
		}

		return $data;
	}

	public static function transformReview($review) {
		return array(
			'id' => $review->ID,
			'title' => $review->post_title,
			'excerpt' => $review->post_excerpt,
			'content' => $review->post_content,
			'url' => get_permalink($review->ID),
			'location' => get_field('reviewLocation', $review->ID)
		);
	}

	public static function transformBio($bio) {
		// return $bio;
		return array(
			'id' => $bio->ID,
			'title' => $bio->post_title,
			'excerpt' => $bio->post_excerpt,
			'content' => $bio->post_content,
			'url' => get_permalink($bio->ID),
			'image' => self::getFeaturedMedia($bio)
		);
	}

	public static function transformAward($award) {
		return array(
			'image' => self::transformImage($award['image']),
			'link' => $award['link']
		);
	}

	public static function transformService($service) {
		return array(
			'image' => self::transformImage($service['image']),
			'title' => $service['title'],
			'text' => $service['text']
		);
	}

	public static function transformTool($tool) {
		return array(
			'image' => self::transformImage($tool['image']),
			'title' => $tool['title'],
			'text' => $tool['text'],
			'link' => $tool['link']
		);
	}
}
