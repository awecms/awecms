<?php

App::uses('BaseWidget', 'PieceOCake.Widget');

class ElementWidget extends BaseWidget {

	protected $_error = null;

	public function __construct($widget) {
		parent::__construct($widget);
		$this->settings = json_decode($widget['content'], true);
		if (empty($this->settings['element'])) {
			$this->_error = 'Error: You must specify an element.';
			//throw new CakeException('You must specify an element.');
		}
		if (empty($this->settings['data'])) {
			$this->settings['data'] = array();
		}
		$this->data = $this->settings['data'];
		if (empty($this->settings['options'])) {
			$this->settings['options'] = array();
		}
		if (empty($this->settings['view_vars'])) {
			$this->settings['view_vars'] = array();
		}
		$this->settings['view_vars'] = array_fill_keys($this->settings['view_vars'], null);
	}

	public function getContent() {
		if ($this->_error) {
			return $this->_error;
		}
		$data = array_merge($this->settings['data'], array_intersect_key($this->_View->viewVars, $this->settings['view_vars']));
		return $this->_View->element($this->settings['element'], $data, $this->settings['options']);
	}

}