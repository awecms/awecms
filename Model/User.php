<?php
App::uses('PieceOCakeAppModel', 'PieceOCake.Model');
App::uses('AuthComponent', 'Controller/Component');
/**
 * User Model
 *
 */
class User extends PieceOCakeAppModel {
/**
 * Validation rules
 *
 * @var array
 */
	public $validate = array(
		'username' => array(
			'notempty' => array(
				'rule' => array('notempty'),
				'message' => 'You must enter a username',
				//'allowEmpty' => false,
				//'required' => false,
				//'last' => false, // Stop validation after this rule
				//'on' => 'create', // Limit validation to 'create' or 'update' operations
			),
		),
		'password' => array(
			'notempty' => array(
				'rule' => array('notempty'),
				'message' => 'You must enter a password',
				//'allowEmpty' => false,
				//'required' => false,
				//'last' => false, // Stop validation after this rule
				'on' => 'create', // Limit validation to 'create' or 'update' operations
			),
		),
		'is_active' => array(
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
	
	public function beforeSave($options = array()) {
		if ($this->data[$this->alias]['is_locked']) {
			return false;
		} else if (!empty($this->data[$this->alias]['password'])) {
			if ($this->data[$this->alias]['password'] != $this->data[$this->alias]['password_repeat']) {
				return false;
			} else {
				$this->data[$this->alias]['password'] = AuthComponent::password($this->data[$this->alias]['password']);
			}
		} else {
			unset($this->data[$this->alias]['password']);
		}
        return true;
    }
	
	public function afterFind($results, $primary = false) {
		if (isset($results[$this->alias]['password'])) {
			unset($results[$this->alias]['password']);
		} else {
			foreach ($results as $key => $user) {
				if (isset($user[$this->alias]['password'])) {
					unset($results[$key][$this->alias]['password']);
				}
			}
		}
		return $results;
	}
}
