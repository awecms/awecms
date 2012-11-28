<?php

class UserShell extends AppShell {
	
	public $uses = array('PieceOCake.User');

	public function create() {
		if (isset($this->args[0])) {
			$username = $this->args[0];
		} else {
			$username = 'admin';
		}
		
		if (isset($this->args[1])) {
			$password = $this->args[1];
		} else {
			$password = 'admin';
		}
		
		$password_repeat = $password;
		$user = array('User' => compact('username', 'password', 'password_repeat'));
		
		$this->User->create();
		if ($this->User->save($user)) {
			$this->out('The user has been saved');
		} else {
			$this->out('The user could not be saved. Please, try again.');
		}
	}
}