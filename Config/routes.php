<?php

Router::connect('/admin', array('admin' => true, 'plugin' => 'awecms', 'controller' => 'static_pages', 'action' => 'plugin_display', 'awecms', 'home'));
Router::connect('/admin/static/*', array('admin' => true, 'plugin' => 'awecms', 'controller' => 'static_pages', 'action' => 'display'));
Router::connect('/static/*', array('admin' => false, 'plugin' => 'awecms', 'controller' => 'static_pages', 'action' => 'display'));
Router::connect('/:plugin/static/*', array('admin' => false, 'plugin' => 'awecms', 'controller' => 'static_pages', 'action' => 'plugin_display'));
Router::connect('/admin/:plugin/static/*', array('admin' => true, 'plugin' => 'awecms', 'controller' => 'static_pages', 'action' => 'plugin_display'), array('pass' => array('plugin')));

Router::connect('/login', array('admin' => false, 'plugin' => 'awecms', 'controller' => 'users', 'action' => 'login'));
Router::connect('/admin/login', array('admin' => true, 'plugin' => 'awecms', 'controller' => 'users', 'action' => 'login'));
Router::connect('/logout', array('admin' => false, 'plugin' => 'awecms', 'controller' => 'users', 'action' => 'logout'));
Router::connect('/admin/logout', array('admin' => true, 'plugin' => 'awecms', 'controller' => 'users', 'action' => 'logout'));

// Connect aliased routes
/*$adminMenu = Configure::read('Admin.menu');
if (is_array($adminMenu)) {
	foreach ($adminMenu as $controller => $alias) {
		// index
		$route = '/admin/' . strtolower($alias);
		$defaults = array('plugin' => 'admin', 'controller' => strtolower($controller));
		Router::connect($route, $defaults);
		
		// add
		$route = '/admin/' . strtolower($alias) . '/:action';
		$defaults = array('plugin' => 'admin', 'controller' => strtolower($controller));
		Router::connect($route, $defaults);
	}
}*/

// App::uses('SlugRoute', 'Awecms.Lib');
// SlugRoute::connectRoutes();