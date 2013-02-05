<?php

App::uses('CakeEventManager', 'Event');
App::uses('CakeEvent', 'Event');

class MenuHelper extends AppHelper {
	
	public $helpers = array('Html');
	
	protected $_menuItems = array();
	protected $_matchUrl = array();
	
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
	
	public function render($menu, $element = 'PieceOCake.menu') {
		CakeEventManager::instance()->dispatch(new CakeEvent($menu . '.beforeRender', $this));
		return $this->_View->element($element, array('menuItems' => $this->_menuItems));
	}
	
	public function addItem($text, $url = null, $options = array()) {
		$this->_menuItems[] = compact('text', 'url', 'options');
	}
	
	public function link($text, $url = null, $options = array(), $confirmMessage = false) {
		if ($this->isActive($url, $options)) {
			$options['class'] = !empty($options['class']) ? $options['class'] . ' active' : 'active';
		}
		unset($options['group']);
		return $this->Html->link($text, $url, $options, $confirmMessage);
	}
	
	public function isActive($checkUrl, $options = array()) {
		if (is_string($options)) {
			$group = $options;
		} else if (isset($options['group'])) {
			$group = $options['group'];
		} else {
			$group = 'default';
		}
		
		if (!isset($this->_matchUrl[$group])) {
			$this->match(Router::url(), $group);
		}
		
		return in_array(Router::normalize($checkUrl), $this->_matchUrl[$group]);
	}
	
	public function match($url, $group = null) {
		if ($group === null) {
			$group = 'default';
		}
		$this->_matchUrl[$group][] = Router::normalize($url);
	}
}