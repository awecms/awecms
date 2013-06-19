<?php

App::uses('AwecmsAppController', 'Awecms.Controller');

class WidgetsAppController extends AwecmsAppController {

	public $uses = array('Awecms.Widget');

	public function beforeRender() {
		$classes = $this->Widget->getWidgetClassList();
		$blocks = $this->Widget->getBlockList();
		$this->set(compact('classes', 'blocks'));
	}
	
	protected function _save() {
		if ($this->Widget->save($this->request->data)) {
			$this->Session->setFlash(__('The widget has been saved'));
			$this->redirect(array('plugin' => 'awecms', 'controller' => 'widgets', 'action' => 'index'));
		} else {
			$this->Session->setFlash(__('The widget could not be saved. Please, try again.'));
		}
	}
	
	protected function _read($id) {
		$this->Widget->id = $id;
		if (!$this->Widget->exists()) {
			throw new NotFoundException(__('Invalid widget'));
		}
		return $this->Widget->read(null, $id);
	}

}