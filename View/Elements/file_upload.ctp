<?php
$path = Set::classicExtract($this->data, $field);
$options = array('type' => 'file');
if ($path) {
	$options['after'] = $this->Html->link($path, '/files/upload/' . $path, array('target' => '_blank'));
}
echo $this->Form->input($field, $options);