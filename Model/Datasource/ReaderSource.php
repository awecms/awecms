<?php

App::uses('ArraySource', 'Datasources.Model/Datasource');
App::import('Vendor', 'Awecms.Uuid/class.uuid');

class ReaderSource extends ArraySource {

	protected $_schema = array(
		'id' => array(
			'key' => 'primary',
			'type' => 'string',
			'null' => false,
			'length' => 36,
		),
		'name' => array(
			'type' => 'string',
			'null' => false,
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
		$path = isset($config['path']) ? $config['path'] : null;
		if (!isset($config['reader'])) {
			$config['reader'] = 'php';
		}
		$readerClass = ucfirst($config['reader']) . 'Reader';
		App::uses($readerClass, 'Configure');
		$this->_reader = new $readerClass($path);
	}

	public function describe($model) {
		return $this->_schema;
    }
	
	public function create(Model $model, $fields = null, $values = null) {
		$data = array_combine($fields, $values);
		$data['id'] = $this->_getId($data['name']);
		
		$this->_readRecords($model);
		$model->records[] = $data;
		$model->setInsertID($data['id']);
		$model->id = $data['id'];
		
		return $this->_saveRecords($model);
	}

	public function read(Model $model, $queryData = array(), $recursive = null) {
		$this->_readRecords($model);
		$data = parent::read($model, $queryData, $recursive);
		
		// A hopefully temporary fix for issue #45 in array source
		if (isset($data[$model->alias]) && empty($data[$model->alias])) {
			$data = array();
		}
		
		return $data;
	}
	
	public function update(Model $model, $fields = null, $values = null, $conditions = null) {
		if ($conditions === null) {
			if ($model->exists()) {
				$conditions = array($model->alias . '.' . $model->primaryKey => $model->getID());
			} else {
				return false;
			}
		}
		
		$data = array_combine($fields, $values);
		$this->_readRecords($model);
		foreach ($model->records as $pos => $record) {
			// Tests whether the record will be chosen
			if (!empty($conditions)) {
				$conditions = (array)$conditions;
				if (!$this->conditionsFilter($model, $record, $conditions)) {
					continue;
				}
				
				$model->records[$pos] = array_merge($record, $data);
			}
		}
		
		return $this->_saveRecords($model);
    }
	
	public function delete(Model $model, $id = null) {
		$this->_readRecords($model);
		$id = $id[$model->alias . '.id'];
		$deleted = false;
		foreach ($model->records as $key => $record) {
			if ($record['id'] == $id) {
				unset($model->records[$key]);
				return $this->_saveRecords($model);
			}
		}
		
		return false;
	}
	
	// Load the records for the model from the config file
	protected function _readRecords(Model $model) {
		if (!isset($model->records)) {
			$data = Hash::flatten($this->_reader->read($model->useTable));
			$model->records = array();
			foreach ($data as $name => $value) {
				$model->records[] = array(
					'id' => $this->_getId($name),
					'name' => $name,
					'value' => $value
				);
			}
		}
	}
	
	protected function _saveRecords(Model $model) {
		$data = array();
		foreach ($model->records as $record) {
			$data = Hash::insert($data, $record['name'], $record['value']);
		}
		return $this->_reader->dump($model->useTable, $data);
	}
	
	protected function _getId($name) {
		return UUID::generate(UUID::UUID_NAME_SHA1, UUID::FMT_STRING, $name, 'b97e07a1-7e91-44de-adb0-42d8a4f5804f');
	}

}