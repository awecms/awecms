<?php

App::uses('Controller', 'Controller');
App::uses('HandlebarsView', 'Awecms.View');

class HandlebarsHelper extends AppHelper {
	
	public $helpers = array('Html');
	protected $_handlebarsView = null;
	
	public function template($id, $element, $options = array()) {
		$handlebarsView = $this->_getHandlebarsView();
		$template = $handlebarsView->template($element);
		if (empty($template)) {
			$template = '';
		}
		$out = $this->Html->tag('script', $template, array('id' => $id, 'type' => 'text/x-handlebars-template'));
		if (isset($options['inline']) && $options['inline'] === false) {
			$this->_View->append('script', $out);
			return null;
		}
		return $out;
	}
	
	protected function _getHandlebarsView() {
		if (empty($this->_handlebarsView)) {
			$controller = new Controller($this->_View->request, $this->_View->response);
			$this->_handlebarsView = new HandlebarsView($controller);
		}
		return $this->_handlebarsView;
	}
}