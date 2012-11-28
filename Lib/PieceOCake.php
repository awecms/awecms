<?php

App::uses('CakeEventListener', 'Event');
App::uses('Inflector', 'Utility');

class PieceOCake implements CakeEventListener {
	
	protected static $_instance = null;
	
	protected $_configChecks = array();
	protected $_configCheckMessages = array();
	
	protected $_widgets = array();
	
	public static function instance() {
		if (empty(self::$_instance)) {
			self::$_instance = new PieceOCake();
		}
		return self::$_instance;
	}
	
	public function implementedEvents() {
		return array(
			'Controller.initialize' => 'controllerInitialize',
			'Admin.MainMenu.beforeRender' => 'addMenuItems',
		);
	}
	
	public function controllerInitialize($event) {
		$Controller = $event->subject();
		
		// Is a page with the admin prefix being requested. If so then setup POC admin.
		if (!empty($Controller->request->params['admin'])) {
			$Controller->helpers[] = 'PieceOCake.Menu';
			App::uses('AuthComponent', 'Controller/Component');
			$Controller->layout = 'PieceOCake.default';
			
			$settings = array(
				'all' => array(
					'scope' => array(
						'User.active' => 1,
						'User.is_admin' => 1,
						'User.role' => 'admin',
					),
				),
				'authenticate' => array('Form'),
				'loginAction' => array('admin' => false, 'plugin' => 'users', 'controller' => 'users', 'action' => 'login'),
			);
			$Controller->Components->load('Auth', $settings);
		}
		
		// Debug test shortcut for views and controllers
		$Controller->debug = Configure::read('debug') > 0;
		$Controller->set('debug', $Controller->debug);
	}
	
	public function addMenuItems($event) {
		$Menu = $event->subject();
		$Menu->addItem('Configuration', array('plugin' => 'piece_o_cake', 'controller' => 'configs', 'action' => 'index'));
		$Menu->addItem('Users', array('plugin' => 'piece_o_cake', 'controller' => 'users', 'action' => 'index'));
	}
	
	public function configCheck($key = null) {
		if ($key === null) {
			return in_array(false, $this->_configChecks);
		}
		
		if (!isset($this->_configChecks[$key])) {
			$method = '_' . Inflector::variable($key);
			$this->_configChecks[$key] = $this->{$method}();
		}
		
		return $this->_configChecks[$key];
	}
	
	public function getConfigError($key) {
		if ($this->configCheck($key)) {
			return null; // There is no config error
		}
		
		if (!isset($this->_configCheckMessages[$key])) {
			return 'Unknown reason.';
		}
		
		return $this->_configCheckMessages[$key];
	}
	
	protected function _databaseConfigPresent() {
		$this->_configCheckMessages['database_connected'] = 'Datebase configuration file missing.';
		return file_exists(APP . 'Config' . DS . 'database.php');
	}
	
	protected function _databaseConnected() {
		if (!$this->configCheck('database_config_present')) {
			$this->_configCheckMessages['database_connected'] = $this->getConfigError('database_config_present');
			return false;
		}
		
		App::uses('ConnectionManager', 'Model');
		try {
			return ConnectionManager::getDataSource('default');
		} catch (Exception $connectionError) {
			$this->_configCheckMessages['database_connected'] = $connectionError->getMessage();
			return false;
		}
	}
	
	protected function _phpVersion() {
		if (version_compare(PHP_VERSION, '5.2.8', '<')) {
			$this->_configCheckMessages['php_version'] = 'Your version of PHP is too low. You need PHP 5.2.8 or higher to use CakePHP.';
			return false;
		}
		
		return true;
	}
	
	protected function _tmpWritable() {
		if (!is_writable(TMP)) {
			$this->_configCheckMessages['tmp_writable'] = 'Your tmp directory is NOT writable.';
			return false;
		}
		
		return true;
	}
	
	protected function _cacheWorking() {
		$check = Cache::settings();
		if ($check) {
			$this->_configCheckMessages['cache_working'] = 'Your cache is NOT working. Please check the settings in APP/Config/core.php';
			return false;
		}
		
		return true;
	}
	
	protected function _pcre() {
		App::uses('Validation', 'Utility');
		if (!Validation::alphaNumeric('cakephp')) {
			$this->_configCheckMessages['pcre'] = 'PCRE has not been compiled with Unicode support.';
			return false;
		}
		
		if (Inflector::slug('pcre bug') != 'pcre_bug') {
			$this->_configCheckMessages['pcre'] = 'Your version of PCRE has a bug. Please upgrade to the latest version.';
			return false;
		}
		
		return true;
	}
	
	public function registerWidget($name) {
		$this->_widgets[] = $name;
	}
	
	public function getWidgetList() {
		return $this->_widgets;
	}
}