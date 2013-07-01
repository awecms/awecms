<?php

App::uses('View', 'View');

class HandlebarsView extends View {
	
	public function template($name) {
		$file = $this->_getElementFilename($name);
		if ($file) {
			return file_get_contents($file);
		}
	}
	
	protected function _getExtensions() {
		return array('.handlebars', '.js');
	}
}