<?php

Router::connect('/admin', array('admin' => true, 'plugin' => 'piece_o_cake', 'controller' => 'pages', 'action' => 'display', 'home'));
Router::connect('/admin/display/*', array('admin' => true, 'plugin' => 'piece_o_cake', 'controller' => 'pages', 'action' => 'display'));
Router::connect('/display/*', array('admin' => false, 'plugin' => 'piece_o_cake', 'controller' => 'pages', 'action' => 'display'));

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

// App::uses('SlugRoute', 'PieceOCake.Lib');
// SlugRoute::connectRoutes();