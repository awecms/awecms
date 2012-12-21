<?php $this->extend('PieceOCake.edit'); ?>
<?php if ($this->Session->read('Auth.User.is_admin')) : ?>
	<?php echo $this->JsonEditor->input('Widget.data'); ?>
<?php endif; ?>