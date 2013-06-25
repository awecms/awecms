<?php
class ResultMapBehavior extends ModelBehavior {

	protected $_Model = null;
	
	public function setup(Model $Model, $settings = array()) {
		if (!isset($this->settings[$Model->alias])) {
			$this->settings[$Model->alias] = array('afterFind' => array());
		}
		$this->settings[$Model->alias] = array_merge($this->settings[$Model->alias], $settings);
	}
	
	public function afterFind(Model $Model, $results, $primary) {
		return $this->map($Model, $this->settings[$Model->alias]['afterFind'], $results);
	}
	
	protected function map(&$Model, $callable, $results) {
		if (empty($results)) {
			$this->_filter($Model, $callable, $results);
		} else if (isset($results[$Model->alias])) {
			if ($results[$Model->alias][0]) {
				$this->_filter($Model, $callable, $results[$Model->alias]);
			} else {
				$data = array(&$results[$Model->alias]);
				$this->_filter($Model, $callable, $data);
			}
		} else if (isset($results[0][$Model->alias])) {
			foreach ($results as &$result) {
				if (isset($result[$Model->alias][0])) {
					$this->_filter($Model, $callable, $result[$Model->alias]);
				} else {
					$data = array(&$result[$Model->alias]);
					$this->_filter($Model, $callable, $data);
				}
			}
		} else if (isset($results[0])) {
			$this->_filter($Model, $callable, $results);
		} else {
			$data = array(&$results);
			$this->_filter($Model, $callable, $data);
		}
		return $results;
	}
	
	protected function _filter(&$Model, $callable, &$results) {
		foreach ((array) $callable as $method) {
			$returnValue = $Model->{$method}($results);
			if ($returnValue === false) {
				$results = false;
				return;
			}
		}
	}
}