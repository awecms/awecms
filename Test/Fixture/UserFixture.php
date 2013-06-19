<?php
/**
 * UserFixture
 *
 */
class UserFixture extends CakeTestFixture {

/**
 * Fields
 *
 * @var array
 */
	public $fields = array(
		'id' => array('type' => 'string', 'null' => false, 'length' => 36, 'key' => 'primary'),
		'username' => array('type' => 'string', 'null' => false),
		'slug' => array('type' => 'string', 'null' => false),
		'password' => array('type' => 'string', 'null' => true, 'default' => null, 'length' => 128),
		'password_token' => array('type' => 'string', 'null' => true, 'default' => null, 'length' => 128),
		'email' => array('type' => 'string', 'null' => true, 'default' => null),
		'email_verified' => array('type' => 'boolean', 'null' => true, 'default' => '0'),
		'email_token' => array('type' => 'string', 'null' => true, 'default' => null),
		'email_token_expires' => array('type' => 'datetime', 'null' => true, 'default' => null),
		'tos' => array('type' => 'boolean', 'null' => true, 'default' => '0'),
		'active' => array('type' => 'boolean', 'null' => true, 'default' => '0'),
		'last_login' => array('type' => 'datetime', 'null' => true, 'default' => null),
		'last_action' => array('type' => 'datetime', 'null' => true, 'default' => null),
		'is_admin' => array('type' => 'boolean', 'null' => true, 'default' => '0'),
		'role' => array('type' => 'string', 'null' => true, 'default' => null),
		'created' => array('type' => 'datetime', 'null' => true, 'default' => null),
		'modified' => array('type' => 'datetime', 'null' => true, 'default' => null),
		'indexes' => array(
			'users_BY_EMAIL' => array('column' => 'email', 'unique' => 0),
			'users_BY_USERNAME' => array('column' => 'username', 'unique' => 0)
		),
		'tableParameters' => array()
	);

/**
 * Records
 *
 * @var array
 */
	public $records = array(
		array(
			'id' => '516cf27c-42f8-4aef-8ebc-87b8cfa47919',
			'username' => 'Lorem ipsum dolor sit amet',
			'slug' => 'Lorem ipsum dolor sit amet',
			'password' => 'Lorem ipsum dolor sit amet',
			'password_token' => 'Lorem ipsum dolor sit amet',
			'email' => 'Lorem ipsum dolor sit amet',
			'email_verified' => 1,
			'email_token' => 'Lorem ipsum dolor sit amet',
			'email_token_expires' => '2013-04-16 07:41:00',
			'tos' => 1,
			'active' => 1,
			'last_login' => '2013-04-16 07:41:00',
			'last_action' => '2013-04-16 07:41:00',
			'is_admin' => 1,
			'role' => 'Lorem ipsum dolor sit amet',
			'created' => '2013-04-16 07:41:00',
			'modified' => '2013-04-16 07:41:00'
		),
	);

}
