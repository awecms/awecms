<?php

App::uses('HtmlHelper', 'View/Helper');

class AwecmsHtmlHelper extends HtmlHelper {

	public function assetUrl($path, $options = array()) {
		if (!empty($options['upload'])) {
			$options['pathPrefix'] = Configure::read('Awecms.uploadUrl');
		}
		return parent::assetUrl($path, $options);
	}
}