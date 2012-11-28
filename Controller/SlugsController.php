<?php
App::uses('PieceOCakeAppController', 'PieceOCake.Controller');
App::import('Vendor', 'PieceOCake.spyc/spyc');
/**
 * Slugs Controller
 *
 * @property Slug $Slug
 */
class SlugsController extends PieceOCakeAppController {


/**
 * index method
 *
 * @return void
 */
	public function index() {
		$this->Slug->recursive = 0;
		$this->set('slugs', $this->paginate());
	}

/**
 * view method
 *
 * @param string $id
 * @return void
 */
	public function view($id = null) {
		$this->Slug->id = $id;
		if (!$this->Slug->exists()) {
			throw new NotFoundException(__('Invalid slug'));
		}
		$this->set('slug', $this->Slug->read(null, $id));
	}

/**
 * add method
 *
 * @return void
 */
	public function add() {
		if ($this->request->is('post')) {
			$this->Slug->create();
			$this->request->data['Slug']['defaults'] = Spyc::YAMLLoadString($this->request->data['Slug']['defaults']);
			$this->request->data['Slug']['options'] = Spyc::YAMLLoadString($this->request->data['Slug']['options']);
			if ($this->Slug->save($this->request->data)) {
				$this->Session->setFlash(__('The slug has been saved'));
				$this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('The slug could not be saved. Please, try again.'));
			}
		}
	}

/**
 * edit method
 *
 * @param string $id
 * @return void
 */
	public function edit($id = null) {
		$this->Slug->id = $id;
		if (!$this->Slug->exists()) {
			throw new NotFoundException(__('Invalid slug'));
		}
		if ($this->request->is('post') || $this->request->is('put')) {
			$this->request->data['Slug']['defaults'] = Spyc::YAMLLoadString($this->request->data['Slug']['defaults']);
			$this->request->data['Slug']['options'] = Spyc::YAMLLoadString($this->request->data['Slug']['options']);
			if ($this->Slug->save($this->request->data)) {
				$this->Session->setFlash(__('The slug has been saved'));
				$this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('The slug could not be saved. Please, try again.'));
			}
		} else {
			$this->request->data = $this->Slug->read(null, $id);
			$this->request->data['Slug']['defaults'] = Spyc::YAMLDump($this->request->data['Slug']['defaults'], 2, 0);
			$this->request->data['Slug']['options'] = Spyc::YAMLDump($this->request->data['Slug']['options'], 2, 0);
		}
	}

/**
 * delete method
 *
 * @param string $id
 * @return void
 */
	public function delete($id = null) {
		if (!$this->request->is('post')) {
			throw new MethodNotAllowedException();
		}
		$this->Slug->id = $id;
		if (!$this->Slug->exists()) {
			throw new NotFoundException(__('Invalid slug'));
		}
		if ($this->Slug->delete()) {
			$this->Session->setFlash(__('Slug deleted'));
			$this->redirect(array('action' => 'index'));
		}
		$this->Session->setFlash(__('Slug was not deleted'));
		$this->redirect(array('action' => 'index'));
	}
}
