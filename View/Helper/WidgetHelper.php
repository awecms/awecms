<?php

App::uses('Helper', 'View');
App::uses('Hash', 'Utility');
App::uses('CakeEvent', 'Event');
App::uses('ClassRegistry', 'Event');

class WidgetHelper extends Helper {

	public $settings = array();
	protected $_blocks = array();
	protected $_Widget = array();

	public function __construct(View $View, $settings = array()) {
		parent::__construct($View, $settings);
		$this->settings = $settings;
		if (empty($this->settings['blocks'])) {
			$this->settings['blocks'] = array();
		}
		$this->settings['blocks'] = Hash::normalize($this->settings['blocks']);
		$this->_Widget = ClassRegistry::getObject('Widget');
	}

	public function beforeRender($viewFile) {
		foreach ($this->settings['blocks'] as $blockName => $settings) {
			$this->_blocks[$blockName] = $this->_Widget->getWidgets($blockName);
			$result = $this->trigger($blockName, 'beforeRender');
			if ($result === false) {
				return false;
			}
		}
	}

	public function render($blockName) {
		if (empty($this->_blocks[$blockName])) {
			$this->_blocks[$blockName] = $this->_Widget->getWidgets($blockName);
			$this->trigger($blockName, 'beforeRender');
		}
		return $this->trigger($blockName, 'render');
	}

	public function trigger($blockName, $callback) {
		$output = null;
		foreach ($this->_blocks[$blockName] as $widget) {
			$result = call_user_func(array($widget, $callback), $this->_View);
			
			if (!is_bool($result)) {
				$output .= $result;
			} else  if ($result === false) {
				return false;
			}
		}
		return $output;
	}

}