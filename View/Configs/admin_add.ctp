<div class="configs form">
<?php echo $this->Form->create('Config');?>
	<fieldset>
		<legend><?php echo __('Add Config'); ?></legend>
	<?php
		echo $this->Form->input('name');
		echo $this->Form->input('namespace');
		echo $this->Form->input('value');
		echo $this->Form->input('type');
		echo $this->Form->input('is_locked');
	?>
	</fieldset>
<?php echo $this->Form->end(__('Submit'));?>
</div>
<div class="actions">
	<h3><?php echo __('Actions'); ?></h3>
	<ul>
		<li><?php echo $this->Html->link(__('List Configuration'), array('action' => 'index'));?></li>
	</ul>
</div>
