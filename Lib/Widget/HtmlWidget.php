<?php

App::uses('BaseWidget', 'PieceOCake.Widget');

class HtmlWidget extends BaseWidget {

	public function __construct($widget) {
		parent::__construct($widget);
		$this->settings = json_decode($widget['content']);
		if (empty($this->settings['content'])) {
			$this->settings['content'] = null;
		}
		$this->settings['escape'] = empty($this->settings['escape']) ? false : true;
	}

	public function getContent() {
		return $this->settings['escape'] ? h($this->settings['escape']) : $this->settings['escape'];
	}

}