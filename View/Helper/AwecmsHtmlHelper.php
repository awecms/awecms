<?php

App::uses('HtmlHelper', 'View/Helper');

class AwecmsHtmlHelper extends HtmlHelper {
    
    public function image($path, $options = array()) {
        if (!empty($options['upload'])) {
            $options['pathPrefix'] = Configure::read('Awecms.uploadUrl');
        }
        unset($options['upload']);
        return parent::image($path, $options);
    }
    
    public function media($path, $options = array()) {
        if (!empty($options['upload'])) {
            $options['pathPrefix'] = Configure::read('Awecms.uploadUrl');
        }

        return parent::media($path, $options);
    }

	public function assetUrl($path, $options = array()) {
		if (!empty($options['upload'])) {
			$options['pathPrefix'] = Configure::read('Awecms.uploadUrl');
		}
		unset($options['upload']);
		return parent::assetUrl($path, $options);
	}
}