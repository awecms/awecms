<?php
	$this->assign('title', __d('awecms_content', 'Widgets'));
	$this->assign('class', 'index');
?>
<table class="table table-striped table-hover">
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
		foreach ($widgets as $widget):
			$url = null;
			if (isset($editUrls[$widget['Widget']['class']])):
				$url = $editUrls[$widget['Widget']['class']];
				$url[] = $widget['Widget']['id'];
			endif;
			?>
		<?php if (($order == 'block' || $order == 'class') && $lastKey != $widget['Widget'][$order]): ?>
			<tr>
				<th colspan="<?php echo $colspan; ?>"><?php echo h($titleVar[$widget['Widget'][$order]]); ?></th>
			</tr>
			<?php $lastKey = $widget['Widget'][$order]; ?>
		<?php endif; ?>
		<tr>
			<td>
				<?php
					if (empty($url)):
						echo h($widget['Widget']['name']);
					else:
						echo $this->Html->link($widget['Widget']['name'], $url);
					endif;
				?>
				&nbsp;
			</td>
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
					if (!empty($url)):
						echo $this->Html->link(
							'<i class="icon-pencil"></i> ' . __d('awecms', 'Edit'),
							$url,
							array('escape' => false, 'class' => 'btn btn-small')
						);
					endif;
					echo ' ';
					echo $this->Form->postLink(
						'<i class="icon-remove"></i> ' . __d('awecms', 'Delete'),
						array('action' => 'delete', $widget['Widget']['id']),
						array('escape' => false, 'class' => 'btn btn-small btn-danger'),
						__d('awecms', 'Are you sure you want to delete \'%s\'?', $widget['Widget']['name'])
					);
				?>
			</td>
		</tr>
		<?php endforeach; ?>
	</tbody>
</table>
<p class="pagination-counter">
	<?php
	echo $this->Paginator->counter(array(
		'format' => __d('awecms', 'Page {:page} of {:pages}, showing {:current} records out of {:count} total, starting on record {:start}, ending on {:end}')
	));
	?>
</p>
<div class="paging">
	<?php
	echo $this->TwitterPaginator->pagination();
	?>
</div>