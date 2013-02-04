<?php
class ResultMapBehavior extends ModelBehavior {

	protected $_Model = null;
	
	public function setup(Model &$Model, $settings = array()) {
		$this->settings = Hash::normalize($settings);
		$this->settings['afterFind'] = isset($this->settings['afterFind']) ? (array) $this->settings['afterFind'] : array();
		$this->_Model = &$Model;
	}
	
	public function afterFind(Model &$Model, $results, $primary) {
		if (empty($results)) {
			$this->_filter($results);
		} else if (isset($results[$Model->alias])) {
			if ($results[$Model->alias][0]) {
				$this->_filter($results[$Model->alias]);
			} else {
				$data = array(&$results[$Model->alias]);
				$this->_filter($data);
			}
		} else if (isset($results[0][$Model->alias])) {
			foreach ($results as &$result) {
				if (isset($result[$Model->alias][0])) {
					$this->_filter($result[$Model->alias]);
				} else {
					$data = array(&$result[$Model->alias]);
					$this->_filter($data);
				}
			}
		} else if (isset($results[0])) {
			$this->_filter($results);
		} else {
			$data = array(&$results);
			$this->_filter($data);
		}
		return $results;
	}
	
	protected function _filter(&$results) {
		foreach ($this->settings['afterFind'] as $method) {
			$returnValue = $this->_Model->{$method}($results);
			if ($returnValue === false) {
				$results = false;
				return;
			}
		}
	}
	
	/*public function resultMap(&$model, $callback, $results) {
		if (!empty($results)) {
			$args = array();
			if (func_num_args() > 3) {
				$args = func_get_args();
				unset($args[0]);
				unset($args[1]);
				$args = array_values($args);
			}
			
			$firstKey = key($results);
			$numericIndex = is_int($firstKey) || ctype_digit($firstKey);
			if ($numericIndex) {
				$firstElement = reset($results);
				if (isset($firstElement[$model->alias])) {
					foreach ($results as $key => $result) {
						$args[0] = $result;
						$results[$key] = call_user_func_array($callback, $args);
						if ($results[$key] === false) {
							return false;
						}
					}
				}
			} else {
				if (isset($results[$model->alias])) {
					$results = call_user_func_array($callback, $args);
					if ($results === false) {
						return false;
					}
				} else {
					$args[0] = array($model->alias => $results);
					$result = call_user_func_array($callback, $args);
					if ($result === false) {
						return false;
					}
					$results = $result[$model->alias];
				}
			}
		}
		return $results;
	}*/
}