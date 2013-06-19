<?php

App::uses('CakeEventManager', 'Event');
App::uses('CakeEvent', 'Event');

class MenuHelper extends AppHelper {
	
	public $helpers = array('Html');
	
	protected $_menuItems = array();
	protected $_matchUrl = array();
	protected $_rendering = array();
	
	public $settings = array();
	
	public function __construct(View $View, $settings = array()) {
		parent::__construct($View, $settings);
		$this->settings = Hash::normalize($settings);
		
		if (isset($this->settings['match'])) {
			foreach ($this->settings['match'] as $group => $url) {
				$this->match($url, $group);
			}
		}
	}
	
	public function render($group = 'default', $options = array()) {
		if (!empty($this->_rendering[$group])) {
			return null;
		}
		$this->_rendering[$group] = true;
		
		if (is_string($options)) {
			$options = array('element' => $options);
		}
		
		$tmpVars = $options;
		$tmpVars['group'] = $group;
		
		$event = new CakeEvent('Menu.beforeRender', $this, $tmpVars);
		CakeEventManager::instance()->dispatch($event);
		if ($event->isStopped() || empty($this->_menuItems[$group])) {
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
	
	public function addItem($text, $url = null, $options = array()) {
		if (is_string($options)) {
			$options = array('group' => $options);
		}
		
		$group = 'default';
		if (isset($options['group'])) {
			$group = $options['group'];
		}
		$priority = 99;
		if (isset($options['priority'])) {
			$priority = $options['priority'];
		}
		if (!isset($this->_menuItems[$group])) {
			$this->_menuItems[$group] = array();
		}
		$priority += count($this->_menuItems[$group]) * 0.01;
		unset($options['group'], $options['priority']);
		
		$key = Router::normalize($url);
		$this->_menuItems[$group][$key] = compact('text', 'url', 'options', 'priority');
	}
	
	public function removeItem($url, $group = 'default') {
		$key = Router::normalize($url);
		unset($this->_menuItems[$group][$key]);
	}
	
	public function link($text, $url = null, $options = array(), $confirmMessage = false) {
		if ($this->isActive($url, $options)) {
			$options['class'] = !empty($options['class']) ? $options['class'] . ' active' : 'active';
		}
		unset($options['group']);
		
		if (isset($options['wrap'])) {
			$wrap = $options['wrap'];
			$class = isset($options['class']) ? $options['class'] : null;
			unset($options['wrap'], $options['class']);
			$link = $this->Html->link($text, $url, $options, $confirmMessage);
			return $this->Html->tag($wrap, $link, $class);
		} else {
			return $this->Html->link($text, $url, $options, $confirmMessage);
		}
	}
	
	public function isActive($checkUrl, $options = array()) {
		if (is_string($options)) {
			$options = array('group' => $options);
		}
		$group = 'default';
		if (isset($options['group'])) {
			$group = $options['group'];
		}
		
		if (!isset($this->_matchUrl[$group])) {
			$this->match(Router::url(), $group);
		}
		
		if (in_array(Router::normalize($checkUrl), $this->_matchUrl[$group])) {
			return true;
		}
		
		if (!empty($options['submenu']) && !empty($this->_menuItems[$options['submenu']])) {
			foreach ($this->_menuItems[$options['submenu']] as $item) {
				if ($this->isActive($item['url'], $item['options'])) {
					return true;
				}
			}
		}
		return false;
	}
	
	public function match($url, $group = null) {
		if ($group === null) {
			$group = 'default';
		}
		$this->_matchUrl[$group][] = Router::normalize($url);
	}
	
	public function hasItems($group = 'default') {
		return !empty($this->_menuItems[$group]);
	}
}