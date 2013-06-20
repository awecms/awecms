<?php
App::uses('AwecmsAppModel', 'Awecms.Model');
/**
 * User Model
 *
 * @property UserDetail $UserDetail
 */
class User extends AwecmsAppModel {

/**
 * Validation rules
 *
 * @var array
 */
	public $validate = array(
		'username' => array(
			'notempty' => array(
				'rule' => array('notempty'),
				//'message' => 'Your custom message here',
				//'allowEmpty' => false,
				//'required' => false,
				//'last' => false, // Stop validation after this rule
				//'on' => 'create', // Limit validation to 'create' or 'update' operations
			),
		),
		'slug' => array(
			'notempty' => array(
				'rule' => array('notempty'),
				//'message' => 'Your custom message here',
				//'allowEmpty' => false,
				//'required' => false,
				//'last' => false, // Stop validation after this rule
				//'on' => 'create', // Limit validation to 'create' or 'update' operations
			),
		),
	);

	//The Associations below have been created with all possible keys, those that are not needed can be removed

/**
 * hasMany associations
 *
 * @var array
 */
	public $hasMany = array(
		'UserDetail' => array(
			'className' => 'UserDetail',
			'foreignKey' => 'user_id',
			'dependent' => false,
			'conditions' => '',
			'fields' => '',
			'order' => '',
			'limit' => '',
			'offset' => '',
			'exclusive' => '',
			'finderQuery' => '',
			'counterQuery' => ''
		)
	);
	
	public function hash($string, $type = null, $salt = false) {
		return Security::hash($string, $type, $salt);
	}
	
	public function add($postData = null) {
		if (!empty($postData)) {
            $this->data = $postData;
            if ($this->validates()) {
                if (empty($postData[$this->alias]['role'])) {
                    if (empty($postData[$this->alias]['is_admin'])) {
                        $defaultRole = Configure::read('Users.defaultRole');
                        if ($defaultRole) {
                            $postData[$this->alias]['role'] = $defaultRole;
                        } else {
                            $postData[$this->alias]['role'] = 'registered';
                        }
                    } else {
                        $postData[$this->alias]['role'] = 'admin';
                    }
                }
                $postData[$this->alias]['password'] = $this->hash($postData[$this->alias]['password'], null, true);
                $this->create();
                $result = $this->save($postData, false);
                if ($result) {
                    $result[$this->alias][$this->primaryKey] = $this->id;
                    $this->data = $result;
                    return true;
                }
            }
		}
		return false;
	}

}
