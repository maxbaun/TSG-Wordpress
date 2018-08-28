<?php

namespace TSG;

class Yoast
{
	public function addRestPrepare() {
		add_filter('rest_prepare_post', array($this, 'prepareYoast'), 10, 3);
		add_filter('rest_prepare_page', array($this, 'prepareYoast'), 10, 3);
		add_filter('rest_prepare_dj', array($this, 'prepareYoast'), 10, 3);
		add_filter('rest_prepare_venue', array($this, 'prepareYoast'), 10, 3);
		add_filter('rest_prepare_vendor', array($this, 'prepareYoast'), 10, 3);
	}

	public function prepareYoast($data, $post, $context) {
		$data->data['yoast'] = $this->getYoastData($post);

		return $data;
	}

	public function getYoastData($post) {
		return array(
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
	}

	private function getYoastMeta($post, $key) {
		$value = get_post_meta($post->ID, '_yoast_wpseo_' . $key, true);

		if (strpos($key, 'image') && empty($value)) {
			$value = null;
		}

		return $value;
	}
}
