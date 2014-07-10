<?php
class ResultMapBehavior extends ModelBehavior {

	protected $_Model = null;
	
	public function setup(Model $model, $settings = array()) {
		if (!isset($this->settings[$model->alias])) {
			$this->settings[$model->alias] = array('afterFind' => array());
		}
		$this->settings[$model->alias] = array_merge($this->settings[$model->alias], $settings);
	}
	
	public function afterFind(Model $model, $results, $primary = false) {
		return $this->map($model, $this->settings[$model->alias]['afterFind'], $results);
	}
	
	protected function map(&$model, $callable, $results) {
		if (empty($results)) {
			$this->_filter($model, $callable, $results);
		} else if (isset($results[$model->alias])) {
			if ($results[$model->alias][0]) {
				$this->_filter($model, $callable, $results[$model->alias]);
			} else {
				$data = array(&$results[$model->alias]);
				$this->_filter($model, $callable, $data);
			}
		} else if (isset($results[0][$model->alias])) {
			foreach ($results as &$result) {
				if (isset($result[$model->alias][0])) {
					$this->_filter($model, $callable, $result[$model->alias]);
				} else {
					$data = array(&$result[$model->alias]);
					$this->_filter($model, $callable, $data);
				}
			}
		} else if (isset($results[0])) {
			$this->_filter($model, $callable, $results);
		} else {
			$data = array(&$results);
			$this->_filter($model, $callable, $data);
		}
		return $results;
	}
	
	protected function _filter(&$model, $callable, &$results) {
		foreach ((array) $callable as $method) {
			$returnValue = $model->{$method}($results);
			if ($returnValue === false) {
				$results = false;
				return;
			}
		}
	}
}