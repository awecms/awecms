<?php
class EditorHelper extends FormHelper {

	public function __construct(View $View, $settings = array()) {
		$this->settings = Hash::normalize($settings);
		if (empty($this->settings['editor'])) {
			$this->settings['editor'] = Configure::read('Admin.editor');
		}
		$this->helpers['ProxiedEditor'] = array('className' => $this->settings['editor']);
		
		parent::__construct($View, $this->settings);
	}
	
	public function input($field, $options = array()) {
		return $this->ProxiedEditor->input($field, $options);
	}
}