<?php
App::uses('Block', 'Awecms.Model');

/**
 * Block Test Case
 *
 */
class BlockTest extends CakeTestCase {

/**
 * Fixtures
 *
 * @var array
 */
	public $fixtures = array(
		'plugin.awecms.block',
		'plugin.awecms.slot',
		'plugin.awecms.slide'
	);

/**
 * setUp method
 *
 * @return void
 */
	public function setUp() {
		parent::setUp();
		$this->Block = ClassRegistry::init('Awecms.Block');
	}

/**
 * tearDown method
 *
 * @return void
 */
	public function tearDown() {
		unset($this->Block);

		parent::tearDown();
	}

}
