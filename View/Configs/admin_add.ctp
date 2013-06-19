<?php
	$this->assign('title', __d('awecms', 'Configuration'));
	$this->assign('class', 'form');
?>
<?php echo $this->Form->create('Config', array('layout' => 'horizontal')); ?>
<fieldset>
	<legend><?php echo __d('awecms', 'New Configuration'); ?></legend>
	<?php
		echo $this->Form->input('name');
		echo $this->Form->input('value');
		echo $this->Html->div('form-actions',
			$this->Form->button(__d('awecms', 'Save'), array('class' => 'btn btn-primary', 'type' => 'submit')) . ' ' .
			$this->Html->link(__d('awecms', 'Cancel'), array('action' => 'index'), array('class' => 'btn'))
		);
	?>
</fieldset>
<?php echo $this->Form->end();