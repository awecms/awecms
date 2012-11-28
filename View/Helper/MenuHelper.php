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
		if ($url === null) {
			$slug = preg_replace(array('/\s&\s/', '/\s@\s/', '/\s+/', '/[^a-z0-9]/i'), array(' and ', ' at ', ' ', '-'), $text);
			$slug = strtolower($slug);
			//$slug = Inflector::slug($slug, '-');
			$url = array('controller' => 'pages', 'action' => 'display', $slug);
			
			// This changes the default behaviour of cake and may need to be removed.
			if (is_array($url) && empty($url['plugin'])) {
				$url['plugin'] = false;
			}
		}
		
		$url = Router::normalize($url);
		
		$group = 'top';
		if (isset($options['group'])) {
			$group = $options['group'];
		}
		unset($options['group']);
		
		if (!isset($this->_matchUrl[$group])) {
			$this->match(Router::url(), $group);
		}
		$matchUrl = $this->_matchUrl[$group];
		
		if (isset($options['query'])) {
			$matchUrl = Router::parse($matchUrl);
			$matchUrl['?'] = $this->request->query;
			$matchUrl = Router::normalize($matchUrl);
		}
		
		// With the new match() method this functionality becomes ambigiuos :(.
		// As much as I like the 'match' option it kinda fails when routes are used.
		$match = false;
		if (isset($options['match'])) {
			$match = preg_match($options['match'], $matchUrl);
			unset($options['match']);
		} else {
			//$match = '/' . preg_quote($url, '/') . '[\/\?\#]/';
		}
		
		if ($url == $matchUrl || $match) {
			$options['class'] = !empty($options['class']) ? $options['class'] . ' active' : 'active';
		}
		return $this->Html->link($text, $url, $options, $confirmMessage);
	}
	
	public function match($url, $group = null) {
		if ($group === null) {
			$group = 'top';
		}
		$this->_matchUrl[$group] = Router::normalize($url);
	}
}