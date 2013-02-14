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
			//'Controller.beforeRedirect' => array('callable' => 'controllerBeforeRedirect', 'passParams' => true),
			'Controller.beforeRender' => 'controllerBeforeRender',
			'Admin.MainMenu.beforeRender' => 'addMenuItems',
			'Widget.initialize' => 'registerWidgetClasses',
		);
	}
	
	public function controllerInitialize($event) {
		$Controller =& $event->subject();
		
		// Is a page with the admin prefix being requested. If so then setup POC admin.
		if (!empty($Controller->request->params['admin'])) {
			$Controller->helpers[] = 'PieceOCake.Menu';
			$Controller->layout = 'PieceOCake.admin';
			$this->_setupAuth($Controller);
		} else if (Hash::contains($Controller->request->params, array('plugin' => 'users', 'controller' => 'users'))) {
			$this->_setupAuth($Controller);
		}
		
		// Set the layout when logging in
		if (Hash::contains($Controller->request->params, array('plugin' => 'users', 'controller' => 'users', 'action' => 'login'))) {
			$Controller->layout = 'PieceOCake.auth';
		}
		
		// Security component fails for my clients too often
		$Controller->Components->unload('Security');
		
		// Debug test shortcut for views and controllers
		$Controller->debug = Configure::read('debug') > 0;
		$Controller->set('debug', $Controller->debug);
		
		// Register Widget Model
		ClassRegistry::init(array('class' => 'PieceOCake.Widget', 'alias' => 'Widget'));
	}
	
	protected function _setupAuth($Controller) {
		if (!$Controller->Components->enabled('Auth')) {
			App::uses('AuthComponent', 'Controller/Component');
			$Controller->Auth = $Controller->Components->load('Auth');
		}
		
		$Controller->Auth->authenticate = array(
			'Form' => array(
				'fields' => array(
					'username' => 'email',
					'password' => 'password'
				),
				'userModel' => 'Users.User', 
				'scope' => array(
					'User.active' => 1,
					'User.email_verified' => 1
				)
			)
		);
		
		$Controller->Auth->authorize = 'PieceOCake.Role';
		$Controller->Auth->loginAction = array('admin' => false, 'plugin' => 'users', 'controller' => 'users', 'action' => 'login');
		$Controller->Auth->unauthorizedRedirect = $Controller->Auth->loginAction;
		$this->_Controller =& $Controller;
		$this->_lastUser = AuthComponent::user('id');
		$Controller->getEventManager()->attach(array($this, 'controllerBeforeRedirect'), 'Controller.beforeRedirect', array('passParams' => true));
	}
	
	// If a redirect occurs after login then redirect to the route prefix for that role
	public function controllerBeforeRedirect($url, $status = null, $exit = true) {
		$Controller =& $this->_Controller;
		
		if ($this->_lastUser !== AuthComponent::user('id')) {
			$userRole = AuthComponent::user('role');
			$prefixes = Configure::read('Routing.prefixes');
			if ($url === '/') {
				if (in_array($userRole, $prefixes)) {
					return array('url' => '/' . $userRole);
				}
			} else {
				foreach ($prefixes as $prefix) {
					if (strpos($url, $prefix) === 1 && $userRole !== $prefix) {
						return array('url' => '/');
					}
				}
			}
		}
		
		/*if ($url === '/') {
			$requestUrl = Router::normalize(Router::url());
			$loginAction = Router::normalize($Controller->Auth->loginAction);
			if ($requestUrl === $loginAction) {
				$userRole = AuthComponent::user('role');
				if (in_array($userRole, Configure::read('Routing.prefixes'))) {
					return array('url' => '/' . $userRole);
				} else {
					return array('url' => '/');
				}
			}
		}*/
	}
	
	public function controllerBeforeRender($event) {
		$Controller = $event->subject();
		
		if (!empty($Controller->request->params['admin'])) {
			$actions = $this->_getControllerActions($Controller);
			$actionUrls = array();
			foreach ($actions['admin'] as $action) {
				$actionUrls[$action] = Router::url(array('action' => $action));
			}
			
			$appConfig = array(
				'BASE_URL' => Router::url('/'),
				'ADMIN_URL' => Router::url(array('admin' => true, 'plugin' => 'piece_o_cake', 'controller' => 'pages', 'action' => 'display', 'home')),
				'ACTIONS' => $actionUrls,
			);
			$Controller->set('appConfig', $appConfig);
		}
	}
	
	protected function _getControllerActions($Controller) {
		$prefixes = Router::prefixes();
		$actions = array_fill_keys($prefixes, array());
		
		foreach ($Controller->methods as $methodName) {
			$method = new ReflectionMethod($Controller, $methodName);
			$isPrivate = $method->name[0] === '_' || !$method->isPublic();
			
			if (!$isPrivate) {
				if (empty($prefixes) || strpos($methodName, '_') === false) {
					$actions[null][] = $methodName;
				} else {
					list($prefix, $action) = explode('_', $methodName, 2);
					if (in_array($prefix, $prefixes)) {
						$actions[$prefix][] = $action;
					} else {
						$actions[null][] = $methodName;
					}
				}
			}
		}
		return $actions;
	}
	
	public function addMenuItems($event) {
		$Menu = $event->subject();
		$Menu->addItem('Widgets', array('plugin' => 'piece_o_cake', 'controller' => 'widgets', 'action' => 'index'));
		$Menu->addItem('Configuration', array('plugin' => 'piece_o_cake', 'controller' => 'configs', 'action' => 'index'));
		$Menu->addItem('Users', array('plugin' => 'users', 'controller' => 'users', 'action' => 'index'));
	}
	
	public function registerWidgetClasses($event) {
		$Widget = $event->subject();
		$Widget->registerWidgetClass('PieceOCake.Html', array('editUrl' => array('controller' => 'html_widget')));
		$Widget->registerWidgetClass('PieceOCake.Element', array('editUrl' => array('controller' => 'element_widget')));
		$Widget->registerWidgetClass('PieceOCake.Common', array('editUrl' => array('controller' => 'common_widget')));
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