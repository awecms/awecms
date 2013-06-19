<?php

App::uses('View', 'View');

class SystemView extends View {
	
	public function __construct(Controller $controller = null) {
		parent::__construct($controller);
	}

	protected function _paths($plugin = null, $cached = true) {
		//$paths = array();
		$paths = parent::_paths($plugin, $cached);
		$themePaths = array();
debug($plugin);debug($paths);die;
		foreach ($paths as $path) {
			if (strpos($path, DS . 'Plugin' . DS) === false
				&& strpos($path, DS . 'Cake' . DS . 'View') === false) {
					if ($plugin) {
						$themePaths[] = $path . 'Themed'. DS . $this->theme . DS . 'Plugin' . DS . $plugin . DS;
					}
					$themePaths[] = $path . 'Themed'. DS . $this->theme . DS;
				}
		}
		$paths = array_merge($themePaths, $paths);
		
		return $paths;
	}
}