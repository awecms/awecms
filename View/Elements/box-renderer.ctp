<?php
$html = '';
foreach ($widgets as $widget) :
	$this->set('className', sprintf('box widget-%s id-%d', $widget->getClassName(), $widget->id));
	$widget->extend('Awecms.box');
	$html .= $widget->render();
endforeach;

if (!isset($options['wrapper']) || $options['wrapper'] !== false) :
	$html = $this->Html->div('box-wrapper ' . $options['class'], $html);
endif;
echo $html;