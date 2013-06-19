<?php

App::uses('BakeTask', 'Console/Command/Task');

class WidgetTask extends BakeTask {

	public $tasks = array('Project', 'Template');
	
	public function initialize() {
		$this->path = current(App::path('Model'));
	}

	public function execute() {
		parent::execute();
		if (empty($this->args)) {
			return $this->_interactive();
		}
		
		if (isset($this->args[0])) {
			$controller = $this->_controllerName($this->args[0]);
			$actions = '';
			
			$admin = $this->Project->getPrefix();
			if ($admin) {
				$this->out(__d('cake_console', 'Adding %s methods', $admin));
				$actions .= "\n" . $this->bakeActions($controller, $admin);
			}
			
			$this->bake($controller, $actions);
		}
	}
	
	public function bakeActions($controllerName, $admin = null, $wannaUseSession = true) {
		$currentModelName = $modelImport = $this->_modelName($controllerName);
		$plugin = $this->plugin;
		if ($plugin) {
			$plugin .= '.';
		}
		App::uses('Widget', 'Awecms.Model');
		if (!class_exists($modelImport)) {
			$this->err(__d('cake_console', 'You must have a model for this class to build basic methods. Please try again.'));
			$this->_stop();
		}

		$modelObj = ClassRegistry::init($currentModelName);
		$controllerPath = $this->_controllerPath($controllerName);
		$pluralName = $this->_pluralName($currentModelName);
		$singularName = Inflector::variable($currentModelName);
		$singularHumanName = $this->_singularHumanName($controllerName);
		$pluralHumanName = $this->_pluralName($controllerName);
		$displayField = $modelObj->displayField;
		$primaryKey = $modelObj->primaryKey;

		$this->Template->set(compact(
			'plugin', 'admin', 'controllerPath', 'pluralName', 'singularName',
			'singularHumanName', 'pluralHumanName', 'modelObj', 'wannaUseSession', 'currentModelName',
			'displayField', 'primaryKey'
		));
		$actions = $this->Template->generate('actions', 'controller_actions');
		return $actions;
	}
	
	public function getOptionParser() {
		$parser = parent::getOptionParser();
		return $parser->description(
				__d('icing_console', 'Bake a controller for a widget.')
			)->addArgument('name', array(
				'help' => __d('icing_console', 'Name of the controller to bake. Can use Plugin.name to bake controllers into plugins.')
			))->addOption('plugin', array(
				'short' => 'p',
				'help' => __d('icing_console', 'Plugin to bake the controller into.')
			))->addOption('connection', array(
				'short' => 'c',
				'help' => __d('icing_console', 'The connection the Widget model is on.')
			))->epilog(__d('icing_console', 'Omitting all arguments and options will enter into an interactive mode.'));
	}

}