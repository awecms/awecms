<?php
App::uses('PieceOCakeAppModel', 'PieceOCake.Model');
App::uses('CakeEvent', 'Event');
App::uses('Inflector', 'Utility');
/**
 * Widget Model
 *
 * @property Slot $Slot
 * @property Slide $Slide
 */
class Widget extends PieceOCakeAppModel {

	public $actsAs = array(
		'Utils.Serializable' => array('field' => 'data', 'engine' => 'json')
	);
	public $useTable = 'widgets';
	public $order = array('Widget.block', 'Widget.order');
	
	protected $_blocks = array();
	protected $_widgetClasses = array();
	protected $_widgets = array();

/**
 * Validation rules
 *
 * @var array
 */
	public $validate = array(
		'name' => array(
			'notempty' => array(
				'rule' => array('notempty'),
				//'message' => 'Your custom message here',
				//'allowEmpty' => false,
				//'required' => false,
				//'last' => false, // Stop validation after this rule
				//'on' => 'create', // Limit validation to 'create' or 'update' operations
			),
		),
		'class' => array(
			'notempty' => array(
				'rule' => array('notempty'),
				//'message' => 'Your custom message here',
				//'allowEmpty' => false,
				//'required' => false,
				//'last' => false, // Stop validation after this rule
				//'on' => 'create', // Limit validation to 'create' or 'update' operations
			),
		),
		'order' => array(
			'numeric' => array(
				'rule' => array('numeric'),
				//'message' => 'Your custom message here',
				//'allowEmpty' => false,
				//'required' => false,
				//'last' => false, // Stop validation after this rule
				//'on' => 'create', // Limit validation to 'create' or 'update' operations
			),
		),
		'is_active' => array(
			'boolean' => array(
				'rule' => array('boolean'),
				//'message' => 'Your custom message here',
				//'allowEmpty' => false,
				//'required' => false,
				//'last' => false, // Stop validation after this rule
				//'on' => 'create', // Limit validation to 'create' or 'update' operations
			),
		),
	);
	
	public function __construct($id = false, $table = null, $ds = null) {
		parent::__construct($id, $table, $ds);
		$event = new CakeEvent('Widget.initialize', $this);
		$this->getEventManager()->dispatch($event);
	}

	public function registerBlock($blockName, $options = array()) {
		if (empty($options['title'])) {
			$options['title'] = Inflector::humanize($blockName);
		}
		$this->_blocks[$blockName] = $options;
	}
	
	public function getBlocks() {
		return $this->_blocks;
	}
	
	public function getBlockList() {
		$keys = array_keys($this->_blocks);
		$values = Hash::extract($this->_blocks, '{s}.title');
		return array_combine($keys, $values);
	}
	
	public function registerWidgetClass($name, $options = array()) {
		list($plugin, $widgetName) = pluginSplit($name, true);
		$className = $widgetName . 'Widget';
		
		$title = Inflector::humanize(Inflector::underscore($widgetName));
		if (!empty($options['title'])) {
			$title = $options['title'];
		}
		
		$editUrl = array('admin' => true, 'plugin' => 'piece_o_cake', 'controller' => 'widgets', 'action' => 'edit');
		if (!empty($options['editUrl'])) {
			$editUrl = array_merge($editUrl, $options['editUrl']);
		}
		
		$this->_widgetClasses[$name] = compact('plugin', 'className', 'widgetName', 'title', 'options', 'editUrl');
	}
	
	public function getEditUrls() {
		$keys = array_keys($this->_widgetClasses);
		$values = Hash::extract($this->_widgetClasses, '{s}.editUrl');
		return array_combine($keys, $values);
	}
	
	public function getWidgetClasses() {
		return $this->_widgetClasses;
	}
	
	public function getWidgetClassList() {
		$keys = array_keys($this->_widgetClasses);
		$values = Hash::extract($this->_widgetClasses, '{s}.title');
		return array_combine($keys, $values);
	}
	
	public function getWidgets($blockName) {
		$widgets = array();
		$data = $this->findAllByBlockAndIsActive($blockName, 1, array(), 'Widget.order');
		foreach ($data as $widget) {
			if (!isset($this->_widgets[$widget['Widget']['id']])) {
				$this->_initWidget($widget);
			}
			$widgets[] = $this->_widgets[$widget['Widget']['id']];
		}
		return $widgets;
	}
	
	public function getWidget($id) {
		if (!isset($this->_widgets[$id])) {
			$this->_initWidget($this->findById($id));
		}
		return $this->_widgets[$id];
	}
	
	protected function _initWidget($widget) {
		extract($this->getWidgetClass($widget['Widget']['class']));
		App::uses($className, $plugin . 'Widget');
		$this->_widgets[$widget['Widget']['id']] = new $className($widget['Widget']);
	}
	
	public function getWidgetClass($name) {
		if (!isset($this->_widgetClasses[$name])) {
			die(__('Widget Class "%s" is not registered.', $name));
			throw new CakeException(__('Widget Class "%s" is not registered.', $name));
		}
		return $this->_widgetClasses[$name];
	}

}
