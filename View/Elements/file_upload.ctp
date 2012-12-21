<?php
$path = Set::classicExtract($this->data, $field);
if (!isset($options)) {
	$options = array();
}
$options['type'] = 'file';
if ($path) {
	$options['after'] = $this->Html->link($path, '/files/upload/' . $path, array('target' => '_blank'));
}
echo $this->Form->input($field, $options);