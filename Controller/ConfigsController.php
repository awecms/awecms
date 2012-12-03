<?php
App::uses('PieceOCakeAppController', 'PieceOCake.Controller');
/**
 * Configs Controller
 *
 * @property Config $Config
 */
class ConfigsController extends PieceOCakeAppController {

/**
 * admin_index method
 *
 * @return void
 */
	public function admin_index() {
		$this->Config->recursive = 0;
		$this->set('configs', $this->paginate());
	}

/**
 * admin_view method
 *
 * @throws NotFoundException
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
 * admin_add method
 *
 * @return void
 */
	public function admin_add() {
		if ($this->request->is('post')) {
			$this->Config->create();
			if ($this->Config->save($this->request->data)) {
				$this->Session->setFlash(__('The config has been saved'));
				$this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('The config could not be saved. Please, try again.'));
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
		$this->Config->id = $id;
		if (!$this->Config->exists()) {
			throw new NotFoundException(__('Invalid config'));
		}
		if ($this->request->is('post') || $this->request->is('put')) {
			if ($this->Config->save($this->request->data)) {
				$this->Session->setFlash(__('The config has been saved'));
				$this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('The config could not be saved. Please, try again.'));
			}
		} else {
			$this->request->data = $this->Config->read(null, $id);
		}
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
