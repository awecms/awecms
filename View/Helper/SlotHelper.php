<?php

App::uses('Helper', 'View');
App::uses('ClassRegistry', 'Utility');
App::uses('SlotCollection', 'Slot');

class SlotHelper extends Helper {
	
	public $Slots = null;
	protected $_View = null;
	
	public function __construct(View $View, $settings = array())) {
		parent::__construct($View, $settings);
		$this->Slots = SlotCollection::instance();
	}
	
	public function render($name) {
		return $this->Slots->{$name}->render($this->_View);
	}

}