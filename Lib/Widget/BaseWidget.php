<?php

class BaseWidget extends Object {

	public $id = null;
	protected $_widget = null;
	
	public function __construct($widget) {
		$this->id = $widget['id'];
		$this->_widget = $widget;
	}

	public function render($view) {
	}
	
	public function beforeRender($view) {
	}

}