<div class="widgets form">
<?php echo $this->Form->create('Widget', array('type' => 'file')); ?>
	<fieldset>
		<legend><?php echo __('Admin Edit Widget'); ?></legend>
	<?php
		echo $this->Form->input('id');
		echo $this->Form->input('name');
		echo $this->Form->input('block', array('options' => $blocks));
		if ($this->Session->read('Auth.User.is_admin')) :
			echo $this->Form->input('Widget.data.element');
			echo $this->JsonEditor->input('Widget.data.data_fields');
			echo $this->JsonEditor->input('Widget.data.options');
			echo $this->JsonEditor->input('Widget.data.view_vars');
			//echo $this->Form->input('Widget.data.data_fields', array('type' => 'textarea'));
			//echo $this->Form->input('Widget.data.options', array('type' => 'textarea'));
			//echo $this->Form->input('Widget.data.view_vars', array('type' => 'textarea'));
		endif;
		foreach ($data_fields as $field => $options) :
			$type = isset($options['type']) ? $options['type'] : 'text';
			unset($options['type']);
			$value = isset($this->data['Widget']['data']['data'][$field]) ? $this->request->data['Widget']['data']['data'][$field] : null;
			switch ($type) {
				case 'url':
					if (is_array($value)) {
						$this->request->data['Widget']['data']['data'][$field] = $this->Html->url(array_merge($value, array('base' => false)));
					}
					echo $this->Form->input('Widget.data.data.' . $field, $options);
					break;
				
				case 'image':
					echo $this->element('PieceOCake.image_upload', array('field' => 'Widget.data.data.' . $field, 'options' => $options));
					break;
				
				case 'file':
					echo $this->element('PieceOCake.file_upload', array('field' => 'Widget.data.data.' . $field, 'options' => $options));
					break;
				
				case 'wysiwyg':
					echo $this->Editor->input('Widget.data.data.' . $field, $options);
					break;
				
				default:
					echo $this->Form->input('Widget.data.data.' . $field, $options);
			}
		endforeach;
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
			<li><?php echo $this->Form->postLink(__('Delete'), array('action' => 'delete', $this->Form->value('Widget.id')), null, __('Are you sure you want to delete # %s?', $this->Form->value('Widget.id'))); ?></li>
		<?php endif; ?>
		<li><?php echo $this->Html->link(__('List Widgets'), array('action' => 'index')); ?></li>
	</ul>
</div>
