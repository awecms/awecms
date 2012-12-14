<section class="box">
	<?php echo $this->fetch('before'); ?>
	<h1><?php echo h($this->fetch('title')); ?></h1>
	<?php echo $this->fetch('content'); ?>
	<?php echo $this->fetch('after'); ?>
</section>