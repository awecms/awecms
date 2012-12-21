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

	public function admin_edit_html($id = null) {
		$data = $this->_read($id);
		$defaults = array('content' => null, 'escape' => 0);
		if ($this->request->is('post') || $this->request->is('put')) {
			$this->request->data['Widget']['data'] = array_merge($defaults, $data['Widget']['data'], $this->data['Widget']['data']);
			$this->_save();
		} else {
			$data['Widget']['data'] = array_merge($defaults, $data['Widget']['data']);
			$this->request->data = $data;
		}
		
		$editor = Configure::read('Admin.editor');
		$this->helpers['Editor'] = array('className' => $editor);
	}

	public function admin_edit_common($id = null) {
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
	
	// This method needs some serious moving to the model
	public function admin_edit_element($id = null) {
		$data = $this->_read($id);
		$defaults = array('element' => '', 'data' => array(), 'options' => array(), 'view_vars' => array(), 'data_fields' => array());
		if ($this->request->is('post') || $this->request->is('put')) {
			if (isset($this->request->data['Widget']['data']['options'])) {
				$this->request->data['Widget']['data']['options'] = json_decode($this->data['Widget']['data']['options'], true);
			}
			if (isset($this->request->data['Widget']['data']['view_vars'])) {
				$this->request->data['Widget']['data']['view_vars'] = json_decode($this->data['Widget']['data']['view_vars'], true);
			}
			if (isset($this->request->data['Widget']['data']['data_fields'])) {
				$this->request->data['Widget']['data']['data_fields'] = json_decode($this->data['Widget']['data']['data_fields'], true);
			}
			$this->request->data['Widget']['data'] = array_merge($defaults, $data['Widget']['data'], $this->data['Widget']['data']);
			
			App::import('Vendor', 'Uploader.Uploader');
			$uploader = new Uploader();
			
			foreach ($this->data['Widget']['data']['data_fields'] as $field => $options) {
				$type = empty($options['type']) ? 'text' : $options['type'];
				if (!array_key_exists($field, $this->data['Widget']['data']['data'])) {
					$this->request->data['Widget']['data']['data'][$field] = isset($data['Widget']['data']['data'][$field]) ? $data['Widget']['data']['data'][$field] : null;
				} else if ($type == 'image' || $type == 'file') {
					// Upload images
					if ($type == 'image') {
						$uploader->uploadDir = 'img/upload/';
					} else {
						$uploader->uploadDir = 'file/upload/';
					}
					$file = $uploader->upload($this->data['Widget']['data']['data'][$field]);
					if ($file) {
						$this->request->data['Widget']['data']['data'][$field] = $file['name'];
					} else {
						$this->request->data['Widget']['data']['data'][$field] = isset($data['Widget']['data']['data'][$field]) ? $data['Widget']['data']['data'][$field] : null;
					}
				}
			}
			
			$this->_save();
		} else {
			$data['Widget']['data'] = array_merge($defaults, $data['Widget']['data']);
			//$data['Widget']['data']['options'] = json_encode($data['Widget']['data']['options']);
			//$data['Widget']['data']['view_vars'] = json_encode($data['Widget']['data']['view_vars']);
			//$data['Widget']['data']['data_fields'] = json_encode($data['Widget']['data']['data_fields']);
			$this->request->data = $data;
		}
		
		$editor = Configure::read('Admin.editor');
		$this->helpers['Editor'] = array('className' => $editor);
		$this->helpers[] = 'JsonEditor.JsonEditor';
		$this->set('data_fields', $data['Widget']['data']['data_fields']);
	}

}
