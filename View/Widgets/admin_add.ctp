<div class="widgets form">
<?php echo $this->Form->create('Widget'); ?>
	<fieldset>
		<legend><?php echo __('Admin Add Widget'); ?></legend>
	<?php
		echo $this->Form->input('name');
		echo $this->Form->input('block');
		echo $this->Form->input('class');
		echo $this->Form->input('content');
		echo $this->Form->input('order');
		echo $this->Form->input('is_active');
	?>
	</fieldset>
<?php echo $this->Form->end(__('Submit')); ?>
</div>
<div class="actions">
	<h3><?php echo __('Actions'); ?></h3>
	<ul>

		<li><?php echo $this->Html->link(__('List Widgets'), array('action' => 'index')); ?></li>
	</ul>
</div>
