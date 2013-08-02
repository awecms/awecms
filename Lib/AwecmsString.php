<?php

App::uses('String', 'Utility');
App::uses('Sanitize', 'Utility');

class AwecmsString {

	protected static $_appCharset = null;

	public static function htmlEntityDecode($str, $charset = null) {
		if (empty($str)) {
			return '';
		}
		
		self::_init();
		if ($charset === null) {
			$charset = self::$_appCharset;
		}
		
		if (function_exists('mb_convert_encoding')) {
			return mb_convert_encoding($str, $charset, 'HTML-ENTITIES');
		}

		$flags = ENT_QUOTES;
		if (defined('ENT_HTML401')) {
			$flags |= constant('ENT_HTML401');
		}
		
		return html_entity_decode($str, $flags, $charset);
	}
	
	public static function htmlToText($html) {
		$text = self::htmlEntityDecode(strip_tags(Sanitize::stripScripts($html)));
		return trim(preg_replace('/\s+/', ' ', $text));
	}
	
	public static function encodeString($text, $charset) {
		self::_init();
		if (self::$_appCharset === $charset || !function_exists('mb_convert_encoding')) {
			return $text;
		}
		return mb_convert_encoding($text, $charset, self::$_appCharset);
	}
	
	protected static function _init() {
		if (self::$_appCharset === null) {
			self::$_appCharset = Configure::read('App.encoding');
			if (self::$_appCharset === null) {
				self::$_appCharset = 'UTF-8';
			}
		}
	}
}