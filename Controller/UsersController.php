<?php
App::uses('PieceOCakeAppController', 'PieceOCake.Controller');
/**
 * Users Controller
 *
 * @property User $User
 */
class UsersController extends PieceOCakeAppController {
	
	protected $_saltChanged = false;
	
	public function beforeFilter() {
		parent::beforeFilter();
		if ($this->_debug && !$this->Auth->loggedIn()) {
			$this->_saltChanged = Configure::read('System.salt') != Configure::read('Security.salt');
			$usersCount = $this->User->find('count');
			if ($this->_saltChanged || $usersCount < 1) {
				if ($this->request->params['action'] == 'add') {
					$this->layout = 'auth';
					$this->Auth->allow('add');
					if ($this->_saltChanged && $usersCount > 0) {
						$this->User->deleteAll(array('1' => 1), false);
					}
				} else {
					$this->Session->setFlash(__('Please create a user.'));
					$this->redirect(array('action' => 'add'));
				}
			}
		}
	}


/**
 * index method
 *
 * @return void
 */
	public function admin_index() {
		$this->User->recursive = 0;
		$this->set('users', $this->paginate());
	}

/**
 * view method
 *
 * @param string $id
 * @return void
 */
	public function admin_view($id = null) {
		$this->User->id = $id;
		if (!$this->User->exists()) {
			throw new NotFoundException(__('Invalid user'));
		}
		$this->set('user', $this->User->read(null, $id));
	}

/**
 * add method
 *
 * @return void
 */
	public function admin_add() {
		if ($this->request->is('post')) {
			$this->User->create();
			if ($this->User->save($this->request->data)) {
				if ($this->_saltChanged) {
					$this->loadModel('Config');
					$id = $this->Config->field('id', array('namespace' => 'System', 'name' => 'salt'));
					$this->Config->read(null, $id);
					$this->Config->set('value', Configure::read('Security.salt'));
					$this->Config->save();
				}
				$this->Session->setFlash(__('The user has been saved'));
				$this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('The user could not be saved. Please, try again.'));
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
		$this->User->id = $id;
		if (!$this->User->exists()) {
			throw new NotFoundException(__('Invalid user'));
		}
		if ($this->request->is('post') || $this->request->is('put')) {
			if ($this->User->save($this->request->data)) {
				$this->Session->setFlash(__('The user has been saved'));
				$this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('The user could not be saved. Please, try again.'));
			}
		} else {
			$this->request->data = $this->User->read(null, $id);
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
		$this->User->id = $id;
		if (!$this->User->exists()) {
			throw new NotFoundException(__('Invalid user'));
		}
		if ($this->User->delete()) {
			$this->Session->setFlash(__('User deleted'));
			$this->redirect(array('action' => 'index'));
		}
		$this->Session->setFlash(__('User was not deleted'));
		$this->redirect(array('action' => 'index'));
	}
	
	public function admin_login() {
		$this->layout = 'auth';
		if ($this->request->is('post')) {
			if ($this->Auth->login()) {
				return $this->redirect($this->Auth->redirect());
			} else {
				unset($this->request->data['User']['password']);
				$this->Session->setFlash(__('Username or password is incorrect'), 'default', array(), 'auth');
			}
		}
	}
	
	public function admin_logout() {
		$this->redirect($this->Auth->logout());
	}
}
