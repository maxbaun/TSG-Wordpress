<?php

namespace TSG\Api\Routes;

use TSG\Api\Utils\Transform;

class Vendors
{
	public function getVendors($req) {
		$vendors = get_posts(array(
			'post_type' => 'vendor',
			'orderby' => 'menu_order',
			'order' => 'ASC',
			'posts_per_page' => -1
		));

		$categories = get_terms('vendorcategory', array(
			'hide_empty' => true
		));

		$vendorData = array_map(array($this, 'transformVendor'), $vendors);
		$categoryData = array_map(array($this, 'transformVendorCategory'), $categories);

		$data = array(
			'data' => $vendorData,
			'categories' => $categoryData
		);

		wp_send_json_success($data);
	}

	public function transformVendor($vendor) {
		$categories = wp_get_post_terms($vendor->ID, 'vendorcategory');

		$vendorCategoryes = array_map(array($this, 'transformVendorCategory'), $categories);

		$venuePage = get_field('vendorVenuePage', $vendor->ID);

		return array(
			'id' => $vendor->ID,
			'title' => $vendor->post_title,
			'slug' => $vendor->post_name,
			'vendorCategory' => $vendorCategoryes,
			'phone' => get_field('vendorPhone', $vendor->ID),
			'email' => get_field('vendorEmail', $vendor->ID),
			'link' => get_field('vendorLink', $vendor->ID),
			'venuePage' => !empty($venuePage) ? $venuePage['url'] : null
		);
	}

	public function transformVendorCategory($category) {
		return array(
			'name' => $category->name,
			'id' => $category->term_id,
			'slug' => $category->slug
		);
	}
}
