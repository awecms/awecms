<?php

App::uses('BakeTask', 'Console/Command/Task');

class UserTask extends BakeTask {
	
	public $uses = array('Users.User');

	public function execute() {
		parent::execute();
		return $this->_interactive();
	}
	
	protected function _interactive() {
		$this->interactive = true;
		// Defaults
		$defaultEmail = Configure::read('App.defaultEmail');
		$defaultRole = Configure::read('Users.defaultRole');
		$tos = $active = $email_verified = 1;
		$password = true;
		$temppassword = false;
		
		// Get input
		$email = $this->in('Email:', null, $defaultEmail);
		$username = $slug = $this->in('Username:', null, 'admin');
		while ($password !== $temppassword) {
			$password = $this->in('Password:');
			$temppassword = $this->in('Repeat Password:');
		}
		$is_admin = strtolower($this->in('Is Admin?:', array('y', 'n'), 'y')) == 'y';
		$role = $is_admin ? 'admin' : $this->in('Role:', null, empty($defaultRole) ? 'registered' : $defaultRole);
		
		// Add the user
		$user = array('User' => compact('email', 'username', 'password', 'temppassword', 'is_admin', 'role', 'slug', 'tos', 'active', 'email_verified'));
		$this->User->create();
		$result = $this->User->add($user);
		if ($result) {
			$this->out('The user has been saved');
		} else {
			foreach ($this->User->validationErrors as $error) {
				$this->out($error);
			}
			$this->out('The user could not be saved. Please, try again.');
		}
		
		return $result;
	}
}