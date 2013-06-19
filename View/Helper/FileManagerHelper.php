<?php
class FileManagerHelper extends AppHelper {

	public $helpers = array(
		'FineUploader.FineUploader' => array(
			'template' =>  'Awecms.FineUploader/template',
			'fileTemplate' =>  'Awecms.FineUploader/fileTemplate',
			'scriptOptions' => array(
				'text' => array(
					'uploadButton' => '<i class="icon-upload icon-white"></i> Upload a file'
				),
				'classes' => array(
					'success' => 'alert alert-success',
					'fail' => 'alert alert-error'
				)
			),
			'css' => 'Awecms.FineUploader/fineuploader'
		)
	);

	public function __construct(View $View, $settings = array()) {
		list($helper, $options) = each($this->helpers);
		$this->ProxiedHelper = $View->loadHelper($helper, $options);
		parent::__construct($View, $settings);
	}
	
	public function beforeRender($viewFile) {
		return $this->ProxiedHelper->beforeRender($viewFile);
	}
	
	public function __call($name, $arguments) {
		return call_user_func_array(array($this->ProxiedHelper, $name), $arguments);
	}

}