<?php

App::uses('FormHelper', 'View/Helper');
App::uses('CakeEventManager', 'Event');
App::uses('CakeEvent', 'Event');

class AwecmsFormHelper extends FormHelper {
	
	protected $_layout = null;
	protected $_eventManager = null;
	
	public function create($model = null, $options = array()) {
		$this->_layout = null;
		if (!empty($options['layout'])) {
			$this->_layout = $options['layout'];
			$options = $this->addClass($options, 'form-' . $this->_layout);
		}
		unset($options['layout']);
		return parent::create($model, $options);
	}
	
	public function end($options = null) {
		$out = null;
		if ($options !== null) {
			$out .= $this->actions($options);
		}
		return $out . parent::end();
	}
	
	public function input($fieldName, $options = array()) {
		$this->setEntity($fieldName);
		$entity = $this->model() . '.' . $this->field();
		
		$content = '';
		$beforeEvent = new CakeEvent('Form.beforeRender.' . $entity, $this, array('content' => &$content));
		$this->getEventManager()->dispatch($beforeEvent);
		if ($beforeEvent->isStopped()) {
			return;
		}
		
		$content .= parent::input($fieldName, $options);
		$afterEvent = new CakeEvent('Form.beforeRender.' . $entity, $this, array('content' => &$content));
		$this->getEventManager()->dispatch($afterEvent);
		if ($afterEvent->isStopped()) {
			return;
		}
		return $content;
	}
	
	protected function _divOptions($options) {
		$options = parent::_divOptions($options);
		if ($options && $this->_layout === 'horizontal') {
			$options = $this->addClass($options, 'control-group');
		}
		return $options;
	}
	
	protected function _inputLabel($fieldName, $label, $options) {
		if ($this->_layout === 'horizontal') {
			if (is_string($label)) {
				$label = array('text' => $label);
			}
			if (!isset($label['class']) || $label['class'] !== false) {
				$label = $this->addClass($label, 'control-label');
			}
		}
		return parent::_inputLabel($fieldName, $label, $options);
	}
	
	protected function _getInput($args) {
		$addOnOptions = array();
		$prepend = $append = null;
		if (!empty($args['options']['prepend'])) {
			$addOnOptions = $this->addClass($addOnOptions, 'input-prepend');
			$prepend = $this->Html->tag('span', $args['options']['prepend'], array('class' => 'add-on'));
		}
		if (!empty($args['options']['append'])) {
			$addOnOptions = $this->addClass($addOnOptions, 'input-append');
			$append = $this->Html->tag('span', $args['options']['append'], array('class' => 'add-on'));
		}
		unset($args['options']['prepend'], $args['options']['append']);
		
		$output = parent::_getInput($args);
		if (!empty($addOnOptions)) {
			$output = $this->Html->div(null, $prepend . $output . $append, $addOnOptions);
		}
		if ($this->_layout === 'horizontal') {
			$output = $this->Html->div('controls', $output);
		}
		return $output;
	}
	
	public function actions($options = array()) {
		$submitOptions = array();
		if (is_string($options)) {
			$submit = $options;
		} else {
			if (isset($options['label'])) {
				$submit = $options['label'];
				unset($options['label']);
			}
			$submitOptions = $options;
		}
		if (!isset($submitOptions['class'])) {
			$submitOptions['class'] = 'btn btn-primary';
		}
		
		if (!isset($submitOptions['div']) || $submitOptions['div'] === true) {
			$submitOptions['div'] = array('class' => 'form form-actions');
		} else if (is_string($submitOptions['div'])) {
			$submitOptions['div'] = array('class' => $submitOptions['div']);
		}
		
		$cancel = true;
		if (isset($options['cancel'])) {
			$cancel = $options['cancel'];
			unset($options['cancel']);
		}
		$cancelOptions = array('class' => 'btn', 'label' => __d('awecms', 'Cancel'), 'url' => array('action' => 'index'));
		
		if ($cancel === true) {
			$cancelOptions['class'] = 'btn';
		} elseif ($cancel === false) {
			unset($cancelOptions);
		} elseif (is_string($cancel)) {
			$cancelLabel = $cancel;
		} elseif (is_array($cancel)) {
			$cancelOptions = array_merge($cancelOptions, $cancel);
		}
		
		if (isset($cancelOptions)) {
			$cancelLabel = $cancelOptions['label'];
			$cancelUrl = $cancelOptions['url'];
			unset($cancelOptions['label'], $cancelOptions['url']);
			
			if (!isset($submitOptions['after'])) {
				$submitOptions['after'] = '';
			}
			$submitOptions['after'] = ' ' . $this->Html->link($cancelLabel, $cancelUrl, $cancelOptions) . $submitOptions['after'];
		}
		
		return $this->submit($submit, $submitOptions);
	}
	
	public function getEventManager() {
		if (empty($this->_eventManager)) {
			$this->_eventManager = new CakeEventManager();
		}
		return $this->_eventManager;
	}
}