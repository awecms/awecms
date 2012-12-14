<?php

App::uses('Helper', 'View');
App::uses('Hash', 'Utility');
App::uses('CakeEvent', 'Event');
App::uses('ClassRegistry', 'Event');

class WidgetHelper extends Helper {

	public $settings = array();
	protected $_blocks = array();
	protected $_Widget = null;

	public function __construct(View $View, $settings = array()) {
		parent::__construct($View, $settings);
		$this->settings = $settings;
		if (empty($this->settings['blocks'])) {
			$this->settings['blocks'] = array();
		}
		$this->settings['blocks'] = Hash::normalize($this->settings['blocks']);
		if (empty($this->settings['renderer'])) {
			$this->settings['renderer'] = 'PieceOCake.default-renderer';
		}
		$this->_Widget = ClassRegistry::getObject('Widget');
		
		foreach ($this->settings['blocks'] as $blockName => $settings) {
			$this->_initBlock($blockName);
		}
	}

	public function render($blockName, $options = array()) {
		if (empty($options['renderer'])) {
			$options['renderer'] = $this->settings['renderer'];
		}
		if (!isset($options['class'])) {
			$options['class'] = $blockName;
		} else if ($options['class'] === false) {
			$options['class'] = '';
		}
		
		$block = $this->_initBlock($blockName);
		if ($block === false) {
			return false;
		}
		
		return $this->_View->element($options['renderer'], array('widgets' => $block, 'blockName' => $blockName, 'options' => $options));
	}
	
	protected function _initBlock($blockName) {
		if (!array_key_exists($blockName, $this->_blocks)) {
			$this->_blocks[$blockName] = $this->_Widget->getWidgets($blockName);
			$this->_call($blockName, 'initialize', array($this->_View));
		}
		return $this->_blocks[$blockName];
	}

	protected function _call($blockName, $callable, $parameters = array()) {
		if (!is_array($this->_blocks[$blockName])) {
			return false;
		}
		
		foreach ($this->_blocks[$blockName] as $widget) {
			$result = call_user_func_array(array($widget, $callable), $parameters);
			if ($result === false) {
				return false;
			}
		}
		
		return true;
	}

}