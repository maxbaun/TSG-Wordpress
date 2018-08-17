<?php

namespace TSG;

class Mail
{
	public function __construct() {
		add_action('rest_api_init', function () {
			register_rest_route('tsg/v1', '/mail/', array(
				'methods' => 'POST',
				'callback' => array($this, 'handleMessagePost'),
			));
		});
	}

	public function handleMessagePost($request) {
		$to = $request->get_param('to');
		$fromEmail = $request->get_param('fromEmail');
		$fromName = $request->get_param('fromName');
		$subject = $request->get_param('subject');

		if (empty($to)) {
			$this->sendError('form', 'To is required.');
		}

		if (empty($fromEmail)) {
			$this->sendError('form', 'fromEmail is required.');
		}

		if (empty($fromName)) {
			$this->sendError('form', 'fromName is required.');
		}

		if (empty($subject)) {
			$this->sendError('form', 'Subject is required.');
		}

		$fields = $this->getFieldString($request);

		$replyTo = 'Reply-To:' . $fromName . ' <' . $fromEmail . '>';
		$from = 'From:' . $fromName . ' <' . $fromEmail . '>';
		$contentType = 'Content-Type: text/html; charset=UTF-8';
		$headers = array($replyTo, $from, $contentType);

		$footer = $this->getFooter();

		$message = $fields . '<br/>' . $footer;

		$res = wp_mail($to, $subject, $message, $headers);

		if ($res) {
			wp_send_json_success();
		} else {
			$this->sendError('form', 'There was an error sending the form.');
		}
	}

	private function getFooter() {
		return '
			==================================<br/>
			This message was sent via <a href="https://tsgweddings.com"><strong>https://tsgweddings.com</strong></a>
		';
	}

	private function getFieldString($request) {
		$fields = $request->get_param('fields');

		if (empty($fields)) {
			return [];
		}

		$fieldStr = '';

		foreach ($fields as $field) {
			$fieldStr .= ucwords($field['key']) . ': ' . $field['value'] . '<br/>';
		}

		return $fieldStr;
	}

	private function sendError($field, $message) {
		return wp_send_json_error(array(
			'field' => $field,
			'message' => $message
		));
	}
}
