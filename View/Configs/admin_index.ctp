<div class="configs index<?php echo !$debug ? ' noactions' : ''; ?>">
	<h2><?php echo __('Configs');?></h2>
	<table cellpadding="0" cellspacing="0">
	<tr>
			<th><?php echo $this->Paginator->sort('namespace');?></th>
			<th><?php echo $this->Paginator->sort('name');?></th>
			<th><?php echo $this->Paginator->sort('value');?></th>
			<th class="actions"><?php echo __('Actions');?></th>
	</tr>
	<?php
	foreach ($configs as $config): ?>
	<tr>
		<td><?php echo h($config['Config']['namespace']); ?>&nbsp;</td>
		<td><?php echo h(Inflector::humanize($config['Config']['name'])); ?>&nbsp;</td>
		<td>
			<?php
			if (is_string($config['Config']['value'])) {
				echo h(preg_replace('/[\r\n].*/', '', $config['Config']['value']));
			} else {
				echo preg_replace('/[\r\n].*/', '', Debugger::exportVar($config['Config']['value'], 1));
			}
			?>
			&nbsp;
		</td>
		<td class="actions">
			<?php echo $this->Html->link(__('View'), array('action' => 'view', $config['Config']['id'])); ?>
			<?php echo $this->Html->link(__('Edit'), array('action' => 'edit', $config['Config']['id'])); ?>
			<?php
				if ($debug) {
					echo $this->Form->postLink(__('Delete'), array('action' => 'delete', $config['Config']['id']), null, __('Are you sure you want to delete "%s"?', $config['Config']['name']));
				}
			?>
		</td>
	</tr>
<?php endforeach; ?>
	</table>
	<p>
	<?php
	echo $this->Paginator->counter(array(
	'format' => __('Page {:page} of {:pages}, showing {:current} records out of {:count} total, starting on record {:start}, ending on {:end}')
	));
	?>	</p>

	<div class="paging">
	<?php
		echo $this->Paginator->prev('< ' . __('previous'), array(), null, array('class' => 'prev disabled'));
		echo $this->Paginator->numbers(array('separator' => ''));
		echo $this->Paginator->next(__('next') . ' >', array(), null, array('class' => 'next disabled'));
	?>
	</div>
</div>
<?php if ($debug): ?>
	<div class="actions">
		<h3><?php echo __('Actions'); ?></h3>
		<ul>
			<li><?php echo $this->Html->link(__('New Config'), array('action' => 'add')); ?></li>
		</ul>
	</div>
<?php endif; ?>
