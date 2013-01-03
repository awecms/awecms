<?php

App::uses('CakeEventListener', 'Event');
App::uses('Inflector', 'Utility');
App::uses('ClassRegistry', 'Utility');

class PieceOCake implements CakeEventListener {
	
	protected static $_instance = null;
	
	protected $_configChecks = array();
	protected $_configCheckMessages = array();
	
	protected $_blocks = array();
	
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
			'Widget.initialize' => 'registerWidgetClasses',
		);
	}
	
	public function controllerInitialize($event) {
		$Controller = $event->subject();
		
		// Is a page with the admin prefix being requested. If so then setup POC admin.
		if (!empty($Controller->request->params['admin'])) {
			$Controller->helpers[] = 'PieceOCake.Menu';
			$Controller->layout = 'PieceOCake.admin';
			App::uses('AuthComponent', 'Controller/Component');
			
			$settings = array(
				'all' => array(
					'scope' => array(
						'User.active' => 1,
						//'User.is_admin' => 1,
						'User.role' => 'admin',
					),
				),
				'authenticate' => array('Form'),
				'loginAction' => array('admin' => false, 'plugin' => 'users', 'controller' => 'users', 'action' => 'login'),
			);
			$Controller->Components->load('Auth', $settings);
		}
		
		// Set the layout when logging in and for users
		if (Hash::contains($Controller->request->params, array('plugin' => 'users', 'controller' => 'users', 'action' => 'login'))) {
			$Controller->layout = 'PieceOCake.auth';
		} else if (Hash::contains($Controller->request->params, array('plugin' => 'users', 'controller' => 'users'))) {
			$Controller->layout = 'PieceOCake.admin';
		}
		
		// Security component fails for my clients too often
		$Controller->Components->unload('Security');
		
		// Set the layout to POC auth layout when logining into backend
		/*$named =& $Controller->request->params['named'];
		$data =& $Controller->request->data;
		$url = Router::url(array('admin' => true, 'plugin' => 'piece_o_cake', 'controller' => 'pages', 'action' => 'display', 'home', 'base' => false));
		if (!empty($named['return_to']) && $named['return_to'] == 'admin') {
			$data['User']['return_to'] = $url;
		}
		if (!empty($data['User']['return_to']) && $data['User']['return_to'] == $url) {
			$Controller->layout = 'PieceOCake.auth';
			$named['return_to'] = urlencode($url);
		}*/
		
		// Debug test shortcut for views and controllers
		$Controller->debug = Configure::read('debug') > 0;
		$Controller->set('debug', $Controller->debug);
		
		// Register Widget Model
		ClassRegistry::init(array('class' => 'PieceOCake.Widget', 'alias' => 'Widget'));
	}
	
	public function addMenuItems($event) {
		$Menu = $event->subject();
		$Menu->addItem('Widgets', array('plugin' => 'piece_o_cake', 'controller' => 'widgets', 'action' => 'index'));
		$Menu->addItem('Configuration', array('plugin' => 'piece_o_cake', 'controller' => 'configs', 'action' => 'index'));
		$Menu->addItem('Users', array('plugin' => 'users', 'controller' => 'users', 'action' => 'index'));
	}
	
	public function registerWidgetClasses($event) {
		$Widget = $event->subject();
		$Widget->registerWidgetClass('PieceOCake.Html', array('editUrl' => array('action' => 'edit_html')));
		$Widget->registerWidgetClass('PieceOCake.Element', array('editUrl' => array('action' => 'edit_element')));
		$Widget->registerWidgetClass('PieceOCake.Common', array('editUrl' => array('action' => 'edit_common')));
		$Widget->registerBlock('common');
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

}