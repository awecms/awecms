<?php

App::uses('AppController', 'Controller');

class AwecmsAppController extends AppController {

	public $components = array(
		'RequestHandler' => array(
			'ajaxLayout' => 'Awecms.ajax'
		)
	);

}

