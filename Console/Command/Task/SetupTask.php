<?php

App::uses('BakeTask', 'Console/Command/Task');

class SetupTask extends BakeTask {

	public function execute() {
		parent::execute();
		return $this->_interactive();
	}
	
	protected function _interactive() {
		$this->interactive = true;
		
		if (file_exists(APP . 'Config' . DS . 'awecms.php')) {
			$overwrite = $this->in('Warning: This will overwrite your current config. Are you sure?', array('y', 'n'), 'n');
			if (strtolower($overwrite) != 'y') {
				return false;
			}
		}
		
		$designCompany = $this->in('Design Company:', null, 'AweCMS');
		$websiteName = $this->in('Website Name:', null, 'AweCMS Website');
		$defaultEditor = $this->in('Default Editor:', null, 'Ckeditor.Ckeditor');
		$titleFormat = $this->in('Title Format:', null, $websiteName . ': %s');
		while (empty($defaultEmail)) {
			$defaultEmail = $this->in('Admin Email:');
		}
		
		$config = array(
			'Awecms' => compact('designCompany', 'websiteName', 'defaultEditor', 'titleFormat'),
			'App' => compact('defaultEmail'),
		);
		Configure::write($config);
		$result = Configure::dump('awecms', 'default', array_keys($config));
		if ($result) {
			$this->out('Configuration saved');
		} else {
			$this->out('Configuration not saved. Check permissions of APP/Config/awecms.php.');
		}
		
		return $result;
	}
}