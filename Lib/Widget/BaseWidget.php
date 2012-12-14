<?php

App::uses('CakeEventManager', 'Event');

class BaseWidget extends Object implements CakeEventListener {

	public $id = null;
	public $data = array();
	protected $_widget = null;
	protected $_View = null;
	protected $_extend = null;
	protected $_eventManager = null;
	protected $_beforeRenderCalled = false;
	
	public function __construct($widget) {
		$this->id = $widget['id'];
		$this->_widget = $widget;
	}
	
	public function implementedEvents() {
		return array(
			'View.beforeRender' => 'beforeRenderCheck',
			'View.afterRender' => 'afterRender',
			'Widget.beforeRender' => 'beforeWidgetRender',
			'Widget.afterRender' => 'afterWidgetRender',
		);
	}
	
	public function getEventManager() {
		if (empty($this->_eventManager)) {
			$this->_eventManager = new CakeEventManager();
		}
		return $this->_eventManager;
	}
	
	public function initialize($view) {
		$this->_View = $view;
		$this->_eventManager = $view->getEventManager();
		$this->_eventManager->attach($this);
	}
	
	public function getName() {
		return $this->_widget['name'];
	}
	
	public function extend($viewFile) {
		$this->_extend = $viewFile;
	}
	
	public function render() {
		$this->beforeRenderCheck();
		
		$Blocks = $this->_View->Blocks;
		$this->_View->Blocks = new ViewBlock();
		
		CakeEventManager::instance()->dispatch(new CakeEvent('Widget.beforeRender', $this));
		$content = $this->getContent();
		CakeEventManager::instance()->dispatch(new CakeEvent('Widget.afterRender', $this));
		
		if ($this->_extend) {
			$this->_View->assign('title', $this->getName());
			$this->_View->assign('content', $content);
			$content = $this->_View->element($this->_extend);
		}
		
		$this->_View->Blocks = $Blocks;
		
		return $content;
	}
	
	public function beforeRenderCheck() {
		if (!$this->_beforeRenderCalled) {
			$this->_beforeRenderCalled = true;
			return $this->beforeRender();
		}
	}

	public function getContent() {
	}
	
	public function beforeRender() {
	}
	
	public function afterRender() {
	}
	
	public function beforeWidgetRender() {
	}
	
	public function afterWidgetRender() {
	}

}