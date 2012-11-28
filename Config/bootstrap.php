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


// Check to make sure all the required plugins are available
$plugins = App::objects('plugin');
$utilsExists = in_array('Utils', $plugins);

if ($databaseConnected) {
	if ($utilsExists) {
		CakePlugin::load('Utils');
		
		App::uses('DboReader', 'PieceOCake.Configure');
		Configure::config('dbo', new DboReader());
		Configure::load('Config', 'dbo');
	}
}

CakePlugin::load('Search');
CakePlugin::load('Users');

// CakePlugin::load('Uploader');