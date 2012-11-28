<div class="configs form">
<?php echo $this->Form->create('Config');?>
	<fieldset>
		<legend><?php echo __('Edit ' . $this->data['Config']['namespace'] . ': ' . Inflector::humanize($this->data['Config']['name'])); ?></legend>
	<?php
		echo $this->Form->input('id');
		$type = $debug ? 'text' : 'hidden';
		echo $this->Form->input('name', array('type' => $type));
		echo $this->Form->input('namespace', array('type' => $type));
		echo $this->Form->input('value');
		if ($debug) {
			echo $this->Form->input('type');
			echo $this->Form->input('is_locked');
		}
	?>
	</fieldset>
<?php echo $this->Form->end(__('Submit'));?>
</div>
<div class="actions">
	<h3><?php echo __('Actions'); ?></h3>
	<ul>
		<?php if ($debug): ?>
			<li><?php echo $this->Form->postLink(__('Delete'), array('action' => 'delete', $this->Form->value('Config.id')), null, __('Are you sure you want to delete # %s?', $this->Form->value('Config.id'))); ?></li>
		<?php endif; ?>
		<li><?php echo $this->Html->link(__('List Configuration'), array('action' => 'index'));?></li>
	</ul>
</div>
