<?php

App::uses('CakeEventManager', 'Event');
App::uses('CakeEvent', 'Event');

/**
 * Class MenuHelper
 *
 * @property HtmlHelper $Html
 */
class MenuHelper extends AppHelper {

/**
 * Helpers
 *
 * @var array
 */
	public $helpers = ['Html'];

/**
 * Menu items
 *
 * @var array
 */
	protected $_menuItems = [];

/**
 * The menu items which are active in the current request
 *
 * @var array
 */
	protected $_matchUrl = [];

/**
 * Prevents recursion in sub menus
 *
 * @var array
 */
	protected $_rendering = [];

/**
 * Helper settings
 *
 * @var array
 */
	public $settings = [];

/**
 * Render a menu
 *
 * ### Options
 *
 * - `element` The element which will render the menu
 *
 * @param string $group Then menu group to render
 * @param string|array $options Menu rendering options or if a string sets the element option
 * @return null|string
 */
	public function render($group = 'default', $options = []) {
		if (!empty($this->_rendering[$group])) {
			return null;
		}
		$this->_rendering[$group] = true;
		
		if (is_string($options)) {
			$options = ['element' => $options];
		}
		
		$tmpVars = $options;
		$tmpVars['group'] = $group;
		
		$event = new CakeEvent('Menu.beforeRender', $this, $tmpVars);
		CakeEventManager::instance()->dispatch($event);
		if ($event->isStopped() || !$this->hasItems($group)) {
			return null;
		}
		$tmpVars = $event->data;
		
		$element = 'menu/default';
		if (!empty($options['element'])) {
			$element = $options['element'];
		}
		unset($options['element']);
		
		$tmpVars['items'] = Hash::sort($this->_menuItems[$group], '{s}.{s}.priority', 'asc', 'numeric');
		$html = $this->_View->element($element, $tmpVars);
		$this->_rendering[$group] = false;
		return $html;
	}

/**
 * Add an item to a menu to be rendered later.
 *
 * Has the same options as `HtmlHelper::link()`
 *
 * ### Additional Options
 *
 * - `group` The menu group to place the menu item in.
 * - `priority` The place in the menu to add the item. Defaults to 99 + (0.01 * count(menu items))
 *
 * @param string $title The content to be wrapped by <a> tags.
 * @param string|array $url Cake-relative URL or array of URL parameters, or external URL (starts with http://)
 * @param array $options Array of options and HTML attributes.
 * @return void
 */
	public function item($title, $url = null, $options = []) {
		if (is_string($options)) {
			$options = ['group' => $options];
		}
		
		$group = 'default';
		if (isset($options['group'])) {
			$group = $options['group'];
		}
		$priority = 99;
		if (isset($options['priority'])) {
			$priority = $options['priority'];
		}
		if (!$this->hasItems($group)) {
			$this->_menuItems[$group] = [];
		}
		$priority += count($this->_menuItems[$group]) * 0.01;
		unset($options['group'], $options['priority']);

		if ($url === false) {
			$key = 'item-' . count($this->_menuItems[$group]);
		} else {
			$key = Router::normalize($url);
		}
		$this->_menuItems[$group][$key] = compact('title', 'url', 'options', 'priority');
	}

/**
 * Remove an item from a menu
 *
 * @param string|array $url A URL to remove from the menu
 * @param string $group The menu group to remove the the URL from
 */
	public function removeItem($url, $group = 'default') {
		$key = Router::normalize($url);
		unset($this->_menuItems[$group][$key]);
	}

/**
 * Renders a menu link
 *
 * Has the same options as `HtmlHelper::link()`
 *
 * ### Additional Options
 *
 * - `wrap` An element to wrap the menu link in. Usually a <li> but defaults to none.
 *    If `wrap` is set, class goes on wrapper instead of <a>.
 *
 * @param string $title The content to be wrapped by <a> tags.
 * @param string|array $url Cake-relative URL or array of URL parameters, or external URL (starts with http://)
 * @param array $options Array of options and HTML attributes.
 * @return mixed A menu link
 */
	public function link($title, $url = null, $options = []) {
		if ($this->isActive($url, $options)) {
			$options['class'] = !empty($options['class']) ? $options['class'] . ' active' : 'active';
		}
		unset($options['group']);
		
		if (isset($options['wrap'])) {
			$wrap = $options['wrap'];
			unset($options['wrap']);
			if ($url === false) {
				return $this->Html->tag($wrap, $title, $options);
			}

			$class = isset($options['class']) ? $options['class'] : null;
			unset($options['class']);
			return $this->Html->tag($wrap, $this->Html->link($title, $url, $options), ['class' => $class]);
		} else {
			if ($url === false) {
				return $this->Html->tag('span', $title, $options);
			}
			return $this->Html->link($title, $url, $options);
		}
	}

/**
 * Checks if a URL is currently active
 *
 * ### Options
 *
 * - `group` The menu group to check this URL
 *
 * @param string|array $url The URL to check
 * @param array $options Array of options
 * @return bool True if menu item is active
 */
	public function isActive($url, $options = []) {
		if ($url === false) {
			return false;
		}
		if (is_string($options)) {
			$options = ['group' => $options];
		}
		$group = 'default';
		if (isset($options['group'])) {
			$group = $options['group'];
		}

		if (!isset($this->_matchUrl[$group])) {
			$this->match(Router::url(), $group);
		}
		return in_array(Router::normalize($url), $this->_matchUrl[$group]);
	}

/**
 * Make the current request match a URL in the menu.
 *
 * Use this method to make a menu item active.
 *
 * @param string|array $url The menu item to make active
 * @param string $group The menu group to make the item active in
 */
	public function match($url, $group = 'default') {
		$this->_matchUrl[$group][] = Router::normalize($url);
	}

/**
 * Check if a menu has menu items
 *
 * @param string $group Them menu group to check
 * @return bool True if the menu has items
 */
	public function hasItems($group = 'default') {
		return !empty($this->_menuItems[$group]);
	}

}
