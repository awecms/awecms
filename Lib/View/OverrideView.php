<?php

App::uses('View', 'View');

class OverrideView extends View {
	
	public function __construct(Controller $controller = null) {
		$this->_passedVars[] = 'overrideViews';
		parent::__construct($controller);
	}

	protected function _getViewFileName($name = null) {
		$exts = $this->_getExtensions();
		foreach ($exts as $ext) {
			foreach ($this->overrideViews as $view) {
				$view = str_replace('/', DS, $view);
				list($plugin, $view) = $this->pluginSplit($view);
				$paths = $this->_paths($plugin);
				foreach ($paths as $path) {
					if (file_exists($path . $this->viewPath . DS . $view . $ext)) {
						return $path . $this->viewPath . DS . $view . $ext;
					}
				}
			}
		}
		
		return parent::_getViewFileName($name);
	}
}