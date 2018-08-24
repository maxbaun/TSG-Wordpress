<?php

namespace TSG;

class Api
{
	public function __construct() {
		require_once(__DIR__ . '/utils/flexibleContent.php');
		require_once(__DIR__ . '/utils/transform.php');

		require_once(__DIR__ . '/routes/venues.php');
		require_once(__DIR__ . '/routes/menu.php');
		require_once(__DIR__ . '/routes/page.php');
		require_once(__DIR__ . '/routes/options.php');
		require_once(__DIR__ . '/routes/vendors.php');
		require_once(__DIR__ . '/routes/reviews.php');
		require_once(__DIR__ . '/routes/djs.php');
		require_once(__DIR__ . '/routes/posts.php');

		$this->venues = new Api\Routes\Venues();
		$this->menu = new Api\Routes\Menu();
		$this->page = new Api\Routes\Page();
		$this->options = new Api\Routes\Options();
		$this->vendors = new Api\Routes\Vendors();
		$this->reviews = new Api\Routes\Reviews();
		$this->djs = new Api\Routes\DJs();
		$this->posts = new Api\Routes\Posts();

		add_action('rest_api_init', array($this, 'initRoutes'));
		add_action('rest_api_init', array($this, 'cors'));
	}

	public function initRoutes() {
		// Menu Routes
		register_rest_route('tsg/v1', '/menu/footer', array(
			'methods' => 'GET',
			'callback' => array($this->menu, 'getFooterMenus')
		));

		register_rest_route('tsg/v1', '/menu/(?P<id>\\d+)', array(
			'methods' => 'GET',
			'callback' => array($this->menu, 'getMenu')
		));

		// Page Routes
		register_rest_route('tsg/v1', '/page', array(
			'methods' => 'GET',
			'callback' => array($this->page, 'getPage')
		));

		// Venue Routes
		register_rest_route('tsg/v1', '/venues/', array(
			'methods' => 'GET',
			'callback' => array($this->venues, 'getVenues')
		));
		register_rest_route('tsg/v1', '/venue/', array(
			'methods' => 'GET',
			'callback' => array($this->venues, 'getVenue')
		));

		// Options Routes
		register_rest_route('tsg/v1', '/options/', array(
			'methods' => 'GET',
			'callback' => array($this->options, 'getOptions')
		));

		// Vendor Routes
		register_rest_route('tsg/v1', '/vendors/', array(
			'methods' => 'GET',
			'callback' => array($this->vendors, 'getVendors')
		));

		// Review Routes
		register_rest_route('tsg/v1', '/reviews/', array(
			'methods' => 'GET',
			'callback' => array($this->reviews, 'getReview')
		));

		// DJ Routes
		register_rest_route('tsg/v1', '/djs/', array(
			'methods' => 'GET',
			'callback' => array($this->djs, 'getDjs')
		));

		// Post Routes
		register_rest_route('tsg/v1', '/posts/', array(
			'methods' => 'GET',
			'callback' => array($this->posts, 'getPosts')
		));
	}

	public function cors() {
		header('Access-Control-Allow-Origin: *');
		header('Access-Control-Allow-Methods: GET, OPTIONS');
		header('Access-Control-Allow-Credentials: true');
		header('Access-Control-Expose-Headers: Link', false);
	}
}
