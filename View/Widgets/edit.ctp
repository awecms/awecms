<?php
	if ($this->fetch('title') === ''):
		$this->assign('title', __('Edit Widget'));
	endif;
?>
<div class="widgets form">
<?php echo $this->Form->create('Widget'); ?>
	<fieldset>
		<legend><?php echo $this->fetch('title'); ?></legend>
	<?php
		echo $this->Form->input('id');
		echo $this->Form->input('name');
		echo $this->Form->input('block', array('options' => $blocks));
		echo $this->fetch('content');
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
			<li><?php echo $this->Form->postLink(__('Delete'), array('plugin' => 'awecms', 'controller' => 'widgets', 'action' => 'delete', $this->Form->value('Widget.id')), null, __('Are you sure you want to delete # %s?', $this->Form->value('Widget.id'))); ?></li>
		<?php endif; ?>
		<li><?php echo $this->Html->link(__('List Widgets'), array('plugin' => 'awecms', 'controller' => 'widgets', 'action' => 'index')); ?></li>
	</ul>
</div>
