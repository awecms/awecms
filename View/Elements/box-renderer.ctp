<?php
$html = '';
foreach ($widgets as $widget) :
	$widget->extend('PieceOCake.box');
	$html .= $widget->render();
endforeach;

if (!isset($options['wrapper']) || $options['wrapper'] !== false) :
	$html = $this->Html->div('box-wrapper ' . $options['class'], $html);
endif;
echo $html;