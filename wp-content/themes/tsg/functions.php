<?

namespace TSG;

require_once(__DIR__ . '/lib/setup.php');
require_once(__DIR__ . '/lib/options.php');
require_once(__DIR__ . '/lib/mail.php');
require_once(__DIR__ . '/lib/gatsby.php');
require_once(__DIR__ . '/lib/api/index.php');
require_once(__DIR__ . '/lib/deploy/index.php');

new Setup();
new Options();
new NetlifyDeploy();
new Api();
new Mail();
new Gatsby();

// add_filter('acf/format_value/type=image', function($value, $post_id, $field) {
//     if (empty($value)) {
//         return null;
//     }

//     return $value;
// }, 100, 3);

// add_filter('acf/format_value/type=relationship', function($value, $post_id, $field) {
//     if (empty($value)) {
//         return null;
//     }

// 	$newValues = array();
// 	foreach ($value as $v) {
// 		$v->featured_media = wp_get_attachment_image_url(get_post_thumbnail_id($v->ID), 'full');
// 		$v->url = get_permalink($v);
// 		$newValues[] = $v;
// 	}

//     return $newValues;
// }, 100, 3);
// // add_filter('acf/format_value/type=relationship', 'nullify_empty', 100, 3);
// // // not sure if gallery is internally named gallery as well but this should work
// // add_filter('acf/format_value/type=gallery', 'nullify_empty', 100, 3);
// add_filter('acf/format_value/type=repeater', function($value, $post_id, $field) {
//     if (empty($value)) {
//         return null;
//     }

//     return $value;
// }, 100, 3);

// add_filter('acf/format_value/type=group', function($value, $post_id, $field) {
//     if (empty($value)) {
//         return null;
//     }

//     return $value;
// }, 100, 3);

// add_filter('acf/format_value/type=component_field', function($value, $post_id, $field) {
//     if (empty($value)) {
//         return null;
// 	}

//     return $value;
// }, 100, 3);

// add_filter('acf/format_value/type=flexible_content', function($value, $post_id, $field) {
//     if (empty($value)) {
//         return array();
// 	}

//     return $value;
// }, 100, 3);

// add_filter('acf/format_value/type=image', function($value, $post_id, $field) {
//     if (empty($value)) {
//         return null;
// 	}

//     return $value;
// }, 100, 3);

// add_filter('acf/format_value/type=link', function($value, $post_id, $field) {
//     if (empty($value)) {
//         return null;
// 	}

//     return $value;
// }, 100, 3);

// add_action('rest_api_init', function() {
// 	register_rest_field(
// 		array('dj'), // add to these post types
// 		'menu_order', // name of field
// 		array(
// 			'get_callback' => function ($post) {
// 				return intval(get_post_field('menu_order', $post['id'])); // value of field
// 			}
// 		)
// 	);
// });
