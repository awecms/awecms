<?php
class FileManagerHelper extends AppHelper {

	public $helpers = array(
		'FineUploader.FineUploader' => array(
			'template' =>  'PieceOCake.FineUploader/template',
			'fileTemplate' =>  'PieceOCake.FineUploader/fileTemplate',
			'scriptOptions' => array(
				'text' => array(
					'uploadButton' => '<i class="icon-upload icon-white"></i> Upload a file'
				),
				'classes' => array(
					'success' => 'alert alert-success',
					'fail' => 'alert alert-error'
				)
			),
			'css' => 'PieceOCake.FineUploader/fineuploader'
		)
	);

	public function __construct(View $View, $settings = array()) {
		parent::__construct($View, $settings);
		// Make sure the FineUploader get constructed at the same time at this helper
		$this->FineUploader !== null;
	}
	
	public function beforeRender($viewFile) {
		return $this->FineUploader->beforeRender($viewFile);
	}
	
	public function __call($name, $arguments) {
		return call_user_func_array(array($this->FineUploader, $name), $arguments);
	}

}