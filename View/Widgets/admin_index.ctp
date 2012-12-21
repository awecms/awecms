<div class="widgets index<?php echo $this->Session->read('Auth.User.is_admin') ? '' : ' noactions' ?>">
	<h2><?php echo __('Widgets'); ?></h2>
	<table cellpadding="0" cellspacing="0">
	<tr>
			<th><?php echo $this->Paginator->sort('name'); ?></th>
			<th><?php echo $this->Paginator->sort('block'); ?></th>
			<?php if ($this->Session->read('Auth.User.is_admin')) : ?>
				<th><?php echo $this->Paginator->sort('class'); ?></th>
			<?php endif; ?>
			<th><?php echo $this->Paginator->sort('order'); ?></th>
			<th><?php echo $this->Paginator->sort('is_active'); ?></th>
			<th class="actions"><?php echo __('Actions'); ?></th>
	</tr>
	<?php
	foreach ($widgets as $widget): ?>
	<tr>
		<td><?php echo h($widget['Widget']['name']); ?>&nbsp;</td>
		<td><?php echo h($blocks[$widget['Widget']['block']]); ?>&nbsp;</td>
		<?php if ($this->Session->read('Auth.User.is_admin')) : ?>
			<td><?php echo h($classes[$widget['Widget']['class']]); ?>&nbsp;</td>
		<?php endif; ?>
		<td><?php echo h($widget['Widget']['order']); ?>&nbsp;</td>
		<td><?php echo h($widget['Widget']['is_active']); ?>&nbsp;</td>
		<td class="actions">
			<?php
			$url = $editUrls[$widget['Widget']['class']];
			$url[] = $widget['Widget']['id'];
			echo $this->Html->link(__('Edit'), $url);
			?>
			<?php if ($this->Session->read('Auth.User.is_admin')) : ?>
				<?php echo $this->Form->postLink(__('Delete'), array('action' => 'delete', $widget['Widget']['id']), null, __('Are you sure you want to delete # %s?', $widget['Widget']['id'])); ?>
			<?php endif; ?>
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
<?php if ($this->Session->read('Auth.User.is_admin')) : ?>
	<div class="actions">
		<h3><?php echo __('Actions'); ?></h3>
		<ul>
			<li><?php echo $this->Html->link(__('New Widget'), array('action' => 'add')); ?></li>
		</ul>
	</div>
<?php endif; ?>