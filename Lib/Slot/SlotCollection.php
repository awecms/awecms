<?php

App::uses('ObjectCollection', 'Utility');
App::uses('MissingSlotException', 'Error/Exception');

class SlotCollection extends ObjectCollection implements CakeEventListener {

	protected static $_instance = null;
	
	public static function instance() {
		if (empty(self::$_instance)) {
			self::$_instance = new SlotCollection();
		}
		return self::$_instance;
	}
	
	public function load($slot, $settings = array()) {
		if (is_array($settings) && isset($settings['className'])) {
			$alias = $slot;
			$slot = $settings['className'];
		}
		list($plugin, $name) = pluginSplit($slot, true);
		if (!isset($alias)) {
			$alias = $name;
		}
		
		if (isset($this->_loaded[$alias])) {
			return $this->_loaded[$alias];
		}
		
		$className = $name . 'Slot';
		App::uses($className, $plugin . 'Lib/Slot');
		if (!class_exists($className)) {
			if (isset($alias)) {
				throw new MissingSlotException(array(
					'class' => $className,
					'plugin' => substr($plugin, 0, -1)
				));
			}
			
			App::uses('Slot', 'PieceOCake.Lib/Slot');
			$className = 'Slot';
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
			//'Slot.render' => array('callable' => 'render'), // You don't render a collection of slots.
		);
	}
	
	public function getList() {
		return array_keys($this->_loaded);
	}

}