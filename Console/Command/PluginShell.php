<?php

App::uses('AppShell', 'Console/Command');

class PluginShell extends AppShell {

	public $tasks = array('Awecms.Install');
	
	public $connection = 'default';

	public function startup() {
		parent::startup();
		Configure::write('debug', 2);
		Configure::write('Cache.disable', 1);
		
		$task = Inflector::classify($this->command);
		if (isset($this->{$task})) {
			if (isset($this->params['connection'])) {
				$this->{$task}->connection = $this->params['connection'];
			} else {
				$this->{$task}->connection = 'default';
			}
		}
	}
	
	public function getOptionParser() {
		$parser = parent::getOptionParser();
		return $parser->description(__d('plugin_console',
			'Run the Install / Uninstall script for a plugin.'
		))->addSubcommand('install', array(
			'help' => __d('plugin_console', 'Install a plugin.'),
			'parser' => $this->Install->getOptionParser()
		//))->addSubcommand('uninstall', array(
		//	'help' => __d('plugin_console', 'Uninstall a plugin.'),
		))->addOption('connection', array(
			'help' => __d('icing_console', 'Database connection to use.'),
			'short' => 'c',
			'default' => 'default'
		));
	}

}