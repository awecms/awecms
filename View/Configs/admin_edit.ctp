<?php
	$this->assign('title', __d('awecms', 'Configuration'));
	$this->assign('class', 'form');
	$this->Menu->match(array('action' => 'index'));
	if ($this->Session->read('Auth.User.is_admin')):
		$this->Menu->addItem(
			__d('awecms', 'Delete'),
			array('action' => 'delete', $this->Form->value('Config.id')),
			array(
				'group' => 'actions',
				'icon' => 'remove',
				'confirm' => __d('awecms', 'Are you sure you want to delete %s?', $this->Form->value('Config.name'))
			)
		);
	endif;
?>
<?php echo $this->Form->create('Config', array('class' => 'form-horizontal')); ?>
<fieldset>
	<legend>
		<?php
			list(, $name) = pluginSplit($this->Form->value('Config.name'));
			echo __d('awecms', 'Edit %s', Inflector::humanize($name));
		?>
	</legend>
<?php
	echo $this->Form->input('id');
	echo $this->Form->input('name');
	echo $this->Form->input('value');
	echo $this->Form->actions();
?>
</fieldset>
<?php echo $this->Form->end();