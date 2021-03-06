<?php

// Load Piece O' Cake
App::uses('CakeEventManager', 'Event');
App::uses('Awecms', 'Awecms.Lib');
$POC = Awecms::instance();
CakeEventManager::instance()->attach($POC);


// Check settings to make sure the enviroment is correctly configured
// TODO: Make it so that these checks are cached where possible. If an error or warning occurs then clear the cache.
$POC->configCheck('cache_working'); // Can't be cached. Check me first.
$databaseConnected = $POC->configCheck('database_connected'); // Cache Me
$POC->configCheck('php_version'); // Cache Me
$POC->configCheck('tmp_writable');  // Cache Me
$POC->configCheck('pcre'); // Cache Me

// Load Piece O Cake configuration
$POC->loadConfig();

// Check to make sure all the required plugins are available
$plugins = App::objects('plugin');
$datasourcesExists = in_array('Datasources', $plugins);
$utilsExists = in_array('Utils', $plugins);

if ($datasourcesExists) {
	CakePlugin::load('Datasources'); // CakePHP/Datasources plugin (2.0 branch)
	App::uses('ConnectionManager', 'Model');
	ConnectionManager::create('config',  array('datasource' => 'Awecms.ReaderSource'));
}

CakePlugin::load('Utils'); // CakeDC/Utils plugin
CakePlugin::load('TwitterBootstrap');

App::build(array('View' => App::pluginPath('Awecms') . 'View' . DS));