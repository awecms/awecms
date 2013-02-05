<div class="widgets form">
<?php echo $this->Form->create('Widget'); ?>
	<fieldset>
		<legend><?php echo __('Admin Edit Widget'); ?></legend>
	<?php
		echo $this->Form->input('id');
		echo $this->Form->input('name');
		echo $this->Form->input('block', array('options' => $blocks));
		echo $this->Form->input('Widget.data.widget_id', array('options' => $widgets));
		echo $this->Form->input('order');
		echo $this->Form->input('is_active');
	?>
	</fieldset>
<?php echo $this->Form->end(__('Submit')); ?>
</div>
<div class="actions">
	<h3><?php echo __('Actions'); ?></h3>
	<ul>
		<?php if ($this->Session->read('Auth.User.is_admin')) : ?>
			<li><?php echo $this->Form->postLink(__('Delete'), array('controller' => 'widgets', 'action' => 'delete', $this->Form->value('Widget.id')), null, __('Are you sure you want to delete # %s?', $this->Form->value('Widget.id'))); ?></li>
		<?php endif; ?>
		<li><?php echo $this->Html->link(__('List Widgets'), array('controller' => 'widgets', 'action' => 'index')); ?></li>
	</ul>
</div>
