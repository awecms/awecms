<?php
App::uses('AwecmsAppController', 'Awecms.Controller');
/**
 * Pages Controller
 *
 * @property Page $Page
 */
class StaticPagesController extends AwecmsAppController {

	public $uses = array();
	
	public function beforeFilter() {
		if (!empty($this->request->params['prefix'])) {
			$regex = '/^' . preg_quote($this->request->params['prefix'], '/') . '_/';
			$this->request->params['action'] = preg_replace($regex, '', $this->request->params['action']);
		}
	}

	public function display() {
		$path = func_get_args();

		$count = count($path);
		if (!$count) {
			$this->redirect('/');
		}
		$page = $subpage = $title_for_layout = null;

		if (!empty($path[0])) {
			$page = $path[0];
		}
		if (!empty($path[1])) {
			$subpage = $path[1];
		}
		if (!empty($path[$count - 1])) {
			$title_for_layout = Inflector::humanize($path[$count - 1]);
		}
		if (!empty($this->request->params['prefix'])) {
			array_unshift($path, $this->request->params['prefix']);
		}
		array_unshift($path, 'StaticPages');
		$this->set(compact('page', 'subpage', 'title_for_layout'));
		$this->render(implode('/', $path));
	}
	
	public function plugin_display() {
		$path = func_get_args();
		$plugin = array_shift($path);
		$viewPath = App::path('View', Inflector::camelize($plugin));
		App::build(array('View' => $viewPath), App::APPEND);
		call_user_func_array(array($this, 'display'), $path);
	}
}
