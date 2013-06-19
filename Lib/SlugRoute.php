<?php

App::uses('CakeRoute', 'Routing/Route');
App::uses('Slug', 'Awecms.Model');

class SlugRoute extends CakeRoute {

	protected static $_slugRoutes = array();
	
	protected $_slug = null;
	
	public function __construct($template, $defaults = array(), $options = array()) {
		self::$_slugRoutes[] = $this;
		$this->_slug = $options['slug'];
		unset($options['slug']);
		parent::__construct($template, $defaults, $options);
	}
	
	public static function matchSlug($url = null) {
		if (!$url) {
			$url = Router::getRequest()->url;
		}
		$route = Router::requestRoute();
		if (isset($route->_slug)) {
			return $route->_slug;
		}
		/*for ($i = 0, $len = count(self::$_slugRoutes); $i < $len; $i++) {
			$route =& self::$_slugRoutes[$i];

			if ($route->parse($url) !== false) {
				return $route->_slug;
			}
		}*/
		
		return null;
	}
	
	public static function connectRoutes() {
		$Model = new Slug();
		$slugs = $Model->find('active');
		
		foreach ($slugs as $slug) {
			$options = $slug['Slug']['options'];
			if (!isset($options['routeClass'])) {
				$options['routeClass'] = 'SlugRoute';
			}
			$options['slug'] = $slug;
			Router::connect($slug['Slug']['route'], $slug['Slug']['defaults'], $options);
		}
	}
}