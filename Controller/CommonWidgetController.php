<?php

App::uses('WidgetsAppController', 'Awecms.Controller');

class CommonWidgetController extends WidgetsAppController {

	public function admin_edit($id = null) {
		$data = $this->_read($id);
		$defaults = array('widget_id' => null);
		if ($this->request->is('post') || $this->request->is('put')) {
			$this->request->data['Widget']['data'] = array_merge($defaults, $data['Widget']['data'], $this->data['Widget']['data']);
			$this->_save();
		} else {
			$data['Widget']['data'] = array_merge($defaults, $data['Widget']['data']);
			$this->request->data = $data;
		}
		
		$widgets = $this->Widget->find('list', array('conditions' => array('Widget.block' => 'common')));
		$this->set('widgets', $widgets);
	}

}