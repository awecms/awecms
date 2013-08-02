<?php

App::uses('AppHelper', 'View/Helper');

class ShortcodeHelper extends AppHelper {

	public $helpers = array('Awecms.Widget');

	public function filter($content) {
		preg_match_all('/(?:<p[^>]*>\s*)?{{widget_(\d+)}}(?:\s*<\/p>)?/i', $content, $matches, PREG_SET_ORDER);
		$widgets = array();
		foreach ($matches as $match) {
			$widgets[$match[0]] = $this->Widget->fetchWidget($match[1]);
		}
		return strtr($content, $widgets);
	}
}