<?php

App::uses('AppModel', 'Model');

class DboReader implements ConfigReaderInterface {
	
	protected $_models = array();
	
	public function __construct($model = null) {
    }
	
	public function read($key) {
		if (!isset($this->_models[$key])) {
			App::uses($key, 'Awecms.Model');
			$this->_models[$key] = new $key();
		}
		$model = $this->_models[$key];
		
		$data = $model->find('all');
		$config = array();
		foreach ($data as $value) {
			$config[$value[$model->alias]['namespace']][$value[$model->alias]['name']] = $value[$model->alias]['value'];
		}
		
		return $config;
	}
}