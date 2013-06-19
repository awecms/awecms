<?php

class WidgetSource extends DataSource {

	public $description = 'A datasource for widgets.';

	protected $_schema = array(
		'id' => array(
            'type' => 'integer',
            'null' => false,
            'key' => 'primary',
            'length' => 11,
        ),
		'name' => array(
			'type' => 'string',
			'null' => true,
			'length' => 255,
		),
		'order' => array(
            'type' => 'integer',
            'null' => false,
            'length' => 11,
            'default' => 0,
        ),
		'is_active' => array(
            'type' => 'boolean',
            'null' => false,
            'default' => 1,
        ),
	);
	
	public function listSources() {
		$POC = Awecms::instance();
		return $POS->getWidgetList();
	}
	
	public function describe(Model $Model) {
		return $this->_schema;
	}
}