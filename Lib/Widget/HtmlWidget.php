<?php

App::uses('BaseWidget', 'Awecms.Widget');

class HtmlWidget extends BaseWidget {

	public function __construct($widget) {
		parent::__construct($widget);
		$this->settings = $widget['data'];
		if (empty($this->settings['content'])) {
			$this->settings['content'] = '';
		}
		$this->settings['escape'] = empty($this->settings['escape']) ? false : true;
	}

	public function getContent() {
		return $this->settings['escape'] ? h($this->settings['content']) : $this->settings['content'];
	}

}