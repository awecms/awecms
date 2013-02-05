<?php

App::uses('BakeTask', 'Console/Command/Task');

class InstallTask extends BakeTask {

/**
 * path to plugins directory
 *
 * @var array
 */
	public $paths = null;

/**
 * Path to the bootstrap file. Changed in tests.
 *
 * @var string
 */
	public $bootstrap = null;
	
	//public $pluginName = null;
	
	protected $_isBootstrapConfigured = null;

/**
 * initialize
 *
 * @return void
 */
	public function initialize() {
		$this->paths = App::path('Plugin');
		$this->bootstrap = APP . 'Config' . DS . 'bootstrap.php';
	}

	public function execute() {
		parent::execute();
		
		$this->pluginName = $this->args[0];
		$pluginPath = null;
		foreach ($this->paths as $path) {
			$checkPath = $path . $this->pluginName;
			if (file_exists($checkPath)) {
				$pluginPath = $checkPath;
				break;
			}
		}
		
		if (empty($pluginPath)) {
			$this->out(__d('plugin_console', 'Plugin \'%s\' does not exist.', $this->pluginName));
			return;
		}
		
		$task = $this->pluginName . 'Install';
		$hasTask = file_exists($pluginPath . DS . 'Console' . DS . 'Command' . DS . 'Task' . DS . $task . 'Task.php');
		
		if ($hasTask) {
			$this->_loadPlugin();
			$this->Tasks->load($this->pluginName . '.' . $task)->execute();
			$this->out(__d('plugin_console', 'Plugin \'%s\' installed.', $this->pluginName));
			return;
		}
		
		// Add plugin to the applictaion boostrap
		$hasBootstrap = file_exists($pluginPath . DS . 'Config' . DS . 'bootstrap.php');
		$hasRoutes = file_exists($pluginPath . DS . 'Config' . DS . 'routes.php');
		$this->_addToBootstrap($hasBootstrap, $hasRoutes);
		
		// Load the schema if there is one
		$hasSchema = file_exists($pluginPath . DS . 'Config' . DS . 'Schema' . DS . 'schema.php');
		if ($hasSchema) {
			$this->_createSchema();
		}
		
		$this->out(__d('plugin_console', 'Plugin \'%s\' installed.', $this->pluginName));
	}
	
	protected function _loadPlugin() {
		if (!$this->_isBootstrapConfigured() && !CakePlugin::loaded($this->pluginName)) {
			CakePlugin::load($this->pluginName);
		}
	}
	
	protected function _isBootstrapConfigured() {
		if ($this->_isBootstrapConfigured === null) {
			$this->_isBootstrapConfigured = CakePlugin::loaded($this->pluginName);
		}
		return $this->_isBootstrapConfigured;
	}
	
	protected function _addToBootstrap($hasBootstrap = false, $hasRoutes = false) {
		if (!$this->_isBootstrapConfigured()) {
			$bootstrap = new File($this->bootstrap, false);
			$contents = $bootstrap->read();
			if (!preg_match("@\n\s*CakePlugin::loadAll@", $contents)) {
				$hasBootstrap = $boostrap ? 'true' : 'false';
				$hasRoutes = $hasRoutes ? 'true' : 'false';
				$bootstrap->append("\nCakePlugin::load('$plugin', array('bootstrap' => $hasBootstrap, 'routes' => $hasRoutes));\n");
				$this->out('');
				$this->out(__d('cake_dev', '%s modified', $this->bootstrap));
			}
			$this->out(__d('plugin_console', 'Plugin \'%s\' was added to bootstrap.', $this->pluginName));
		} else {
			$this->out(__d('plugin_console', 'Plugin \'%s\' did not need to be added to bootstrap.', $this->pluginName));
		}
	}
	
	protected function _createSchema($file = null) {
		$this->_loadPlugin();
		$cmd = sprintf('schema create -p %s', $this->pluginName);
		if ($file) {
			$cmd .= sprintf(' -f %s', $file);
		}
		return $this->dispatchShell($cmd);
	}
	
	public function getOptionParser() {
		$parser = parent::getOptionParser();
		return $parser->description(__d('plugin_console',
			'Run the Install script for a plugin.'
		))->addArgument('pluginName', array(
			'help' => __d('plugin_console', 'The name of a plugin.'),
			'required' => true,
		));
	}

}