<?php

App::uses('AppShell', 'Console/Command');

class IcingShell extends AppShell {

	public $tasks = array('Widget', 'User');
	
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
	
	public function main() {
		$this->out(__d('icing_console', 'Icing Interactive Bake Shell'));
		$this->hr();
		$this->out(__d('icing_console', '[W]idget Model'));
		$this->out(__d('icing_console', '[U]ser'));
		$this->out(__d('icing_console', '[Q]uit'));
		
		$classToBake = strtoupper($this->in(__d('icing_console', 'What would you like to Bake?'), array('W', 'U', 'Q')));
		switch ($classToBake) {
			case 'W':
				$this->Widget->execute();
				break;
			case 'U':
				$this->User->execute();
				break;
			case 'Q':
				exit(0);
				break;
			default:
				$this->out(__d('icing_console', 'You have made an invalid selection. Please choose a what you to Bake by entering W, U or Q.'));
		}
		$this->hr();
		$this->main();
	}
	
	public function getOptionParser() {
		$parser = parent::getOptionParser();
		return $parser->description(__d('icing_console',
			'The Icing script generates widgets and users for your application.' .
			' If run with no command line arguments, Icing guides the user through the creation process.' .
			' You can customize the generation process by telling Icing where different parts of your application are using command line arguments.'
		))->addSubcommand('widget', array(
			'help' => __d('icing_console', 'Bake a model for a widget.'),
			'parser' => $this->Widget->getOptionParser()
		))->addSubcommand('user', array(
			'help' => __d('icing_console', 'Add a new user to the database.'),
		))->addOption('connection', array(
			'help' => __d('icing_console', 'Database connection to use.'),
			'short' => 'c',
			'default' => 'default'
		));
	}

}