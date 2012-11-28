<div class="slugs form">
<?php echo $this->Form->create('Slug');?>
	<fieldset>
		<legend><?php echo __('Add Slug'); ?></legend>
	<?php
		echo $this->Form->input('name');
		echo $this->Form->input('route');
		echo $this->Form->input('defaults');
		echo $this->Form->input('options');
		echo $this->Form->input('is_active');
	?>
	</fieldset>
<?php echo $this->Form->end(__('Submit'));?>
</div>
<div class="actions">
	<h3><?php echo __('Actions'); ?></h3>
	<ul>

		<li><?php echo $this->Html->link(__('List Slugs'), array('action' => 'index'));?></li>
	</ul>
</div>
