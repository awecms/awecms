<?php

class ReaderSource extends DataSource {
	
	protected $_schema = array(
		'name' => array(
			'type' => 'string',
			'null' => false,
			'key' => 'primary',
			'length' => 255,
		),
		'value' => array(
			'type' => 'text',
			'null' => true,
		)
	);
	
	protected $_reader = null;
	
	public function __construct($config) {
		parent::__construct($config);
		$readerClass = $config['reader'] . 'Reader';
		$path = isset($config['path']) ? $config['path'] : null;
		App::uses($readerClass, 'Configure');
		$this->_reader = new {$readerClass}($path);
	}

	public function describe(Model $Model) {
		return $this->_schema;
    }
	
	public function listSources() {
		return null;
    }
	
	public function calculate(Model $Model, $func, $params = array()) {
        return 'COUNT';
    }
	
	public function read(Model $Model, $data = array()) {
		$this->read($Model->useTable, $data['conditions']);
		if ($data['fields'] == 'COUNT') {
			return array(array(array('count' => count($readerData))));
		}
		debug($readerData);
		return array($Model->alias => $readerData);
	}
	
	protected function _read($file, $conditions) {
		$data = $this->_reader->read($file);
		
	}
}