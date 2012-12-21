<div class="configs form">
<?php echo $this->Form->create('Config'); ?>
	<fieldset>
		<legend><?php echo __('Admin Edit Config'); ?></legend>
	<?php
		echo $this->Form->input('id');
		echo $this->Form->input('name');
		echo $this->Form->input('value');
	?>
	</fieldset>
<?php echo $this->Form->end(__('Submit')); ?>
</div>
<div class="actions">
	<h3><?php echo __('Actions'); ?></h3>
	<ul>
		<?php if ($this->Session->read('Auth.User.is_admin')) : ?>
			<li><?php echo $this->Form->postLink(__('Delete'), array('action' => 'delete', $this->Form->value('Config.id')), null, __('Are you sure you want to delete # %s?', $this->Form->value('Config.id'))); ?></li>
		<?php endif; ?>
		<li><?php echo $this->Html->link(__('List Configs'), array('action' => 'index')); ?></li>
	</ul>
</div>
