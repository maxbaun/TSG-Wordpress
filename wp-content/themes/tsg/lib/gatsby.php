<?php

namespace TSG;

class Gatsby
{
	public function __construct() {
		add_filter('acf/format_value/type=image', array($this, 'returnNull'), 100, 3);
		add_filter('acf/format_value/type=repeater', array($this, 'returnNull'), 100, 3);
		add_filter('acf/format_value/type=group', array($this, 'returnNull'), 100, 3);
		add_filter('acf/format_value/type=component_field', array($this, 'returnNull'), 100, 3);
		add_filter('acf/format_value/type=flexible_content', array($this, 'returnNull'), 100, 3);
		add_filter('acf/format_value/type=image', array($this, 'returnNull'), 100, 3);
		add_filter('acf/format_value/type=link', array($this, 'returnNull'), 100, 3);

		add_filter('acf/format_value/type=relationship', array($this, 'returnRelationship'), 100, 3);

		add_action('rest_api_init', array($this, 'addMenuOrderToResponse'));
	}

	public function returnNull($value, $post_id, $field) {
		if (empty($value)) {
			return null;
		}

		return $value;
	}

	public function returnRelationship($value, $post_id, $field) {
		if (empty($value)) {
			return null;
		}

		$newValues = array();
		foreach ($value as $v) {
			$v->featured_media = wp_get_attachment_image_url(get_post_thumbnail_id($v->ID), 'full');
			$v->url = get_permalink($v);
			$v->acf = get_fields($v);
			$newValues[] = $v;
		}

		return $newValues;
	}

	public function addMenuOrderToResponse() {
		register_rest_field(
			array('dj'), // add to these post types
			'menu_order', // name of field
			array(
				'get_callback' => function ($post) {
					return intval(get_post_field('menu_order', $post['id'])); // value of field
				}
			)
		);
	}

	public function prepareYoast($data, $post, $context) {
		$yoastMeta = array(
			'focusKw' => $this->getYoastMeta($post, 'focuskw', true),
			'linkdex' => $this->getYoastMeta($post, 'linkdex', true),
			'title' => $this->getYoastMeta($post, 'title', true),
			'metaDescription' => $this->getYoastMeta($post, 'metadesc', true),
			'metaKeywords' => $this->getYoastMeta($post, 'metakeywords', true),
			'metaRobotsNoIndex' => $this->getYoastMeta($post, 'meta-robots-noindex', true),
			'metaRobotsNoFollow' => $this->getYoastMeta($post, 'meta-robots-nofollow', true),
			'metaRobotsAdv' => $this->getYoastMeta($post, 'meta-robots-adv', true),
			'canonical' => $this->getYoastMeta($post, 'canonical', true),
			'redirect' => $this->getYoastMeta($post, 'redirect', true),
			'ogTitle' => $this->getYoastMeta($post, 'opengraph-title', true),
			'ogDescription' => $this->getYoastMeta($post, 'opengraph-description', true),
			'ogImage' => $this->getYoastMeta($post, 'opengraph-image', true),
			'twitterTitle' => $this->getYoastMeta($post, 'twitter-title', true),
			'twitterDescription' => $this->getYoastMeta($post, 'twitter-description', true),
			'twitterImage' => $this->getYoastMeta($post, 'twitter-image', true)
		);

		$data->data['yoast'] = $yoastMeta;

		return $data;
	}

	private function getYoastMeta($post, $key) {
		$value = get_post_meta($post->ID, '_yoast_wpseo_' . $key, true);

		if (strpos($key, 'image') && empty($value)) {
			$value = null;
		}

		return $value;
	}
}
