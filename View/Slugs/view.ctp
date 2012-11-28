<div class="slugs view">
<h2><?php  echo __('Slug');?></h2>
	<dl>
		<dt><?php echo __('Id'); ?></dt>
		<dd>
			<?php echo h($slug['Slug']['id']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Name'); ?></dt>
		<dd>
			<?php echo h($slug['Slug']['name']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Route'); ?></dt>
		<dd>
			<?php echo h($slug['Slug']['route']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Defaults'); ?></dt>
		<dd>
			<?php echo h($slug['Slug']['defaults']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Options'); ?></dt>
		<dd>
			<?php echo h($slug['Slug']['options']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Is Active'); ?></dt>
		<dd>
			<?php echo h($slug['Slug']['is_active']); ?>
			&nbsp;
		</dd>
	</dl>
</div>
<div class="actions">
	<h3><?php echo __('Actions'); ?></h3>
	<ul>
		<li><?php echo $this->Html->link(__('Edit Slug'), array('action' => 'edit', $slug['Slug']['id'])); ?> </li>
		<li><?php echo $this->Form->postLink(__('Delete Slug'), array('action' => 'delete', $slug['Slug']['id']), null, __('Are you sure you want to delete # %s?', $slug['Slug']['id'])); ?> </li>
		<li><?php echo $this->Html->link(__('List Slugs'), array('action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Slug'), array('action' => 'add')); ?> </li>
	</ul>
</div>
