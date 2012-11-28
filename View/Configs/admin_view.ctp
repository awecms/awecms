<div class="configs view">
<h2><?php  echo __($config['Config']['namespace'] . ': ' . Inflector::humanize($config['Config']['name']));?></h2>
	<dl>
		<dt><?php echo __('Value'); ?></dt>
		<dd>
			<?php
			if (is_string($config['Config']['value'])) {
				echo nl2br(h($config['Config']['value']));
			} else {
				echo Debugger::exportVar($config['Config']['value'], 10);
			}
			?>
			&nbsp;
		</dd>
	</dl>
</div>
<div class="actions">
	<h3><?php echo __('Actions'); ?></h3>
	<ul>
		<li><?php echo $this->Html->link(__('Edit Configuration'), array('action' => 'edit', $config['Config']['id'])); ?> </li>
		<li><?php echo $this->Form->postLink(__('Delete Configuration'), array('action' => 'delete', $config['Config']['id']), null, __('Are you sure you want to delete # %s?', $config['Config']['id'])); ?> </li>
		<li><?php echo $this->Html->link(__('List Configuration'), array('action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Configuration'), array('action' => 'add')); ?> </li>
	</ul>
</div>
