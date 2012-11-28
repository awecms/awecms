<?php

App::uses('CakeEventManager', 'Event');
App::uses('SlotCollection', 'PieceOCake/Slot');

class Slot extends Object {

	protected $_Collection;
	public $settings = array();
	public $slots = array();
	protected $_slotMap = array();
	public $element = 'PieceOCake.slot';
	public $Blocks = null;
	protected $_eventManager = null;
	
	public function __construct(SlotCollection $collection, $settings = array()) {
		$this->_Collection = $collection;
		$this->settings = $settings;
		$this->_set($settings);
		if (!empty($this->slots)) {
			$this->_slotMap = SlotCollection::normalizeObjectArray($this->slots);
		}
	}
	
	public function __get($name) {
		if (isset($this->_slotMap[$name]) && !isset($this->{$name})) {
			$settings = array_merge((array)$this->_slotMap[$name]['settings'], array('enabled' => false));
			$this->{$name} = $this->_Collection->load($this->_slotMap[$name]['class'], $settings);
		}
		if (isset($this->{$name})) {
			return $this->{$name};
		}
	}
	
	public function render($View) {
		$blocks = $this->getEventManager->dispatch('Slot.render');
		return $View->element($this->element, $blocks);
	}
	
	public function getEventManager() {
		if (empty($this->_eventManager)) {
			$this->_eventManager = new CakeEventManager();
			$this->_eventManager->attach($this->Blocks);
			$this->_eventManager->attach($this);
		}
		return $this->_eventManager;
	}

}