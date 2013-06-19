<div id="login" class="well">
	<fieldset>
		<legend><?php echo __d('awecms', 'Please Login'); ?></legend>
		<?php echo $this->Session->flash('auth', array('element' => 'alert')); ?>
		<?php
			echo $this->Form->create('User', array(
				'action' => 'login',
				'id' => 'LoginForm',
				'layout' => 'horizontal'));
			echo $this->Form->input('username');
			echo $this->Form->input('password', array('required' => true));
			/*echo $this->Form->actions(array(
				'submit' => array('text' => 'Login'),
				'cancel' => array('url' => '/')
			));*/
			echo $this->Form->end('Login');
		?>
	</fieldset>
</div>