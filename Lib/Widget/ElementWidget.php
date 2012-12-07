<?php

App::uses('BaseWidget', 'PieceOCake.Widget');

class ElementWidget extends BaseWidget {

	public function __construct($widget) {
		parent::__construct($widget);
		$this->settings = json_decode($widget['content']);
		if (empty($this->settings['element'])) {
			throw new CakeException('You must specify an element.');
		}
		if (empty($this->settings['data'])) {
			$this->settings['data'] = array();
		}
		if (empty($this->settings['options'])) {
			$this->settings['options'] = array();
		}
		if (empty($this->settings['view_vars'])) {
			$this->settings['view_vars'] = array();
		}
		$this->settings['view_vars'] = array_fill_keys($this->settings['view_vars'], null);
	}

	public function render($view) {
		$data = array_merge($this->settings['data'], array_intersect_key($view->viewVars, $this->settings['view_vars']));
		return $view->element($this->settings['element'], $data, $this->settings['options']);
	}

}