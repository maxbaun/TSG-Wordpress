<?php

namespace TSG;

class API
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
}
