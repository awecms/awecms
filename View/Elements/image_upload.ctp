<?php
$path = Set::classicExtract($this->data, $field);
$options = array('type' => 'file');
if ($path) {
	$image = $this->Html->image('upload/' . $path);
	$options['after'] = $this->Html->link($image, '/img/upload/' . $path, array('target' => '_blank', 'escape' => false));
}
echo $this->Form->input($field, $options);