<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<?php echo $this->Html->charset(); ?>
	<title>
		<?php echo __(Configure::read('Admin.website_name')); ?>:
		<?php echo $title_for_layout; ?>
	</title>
	<?php
		echo $this->Html->meta('icon');

		echo $this->Html->css('//netdna.bootstrapcdn.com/twitter-bootstrap/2.1.1/css/bootstrap.min.css');
		echo $this->Html->css('PieceOCake.cake.generic');
		echo $this->Html->css('PieceOCake.smoothness/jquery-ui-1.9.0.custom.min');
		echo $this->Html->css('PieceOCake.style');

		echo $this->fetch('meta');
		echo $this->fetch('css');
	?>
</head>
<body>
	<div id="container">
		<div id="header">
			<h1>
				<?php echo $this->Html->link(__(Configure::read('Admin.design_company')), array('controller' => 'pages', 'action' => 'display', 'home')); ?>
			</h1>
			<?php echo $this->Menu->render('Admin.MainMenu'); ?>
			<dl>
				<dt><?php echo __('Website'); ?>:</dt>
				<dd><?php echo $this->Html->link(__(Configure::read('Admin.website_name')), '/'); ?></dd>
				<dt><?php echo __('Logged in as') ?>:</dt>
				<dd><?php echo AuthComponent::user('username'); ?></dd>
				<dt><?php echo $this->Html->link(__('Logout'), array('admin' => false, 'plugin' => 'users', 'controller' => 'users', 'action' => 'logout')) ?></dt>
				<dd></dd>
			</dl>
		</div>
		<div id="content">
			
			<?php echo $this->Session->flash(); ?>
			
			<?php echo $this->fetch('content'); ?>
			
			<div id="ajax"></div>
		</div>
		<div id="footer">
			<?php echo $this->Html->link(
					$this->Html->image('cake.power.gif', array('alt' => __('CakePHP: the rapid development php framework'), 'border' => '0')),
					'http://www.cakephp.org/',
					array('target' => '_blank', 'escape' => false)
				);
			?>
		</div>
	</div>
	<?php
	echo $this->element('sql_dump');
	
	echo $this->Html->script('PieceOCake.jquery-1.8.2.min');
	echo $this->Html->script('PieceOCake.jquery-ui-1.9.0.custom.min');
	?>
	<script type="text/javascript">
	var baseUrl = <?php echo json_encode($this->Html->url('/')); ?>;
	</script>
	<?php
	echo $this->Html->script('PieceOCake.common');
	echo $this->fetch('script');
	?>
</body>
</html>
