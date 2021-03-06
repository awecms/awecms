<?php
App::uses('AwecmsAppModel', 'Awecms.Model');
/**
 * Config Model
 *
 */
class Config extends AwecmsAppModel {
	
	/*public $actsAs = array(
		'Utils.Serializable' => array('field' => 'value')
	);*/
	
	//public $order = 'name';
	public $useDbConfig = 'config';
	public $useTable = 'awecms';
	
/**
 * Validation rules
 *
 * @var array
 */
	/*public $validate = array(
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
		'is_locked' => array(
			'notempty' => array(
				'rule' => array('notempty'),
				//'message' => 'Your custom message here',
				//'allowEmpty' => false,
				//'required' => false,
				//'last' => false, // Stop validation after this rule
				//'on' => 'create', // Limit validation to 'create' or 'update' operations
			),
		),
	);*/
	
	public function write($name, $value) {
		$this->create();
		$config = array('Config' => compact('name', 'value'));
		$this->save($config);
	}
}
