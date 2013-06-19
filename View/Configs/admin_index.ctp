<?php
	$this->assign('title', __d('awecms', 'Configuration'));
	$this->assign('class', 'index');
?>
<table class="table table-striped table-hover">
<thead>
	<tr>
		<th><?php echo $this->Paginator->sort('name'); ?></th>
		<th><?php echo $this->Paginator->sort('value'); ?></th>
		<th class="actions"><?php echo __('Actions'); ?></th>
	</tr>
</thead>
<tbody>
	<?php foreach ($configs as $config): ?>
		<tr>
			<td><?php echo h($config['Config']['name']); ?>&nbsp;</td>
			<td><?php echo h($config['Config']['value']); ?>&nbsp;</td>
			<td class="actions">
				<?php echo $this->Html->link('<i class="icon-pencil"></i> ' . __('Edit'), array('action' => 'edit', $config['Config']['id']), array('class' => 'btn btn-small', 'escape' => false)); ?>
				<?php if ($this->Session->read('Auth.User.is_admin')) : ?>
					<?php echo $this->Form->postLink(
							'<i class="icon-remove"></i> ' . __('Delete'),
							array('action' => 'delete', $config['Config']['id']),
							array('class' => 'btn btn-small btn-danger', 'escape' => false),
							__('Are you sure you want to delete # %s?', $config['Config']['id'])
						); ?>
				<?php endif; ?>
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

<div class="pagination">
<?php
	echo $this->TwitterPaginator->pagination();
	//echo $this->Paginator->prev('<i class="icon-double-angle-left"></i>', array(), null, array('class' => 'prev disabled', 'escape' => false));
	//echo $this->Paginator->numbers(array('separator' => ''));
	//echo $this->Paginator->next('<i class="icon-double-angle-right"></i>', array(), null, array('class' => 'next disabled', 'escape' => false));
?>
</div>