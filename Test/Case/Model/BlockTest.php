<?php
App::uses('Block', 'PieceOCake.Model');

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
		'plugin.piece_o_cake.block',
		'plugin.piece_o_cake.slot',
		'plugin.piece_o_cake.slide'
	);

/**
 * setUp method
 *
 * @return void
 */
	public function setUp() {
		parent::setUp();
		$this->Block = ClassRegistry::init('PieceOCake.Block');
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
