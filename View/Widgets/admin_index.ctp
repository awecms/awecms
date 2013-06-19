<div class="widgets index<?php echo $this->Session->read('Auth.User.is_admin') ? '' : ' noactions' ?>">
	<h2><?php echo __('Widgets'); ?></h2>
	<table class="table">
		<thead>
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
		</thead>
		<tbody>
			<?php
			$params = $this->Paginator->params();
			if (empty($params['order'])) {
				$order = 'block';
			} else {
				$order = array_keys($params['order']);
				list( ,$order) = explode('.', $order[0]);
			}
			$titleVar = isset(${Inflector::pluralize($order)}) ? ${Inflector::pluralize($order)} : null;
			$colspan = $this->Session->read('Auth.User.is_admin') ? 6 : 5;
			$lastKey = null;
			foreach ($widgets as $widget): ?>
			<?php if (($order == 'block' || $order == 'class') && $lastKey != $widget['Widget'][$order]): ?>
				<tr>
					<th colspan="<?php echo $colspan; ?>"><?php echo h($titleVar[$widget['Widget'][$order]]); ?></th>
				</tr>
				<?php $lastKey = $widget['Widget'][$order]; ?>
			<?php endif; ?>
			<tr>
				<td><?php echo h($widget['Widget']['name']); ?>&nbsp;</td>
				<td><?php echo h($blocks[$widget['Widget']['block']]); ?>&nbsp;</td>
				<?php if ($this->Session->read('Auth.User.is_admin')) : ?>
					<?php if (isset($classes[$widget['Widget']['class']])): ?>
						<td><?php echo h($classes[$widget['Widget']['class']]); ?>&nbsp;</td>
					<?php else: ?>
						<td><?php echo h($widget['Widget']['class']); ?>&nbsp;</td>
					<?php endif; ?>
				<?php endif; ?>
				<td><?php echo h($widget['Widget']['order']); ?>&nbsp;</td>
				<td><?php echo h($widget['Widget']['is_active']); ?>&nbsp;</td>
				<td class="actions">
					<?php
						if (isset($editUrls[$widget['Widget']['class']])):
							$url = $editUrls[$widget['Widget']['class']];
							$url[] = $widget['Widget']['id'];
							echo $this->Html->link(__('Edit'), $url);
						endif;
						echo ' ';
						if ($this->Session->read('Auth.User.is_admin')):
							echo $this->Form->postLink(__('Delete'), array('action' => 'delete', $widget['Widget']['id']), null, __('Are you sure you want to delete # %s?', $widget['Widget']['id']));
						endif;
					?>
				</td>
			</tr>
			<?php endforeach; ?>
		</tbody>
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