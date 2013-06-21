<?php

App::uses('WidgetsAppController', 'Awecms.Controller');

class HtmlWidgetController extends WidgetsAppController {

	public function admin_edit($id = null) {
		$data = $this->_read($id);
		$defaults = array('content' => null, 'escape' => 0);
		if ($this->request->is('post') || $this->request->is('put')) {
			$this->request->data['Widget']['data'] = array_merge($defaults, $data['Widget']['data'], $this->data['Widget']['data']);
			$this->_save();
		} else {
			$data['Widget']['data'] = array_merge($defaults, $data['Widget']['data']);
			$this->request->data = $data;
		}
		
		$editor = Configure::read('Awecms.defaultEditor');
		$this->helpers['Editor'] = array('className' => $editor);
	}

}