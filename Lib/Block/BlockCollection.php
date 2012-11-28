<?php

App::uses('ObjectCollection', 'Utility');
App::uses('MissingBlockException', 'Error/Exception');

class BlockCollection extends ObjectCollection implements CakeEventListener {

	public function load($block, $settings = array()) {
		if (is_array($settings) && isset($settings['className'])) {
			$alias = $block;
			$block = $settings['className'];
		}
		list($plugin, $name) = pluginSplit($block, true);
		if (!isset($alias)) {
			$alias = $name;
		}
		
		if (isset($this->_loaded[$alias])) {
			return $this->_loaded[$alias];
		}
		
		$className = $name . 'Block';
		App::uses($className, $plugin . 'Lib/Block');
		if (!class_exists($className)) {
			throw new MissingBlockException(array(
				'class' => $className,
				'plugin' => substr($plugin, 0, -1)
			));
		}
		$this->_loaded[$alias] = new $className($this, $settings);
		
		$enable = isset($settings['enabled']) ? $settings['enabled'] : true;
		if ($enable) {
			$this->enable($alias);
		}
		
		return $this->_loaded[$alias];
	}
	
	public function implementedEvents() {
		return array(
			'Slot.render' => array('callable' => 'render'),
		);
	}

}