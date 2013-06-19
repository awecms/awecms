<?php

App::uses('WidgetsAppController', 'Awecms.Controller');

class ElementWidgetController extends WidgetsAppController {

	// This method needs some serious moving to the model
	public function admin_edit($id = null) {
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
			
			//App::import('Vendor', 'Uploader.Uploader');
			//$uploader = new Uploader();
			
			foreach ($this->data['Widget']['data']['data_fields'] as $field => $options) {
				$type = empty($options['type']) ? 'text' : $options['type'];
				if (!array_key_exists($field, $this->data['Widget']['data']['data'])) {
					$this->request->data['Widget']['data']['data'][$field] = isset($data['Widget']['data']['data'][$field]) ? $data['Widget']['data']['data'][$field] : null;
				}/* else if ($type == 'image' || $type == 'file') {
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
				}*/
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
		//$this->helpers[] = 'JsonEditor.JsonEditor';
		$this->set('data_fields', $data['Widget']['data']['data_fields']);
	}

}