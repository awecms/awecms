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
		
		echo $this->Html->css('Awecms.smoothness/jquery-ui-1.9.0.custom.min');
		echo $this->Html->css('Awecms.main');

		echo $this->fetch('meta');
		echo $this->fetch('css');
	?>
</head>
<body>
	<div id="wrap">
		<header id="header" class="navbar navbar-inverse navbar-fixed-top">
			<div class="navbar-inner">
				<div class="container-fluid">
					<?php echo $this->Html->link(__(Configure::read('Admin.design_company')), array('admin' => true, 'plugin' => 'awecms', 'controller' => 'pages', 'action' => 'display', 'home'), array('class' => 'brand')); ?>
					<a class="btn btn-navbar" data-toggle="collapse" data-target=".nav-collapse" href="#"><span class="icon-arrow-down"></span></a>
					<ul class="nav">
						<li class="divider-vertical"></li>
						<li><?php echo $this->Html->link(__(Configure::read('Admin.website_name')), '/'); ?></li>
					</ul>
					<ul class="nav pull-right">
						<li class="dropdown">
							<a class="dropdown-toggle" data-toggle="dropdown" href="#"><?php echo __('Hello %s', AuthComponent::user('username')); ?> <span class="caret"></span></a>
							<ul class="dropdown-menu">
								<li><?php echo $this->Html->link(__('Edit your profile'), '#'); ?></li>
								<li><?php echo $this->Html->link(__('Logout'), array('admin' => false, 'plugin' => 'awecms', 'controller' => 'users', 'action' => 'logout')); ?></li>
							</ul>
						</li>
					</ul>
					<div class="nav-collapse collapse">
						<ul class="nav">
							<li class="divider-vertical"></li>
							<li><?php echo $this->Html->link(__('Help'), '#'); ?></li>
						</ul>
					</div>
				</div>
			</div>
		</header>
		<div id="main" class="container-fluid">
			<div class="row-fluid">
				<div id="sidebar" class="span2">
					<?php echo $this->Menu->render('Admin.MainMenu', 'Awecms.admin-sidebar'); ?>
				</div>
				<div id="content" class="span10">
					<?php echo $this->Session->flash(); ?>
					<?php echo $this->fetch('content'); ?>
					<div id="ajax"></div>
				</div>
			</div>
		</div>
		<div id="push"></div>
	</div>
	<div id="footer">
		<?php
			echo $this->Html->link(
				$this->Html->image('Awecms.poc.power.gif', array('alt' => __('Piece O\' Cake: CMS'), 'border' => '0')),
				'http://github.com/thegallagher',
				array('target' => '_blank', 'escape' => false)
			);
			echo ' ';
			echo $this->Html->link(
				$this->Html->image('cake.power.gif', array('alt' => __('CakePHP: the rapid development php framework'), 'border' => '0')),
				'http://www.cakephp.org/',
				array('target' => '_blank', 'escape' => false)
			);
		?>
	</div>
	<?php
	echo $this->element('sql_dump');
	
	echo $this->Html->script('Awecms.jquery-1.8.2.min');
	echo $this->Html->script('Awecms.jquery-ui-1.9.0.custom.min');
	echo $this->Html->script('Awecms.bootstrap');
	?>
	<script type="text/javascript">
	// baseUrl is Deprecated. Use APP instead.
	var baseUrl = <?php echo json_encode($this->Html->url('/')); ?>;
	
	var APP = <?php echo json_encode($appConfig); ?>;
	</script>
	<?php
	echo $this->Html->script('Awecms.common');
	echo $this->fetch('script');
	?>
</body>
</html>
