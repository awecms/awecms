<?php

App::uses('BaseWidget', 'Awecms.Widget');
App::uses('ClassRegistry', 'Event');

class CommonWidget extends BaseWidget {

	protected $_error = null;
	protected $_proxiedWidget = null;
	protected $_Widget = null;

	public function __construct($widget) {
		parent::__construct($widget);
		$this->settings = $widget['data'];
		if (empty($this->settings['widget_id'])) {
			$this->_error = 'Error: You must specify an widget.';
			return;
		}
		
		$this->_Widget = ClassRegistry::getObject('Widget');
		$this->_proxiedWidget = $this->_Widget->getWidget($this->settings['widget_id']);
	}
	
	public function initialize($view) {
		parent::initialize($view);
		if ($this->_error) {
			return;
		}
		$this->_proxiedWidget->initialize($view);
	}

	public function getContent() {
		if ($this->_error) {
			return $this->_error;
		}
		return $this->_proxiedWidget->getContent();
	}
	
	public function beforeRenderCheck() {
		if ($this->_error) {
			return false;
		}
		$result = parent::beforeRenderCheck();
		if ($result === false) {
			return false;
		}
		return $this->_proxiedWidget->beforeRenderCheck();
	}

}