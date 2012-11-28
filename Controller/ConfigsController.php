<?php
App::uses('PieceOCakeAppController', 'PieceOCake.Controller');
App::import('Vendor', 'PieceOCake.spyc/spyc');
/**
 * Configs Controller
 *
 * @property Config $Config
 */
class ConfigsController extends PieceOCakeAppController {


/**
 * index method
 *
 * @return void
 */
	public function admin_index() {
		$this->Config->recursive = 0;
		if (Configure::read('debug') < 1) {
			$this->paginate = array(
				'conditions' => array('is_locked' => 0),
			);
		}
		$this->set('configs', $this->paginate());
	}

/**
 * view method
 *
 * @param string $id
 * @return void
 */
	public function admin_view($id = null) {
		$this->Config->id = $id;
		if (!$this->Config->exists()) {
			throw new NotFoundException(__('Invalid config'));
		}
		$this->set('config', $this->Config->read(null, $id));
	}

/**
 * add method
 *
 * @return void
 */
	public function admin_add() {
		if ($this->request->is('post')) {
			$this->Config->create();
			if ($this->request->data['Config']['type'] == 'array') {
				$this->request->data['Config']['value'] = Spyc::YAMLLoadString($this->request->data['Config']['value']);
			}
			if ($this->Config->save($this->request->data)) {
				$this->Session->setFlash(__('The config has been saved'));
				$this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('The config could not be saved. Please, try again.'));
			}
		}
	}

/**
 * edit method
 *
 * @param string $id
 * @return void
 */
	public function admin_edit($id = null) {
		$this->Config->id = $id;
		if (!$this->Config->exists()) {
			throw new NotFoundException(__('Invalid config'));
		}
		if ($this->request->is('post') || $this->request->is('put')) {
			if ($this->request->data['Config']['type'] == 'array') {
				$this->request->data['Config']['value'] = Spyc::YAMLLoadString($this->request->data['Config']['value']);
			}
			if ($this->Config->save($this->request->data)) {
				$this->Session->setFlash(__('The config has been saved'));
				$this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('The config could not be saved. Please, try again.'));
			}
		} else {
			$this->request->data = $this->Config->read(null, $id);
			if ($this->request->data['Config']['type'] == 'array') {
				$this->request->data['Config']['value'] = Spyc::YAMLDump($this->request->data['Config']['value'], 2, 0);
			}
		}
	}

/**
 * delete method
 *
 * @param string $id
 * @return void
 */
	public function admin_delete($id = null) {
		if (!$this->request->is('post')) {
			throw new MethodNotAllowedException();
		}
		$this->Config->id = $id;
		if (!$this->Config->exists()) {
			throw new NotFoundException(__('Invalid config'));
		}
		if ($this->Config->delete()) {
			$this->Session->setFlash(__('Config deleted'));
			$this->redirect(array('action' => 'index'));
		}
		$this->Session->setFlash(__('Config was not deleted'));
		$this->redirect(array('action' => 'index'));
	}
}
