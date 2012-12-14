<?php

App::uses('BaseWidget', 'PieceOCake.Widget');
App::uses('ClassRegistry', 'Event');

class CommonWidget extends BaseWidget {

	protected $_error = null;
	protected $_proxiedWidget = null;
	protected $_Widget = null;

	public function __construct($widget) {
		parent::__construct($widget);
		$this->settings = json_decode($widget['content'], true);
		if (empty($this->settings['widget_id'])) {
			$this->_error = 'Error: You must specify an widget.';
		}
		
		$this->_Widget = ClassRegistry::getObject('Widget');
		$this->_proxiedWidget = $this->_Widget->getWidget($this->settings['widget_id']);
	}
	
	public function initialize($view) {
		parent::initialize($view);
		$this->_proxiedWidget->initialize($view);
	}

	public function getContent() {
		if ($this->_error) {
			return $this->_error;
		}
		return $this->_proxiedWidget->getContent();
	}
	
	public function beforeRenderCheck() {
		$result = parent::beforeRenderCheck();
		if ($result === false) {
			return false;
		}
		return $this->_proxiedWidget->beforeRenderCheck();
	}

}