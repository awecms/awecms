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

	protected $_blocks = array();
	protected $_widgetClasses = array();

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
	
	public function registerWidgetClass($name) {
		list($plugin, $widgetName) = pluginSplit($name, true);
		$className = $widgetName . 'Widget';
		$this->_widgetClasses[$name] = compact('plugin', 'className', 'widgetName');
	}
	
	public function getWidgetClasses() {
		return $this->_widgetClasses;
	}
	
	public function getWidgets($blockName) {
		$widgets = array();
		$data = $this->findAllByBlock($blockName);
		foreach ($data as $widget) {
			extract($this->getWidgetClass($widget['Widget']['class']));
			App::uses($className, $plugin . 'Widget');
			$widgets[] = new $className($widget['Widget']);
		}
		return $widgets;
	}
	
	public function getWidgetClass($name) {
		if (!isset($this->_widgetClasses[$name])) {
			debug($name);die;
			throw new CakeException(__('Widget Class "%s" is not registered.', $name));
		}
		return $this->_widgetClasses[$name];
	}

}
