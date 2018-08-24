<?php

namespace TSG\Api\Utils;

use TSG\Api\Utils\Transform;

class FlexibleContent
{
	private $data = array();
	private $content = array();
	private $transform;

	public function __construct($postId) {
		$this->content = get_field('flexibleContent', $postId);

		$this->data = $this->getData();
	}

	public function getContent() {
		return $this->data;
	}

	private function getData() {
		$data = array();

		foreach ($this->content as $component) {
			$data[] = $this->transformComponent($component);
		}

		return $data;
	}

	private function transformComponent($component) {
		$data = array(
			'component' => $component['acf_fc_layout']
		);

		$componentData = $this->getComponentData($component);

		return array_merge($data, $componentData);
	}

	private function getComponentData($component) {
		$type = $component['acf_fc_layout'];
		if (method_exists($this, $type)) {
			return $this->$type($component);
		} else {
			return array();
		}
	}

	private function hero($component) {
		return array(
			'title' => $component['title'],
			'subtitle' => $component['subtitle'],
			'link' => !empty($component['link']) ? $component['link'] : null,
			'video' => Transform::transformVideo($component['video']),
			'images' => array_map(function ($image) {
				return Transform::transformImage($image);
			}, $component['images'])
		);
	}

	private function pageDescriptionContent($component) {
		return array(
			'disableAngleBottom' => $component['pageDescriptionContentDisableAngleBottom'],
			'content' => Transform::transformContent($component['content']),
			'video' => Transform::transformVideo($component['pageDescriptionContentVideo'])
		);
	}

	private function pageDescriptionImages($component) {
		$images = array();

		foreach ($component['images'] as $image) {
			$images[] = array(
				'image' => Transform::transformImage($image['image']),
				'link' => $image['link']
			);
		}

		return array(
			'disableAngleBottom' => $component['pageDescriptionImagesDisableAngleBottom'],
			'images' => $images
		);
	}

	private function sectionHalf($component) {
		return array(
			'verticalCenter' => !empty($component['verticalCenter']) ? $component['verticalCenter'] : false,
			'left' => Transform::transformSectionContent($component['left']),
			'right' => Transform::transformSectionContent($component['right'])
		);
	}

	private function sectionReviews($component) {
		return array(
			'header' => $component['sectionReviewsHeader'],
			'content' => $component['sectionReviewsContent'],
			'reviews' => array_map(function ($review) {
				return Transform::transformReview($review);
			}, $component['sectionReviewsItems']),
			'link' => $component['sectionReviewsLink']
		);
	}

	private function sectionBios($component) {
		return array(
			'header' => $component['sectionBiosHeader'],
			'content' => $component['sectionBiosContent'],
			'bios' => array_map(function ($bio) {
				return Transform::transformBio($bio);
			}, $component['sectionBiosBios'])
		);
	}

	private function sectionAwards($component) {
		return array(
			'title' => $component['sectionAwardsTitle'],
			'images' => array_map(function ($award) {
				return Transform::transformAward($award);
			}, $component['sectionAwardsImages'])
		);
	}

	private function sectionSlant($component) {
		return array(
			'slantDirection' => $component['sectionSlantSlantDirection'],
			'disableSlantTop' => $component['sectionSlantDisableSlantTop'],
			'disableSlantBottom' => $component['sectionSlantDisableSlantBottom'],
			'image' => Transform::transformImage($component['sectionSlantImage']),
			'content' => Transform::transformContent($component['sectionSlantContent'])
		);
	}

	private function sectionFacts($component) {
		return array(
			'title' => $component['sectionFactsTitle'],
			'facts' => $component['sectionFactsFacts'],
			'link' => $component['sectionFactsLink']
		);
	}

	private function sectionFeatured($component) {
		return array(
			'image' => Transform::transformImage($component['sectionFeaturedImage']),
			'content' => Transform::transformContent($component['sectionFeaturedContent'])
		);
	}

	private function sectionServices($component) {
		return array(
			'content' => Transform::transformContent($component['sectionServicesContent']),
			'services' => array_map(function ($service) {
				return Transform::transformService($service);
			}, $component['sectionServicesServices'])
		);
	}

	private function sectionComponents($component) {
		return array(
			'header' => $component['sectionComponentsHeader'],
			'components' => $component['sectionComponentsComponents']
		);
	}

	private function fullWidthImage($component) {
		return array(
			'image' => Transform::transformImage($component['fullWidthImageImage']),
			'disableSlantTop' => $component['fullWidthImageDisableSlantTop'],
			'disableSlantBottom' => $component['fullWidthImageDisableSlantBottom'],
			'slantDirection' => $component['fullWidthImageSlantDirection']
		);
	}

	private function fullWidthContent($component) {
		return array(
			'maxWidth' => $component['fullWidthContentMaxWidth'],
			'content' => Transform::transformContent($component['fullWidthContentContent'])
		);
	}

	private function sectionPlanning($component) {
		return array(
			'header' => !empty($component['sectionPlanningHeader']) && isset($component['sectionPlanningHeader'][0]) ? $component['sectionPlanningHeader'][0] : null,
			'tools' => array_map(function ($tool) {
				return Transform::transformTool($tool);
			}, $component['sectionPlanningTools']),
			'help' => $component['sectionPlanningHelp']
		);
	}

	private function sectionForm($component) {
		return array(
			'content' => Transform::transformContent($component['sectionFormContent']),
			'form' => $component['sectionFormForm']
		);
	}
}
