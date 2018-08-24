<?php

namespace TSG\Api\Routes;

class Menu
{
	public function getMenu($req) {
		$menu = $this->transformMenu($req->get_param('id'));
		wp_send_json_success($menu);
	}

	public function getFooterMenus() {
		$menu1 = array(
			'title' => 'Resources',
			'items' => $this->transformMenu(3)
		);
		$menu2 = array(
			'title' => 'Services',
			'items' => $this->transformMenu(4)
		);

		wp_send_json_success(array($menu1, $menu2));
	}

	private function transformMenu($menuId) {
		$items = wp_get_nav_menu_items($menuId);

		$revItems = array_reverse($items);
		$revMenu  = array();
		$cache     = array();

		foreach ($revItems as $item) {
			$formatted = array(
				'ID'          => abs($item->ID),
				'order'       => (int) $item->menu_order,
				'parent'      => abs($item->menu_item_parent),
				'title'       => $item->title,
				'url'         => $item->url,
				'attr'        => $item->attr_title,
				'target'      => $item->target,
				'classes'     => implode(' ', $item->classes),
				'xfn'         => $item->xfn,
				'description' => $item->description,
				'object_id'   => abs($item->object_id),
				'object'      => $item->object,
				'type'        => $item->type,
				'type_label'  => $item->type_label,
				'children'    => array(),
			);

			if (array_key_exists($item->ID , $cache)) {
				$formatted['children'] = array_reverse($cache[ $item->ID ]);
			}

			$formatted = apply_filters('rest_menus_format_menu_item', $formatted);

			if ($item->menu_item_parent != 0) {

				if (array_key_exists($item->menu_item_parent , $cache)) {
					array_push($cache[$item->menu_item_parent ], $formatted);
				} else {
					$cache[$item->menu_item_parent ] = array( $formatted, );
				}

			} else {
				array_push($revMenu, $formatted);
			}

		}

		$data = array_reverse($revMenu);

		return $data;
	}
}
