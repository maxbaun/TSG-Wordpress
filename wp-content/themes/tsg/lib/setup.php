<?php

namespace TSG;

class Setup
{
	private $manifest = array();

	public function __construct() {
		add_action('after_setup_theme', array($this, 'addThemeSupport'));
		add_action('wp_enqueue_scripts', array($this, 'addScripts'));

		$this->manifest = $this->getAppManifest();
	}

	public function addThemeSupport() {
		add_theme_support('post-thumbnails');
		add_theme_support('menus');
	}

	private function getAppManifest() {
		$manifest = file_get_contents(get_template_directory() . '/dist/assets.json');
		$manifest = (array) json_decode($manifest);

		return $manifest;
	}

	private function getAppStylesheet() {
		// return get_template_directory_uri() . '/dist/css/app.css';
		// return get_template_directory_uri() . '/dist/' . $this->manifest['app.css'];
		if (empty($this->manifest['app.css'])) {
			return false;
		}

		return $this->manifest['app.css'];
	}

	private function getAppScript() {
		// return get_template_directory_uri() . '/dist/js/app.js';

		// return get_template_directory_uri() . '/dist/' . $this->manifest['app.js'];
		return $this->manifest['app.js'];
	}

	public function addScripts() {
		wp_enqueue_script('tsg', $this->getAppScript(), array(), null, true);

		$constants = array(
			'options' => $this->getThemeOptions()
		);

		wp_localize_script('tsg', 'TsgConstants', $constants);

		if ($this->getAppStylesheet()) {
			wp_enqueue_style('tsg', $this->getAppStylesheet(), false, null);
		}

		if (is_page_template('template-availability.php')) {
			wp_enqueue_script('djep', 'https://tsgtools.com/check_req_info_form.js', array(), null, true);
		}
	}

	private function getThemeOptions() {
		return get_fields('options');
	}
}
