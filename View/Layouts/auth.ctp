<!DOCTYPE html>
<html lang="en">
<head>
	<?php echo $this->Html->charset(); ?>
	<title>
		<?php echo __(Configure::read('Admin.website_name')); ?>:
		<?php echo $title_for_layout; ?>
	</title>
	<?php
		echo $this->Html->meta('icon');

		//echo $this->Html->css('Awecms.bootstrap.min');
		//echo $this->Html->css('Awecms.bootstrap-responsive.min');
		//echo $this->Html->css('Awecms.cake.generic');
		echo $this->Html->css('Awecms.main');

		echo $this->fetch('meta');
		echo $this->fetch('css');
		echo $this->fetch('script');
	?>
</head>
<body class="auth">
	<div id="wrap">
		<header id="header" class="navbar navbar-inverse navbar-fixed-top">
			<div class="navbar-inner">
				<div class="container-fluid">
					<?php echo $this->Html->link(__(Configure::read('Admin.design_company')), array('admin' => true, 'plugin' => 'awecms', 'controller' => 'pages', 'action' => 'display', 'home'), array('class' => 'brand')); ?>
				</div>
			</div>
		</header>
		<div id="main" class="container">
			<?php echo $this->fetch('content'); ?>
		</div>
	</div>
	<?php echo $this->element('sql_dump'); ?>
	<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.7.2/jquery.min.js"></script>
	<?php echo $this->Html->script('Awecms.bootstrap'); ?>
</html>
