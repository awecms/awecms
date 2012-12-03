<?php

// Load Piece O' Cake
App::uses('CakeEventManager', 'Event');
App::uses('PieceOCake', 'PieceOCake.Lib');
$POC = PieceOCake::instance();
CakeEventManager::instance()->attach($POC);


// Check settings to make sure the enviroment is correctly configured
// TODO: Make it so that these checks are cached where possible. If an error or warning occurs then clear the cache.
$POC->configCheck('cache_working'); // Can't be cached. Check me first.
$databaseConnected = $POC->configCheck('database_connected'); // Cache Me
$POC->configCheck('php_version'); // Cache Me
$POC->configCheck('tmp_writable');  // Cache Me
$POC->configCheck('pcre'); // Cache Me

// Load Piece O Cake configuration
Configure::load('piece_o_cake');

// Check to make sure all the required plugins are available
$plugins = App::objects('plugin');
$datasourcesExists = in_array('Datasources', $plugins);
$utilsExists = in_array('Utils', $plugins);
$usersExists = in_array('Users', $plugins);
$searchExists = in_array('Search', $plugins);

if ($datasourcesExists) {
	CakePlugin::load('Datasources'); // CakePHP/Datasources plugin (2.0 branch)
	App::uses('ConnectionManager', 'Model');
	ConnectionManager::create('config',  array('datasource' => 'PieceOCake.ReaderSource'));
}

CakePlugin::load('Utils'); // CakeDC/Utils plugin
CakePlugin::load('Search'); // CakeDC/Search plugin
CakePlugin::load('Users', array('routes' => true)); // CakeDC/Users plugin

// CakePlugin::load('Uploader');