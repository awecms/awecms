<?php

App::uses('Hash', 'Utility');
App::uses('CakeRequest', 'Network');
App::uses('Router', 'Routing');

class AwecmsUploader {

	public $request = null;

	public $_settings = array(
		'allowedExtensions' => array(
			'image' => array(
				'jpe?g',
				'png',
				'bmp',
				'gif'
			),
			'document' => array(
				'pdf',
				'doc',
				'docx',
				'txt',
				'odt',
				'rtf'
			)
		),
		'type' => 'image',
		'uploadPath' => null,
		'uploadUrl' => null,
		'method' => 'auto',
		'useRelativePath' => true
	);

	protected $_lastErrorMessage = null;
	protected $_uploadedFileName = null;

	public function __construct(CakeRequest $request, $settings = array()) {
		$this->request = $request;
		$this->_settings = array_merge($this->_settings, $settings);

		if ($this->_settings['uploadPath'] === null && $this->_settings['useRelativePath']) {
			$this->_settings['uploadPath'] = Configure::read('Awecms.uploadPath');
		}
		if ($this->_settings['uploadPath'] === null) {
			throw new Exception('Please set \'Awecms.uploadPath\' in your configuration.');
		}
		$this->_settings['uploadPath'] = str_replace('/', DS, $this->_settings['uploadPath']);
		if ($this->_settings['useRelativePath']) {
			$this->_settings['uploadPath'] = APP . ltrim($this->_settings['uploadPath'], DS);
		}
		if (substr($this->_settings['uploadPath'], -1, 1) !== DS) {
			$this->_settings['uploadPath'] .= DS;
		}
		if (!file_exists($this->_settings['uploadPath'])) {
			throw new Exception(sprintf('Upload path does not exist. Please create \'%s\'.', $this->_settings['uploadPath']));
		}

		if ($this->_settings['uploadUrl'] === null) {
			$this->_settings['uploadUrl'] = '/' . Configure::read('Awecms.uploadUrl');
		}
	}

	public function upload($hashPath, $requestProperty = 'data', $targetFileName = null) {
		$this->_lastErrorMessage = null;

		$properties = array('data', 'form', 'query', 'named', 'pass');
		if (!in_array($requestProperty, $properties)) {
			$this->_lastErrorMessage = sprintf('$requestProperty must be one of \'%s\'', implode($properties, '\', \''));
			return false;
		}

		//debug($this->request->{$requestProperty});
		$fileData = Hash::get($this->request->{$requestProperty}, $hashPath);
		if (empty($fileData)) {
			$fileData = Hash::get($this->request->params, $hashPath);
		}
		if (empty($fileData)) {
			$this->_lastErrorMessage = 'No file was uploaded.';
			return false;
		}

		$method = $this->_settings['method'];
		if ($method === 'auto') {
			$method = $this->request->is('ajax') ? 'ajax' : 'file';
		}
		switch ($method) {
			case 'ajax':
				$result = $this->_uploadAjax($fileData, $targetFileName);
				break;

			case 'file':
				$result = $this->_uploadFile($fileData, $targetFileName);
				break;

			default:
				throw new Exception(sprintf('Undefined upload method \'%s\'.', $method));
		}

		if (!$result && $this->_lastErrorMessage === null) {
			$this->_lastErrorMessage = 'The file could not be saved.';
		}
		return $result;
	}

	public function getUploadedFileName() {
		if ($this->_lastErrorMessage !== null) {
			return false;
		}
		return $this->_uploadedFileName;
	}

	public function getUploadedUrl() {
		if ($this->_lastErrorMessage !== null || $this->_settings['uploadUrl'] === null) {
			return false;
		}
		return Router::url($this->_settings['uploadUrl'] . $this->getUploadedFileName());
	}

	public function getLastErrorMessage() {
		return $this->_lastErrorMessage;
	}

	protected function _uploadAjax($originalFileName, $targetFileName) {
		$fileInfo = pathinfo($originalFileName);
		if (!$this->_validateFileExtension($fileInfo)) {
			return false;
		}

		$targetPath = $this->_getTargetPath($fileInfo, $targetFileName);
		return file_put_contents($targetPath, $this->request->input()) !== false;
	}

	protected function _uploadFile($fileData, $targetFileName) {
		$fileInfo = pathinfo($fileData['name']);
		if (!$this->_validateFileExtension($fileInfo)) {
			return false;
		}

		$targetPath = $this->_getTargetPath($fileInfo, $targetFileName);
		return move_uploaded_file($fileData['tmp_name'], $targetPath);
	}

	protected function _validateFileExtension($fileInfo) {
		if ($this->_settings['type'] === 'all') {
			$allowedExtensions = Hash::extract($this->_settings['allowedExtensions'], '{s}');
		} else {
			$allowedExtensions = Hash::get($this->_settings['allowedExtensions'], $this->_settings['type']);
		}
		if ($allowedExtensions === null) {
			throw new Exception(sprintf('Undefined type \'%s\'.', $this->_settings['type']));
		}

		if (!isset($fileInfo['extension'])) {
			$isAllowed = in_array(null, $allowedExtensions, true);
			$errorMessage = 'Files with no extension are not allowed.';
		} else {
			$isAllowed = preg_match('#^(' . implode('|', $allowedExtensions) . ')$#i', $fileInfo['extension']) > 0;
			$errorMessage = sprintf('The file extension \'%s\' is not allowed.', $fileInfo['extension']);
		}

		if (!$isAllowed) {
			$this->_lastErrorMessage = $errorMessage;
		}
		return $isAllowed;
	}

	protected function _getTargetPath($fileInfo, $targetFileName = null) {
		if (empty($targetFileName)) {
			$targetFileName = $fileInfo['filename'];
		}

		$targetFileName = preg_replace('#[^\s\w.-]+#', '', $targetFileName);
		$targetFileName = preg_replace('#[\s-]+#', '-', $targetFileName);

		$extension = isset($fileInfo['extension']) ? '.' . $fileInfo['extension'] : '';
		$this->_uploadedFileName = $targetFileName . $extension;
		$targetPath = $this->_settings['uploadPath'] . $this->_uploadedFileName;
		$i = 1;
		while (file_exists($targetPath) && $i < 100) {
			$this->_uploadedFileName = sprintf('%s-%02d%s', $targetFileName, $i, $extension);
			$targetPath = $this->_settings['uploadPath'] . $this->_uploadedFileName;
			$i++;
		}

		if ($i === 100) {
			$this->_lastErrorMessage =
				'Too many files upload with the same or similar name. Change the file name and try again.';
			$this->_uploadedFileName = null;
			return false;
		}

		return $targetPath;
	}
}