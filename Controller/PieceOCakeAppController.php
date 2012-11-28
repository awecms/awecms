<?php

class PieceOCakeAppController extends AppController {

	public $components = array(
		'RequestHandler' => array(
			'ajaxLayout' => 'PieceOCake.ajax'
		)
	);

}

