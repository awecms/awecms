<?php

App::uses('WidgetsAppController', 'PieceOCake.Controller');

/**
 * Widgets Controller
 *
 * @property Widget $Widget
 */
class WidgetsController extends WidgetsAppController {

/**
 * admin_index method
 *
 * @return void
 */
	public function admin_index() {
		$this->Widget->recursive = 0;
		$this->set('widgets', $this->paginate());
		$this->set('editUrls', $this->Widget->getEditUrls());
	}

/**
 * admin_add method
 *
 * @return void
 */
	public function admin_add() {
		if ($this->request->is('post')) {
			$this->Widget->create();
			if ($this->Widget->save($this->request->data)) {
				$this->Session->setFlash(__('The widget has been saved'));
				$this->redirect(array('plugin' => 'piece_o_cake', 'controller' => 'widgets', 'action' => 'index'));
			} else {
				$this->Session->setFlash(__('The widget could not be saved. Please, try again.'));
			}
		}
	}

/**
 * admin_edit method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function admin_edit($id = null) {
		$data = $this->_read($id);
		if ($this->request->is('post') || $this->request->is('put')) {
			if (isset($this->request->data['Widget']['data'])) {
				$this->request->data['Widget']['data'] = json_decode($this->data['Widget']['data'], true);
			}
			$this->_save();
		} else {
			$this->request->data = $data;
		}
		$this->helpers[] = 'JsonEditor.JsonEditor';
	}

/**
 * admin_delete method
 *
 * @throws MethodNotAllowedException
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function admin_delete($id = null) {
		if (!$this->request->is('post')) {
			throw new MethodNotAllowedException();
		}
		$this->Widget->id = $id;
		if (!$this->Widget->exists()) {
			throw new NotFoundException(__('Invalid widget'));
		}
		if ($this->Widget->delete()) {
			$this->Session->setFlash(__('Widget deleted'));
			$this->redirect(array('plugin' => 'piece_o_cake', 'controller' => 'widgets', 'action' => 'index'));
		}
		$this->Session->setFlash(__('Widget was not deleted'));
		$this->redirect(array('action' => 'index'));
	}

}
