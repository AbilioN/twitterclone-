<?php

namespace MF\Controller;

abstract class Action {

	protected $view;

	public function __construct() {
		$this->view = new \stdClass();
	}

	protected function render($view, $layout = 'layout') {
		$this->view->page = $view;
		// echo 'render';
		// echo $layout;


		if(file_exists("../App/Views/".$layout.".phtml")) {
			// echo ' if';
			// echo $layout;
			require_once "../App/Views/".$layout.".phtml";
		} else {
			// echo 'else';
			$this->content();
		}
	}

	protected function content() {
		$classAtual = get_class($this);

		$classAtual = str_replace('App\\Controllers\\', '', $classAtual);

		$classAtual = strtolower(str_replace('Controller', '', $classAtual));

		require_once "../App/Views/".$classAtual."/".$this->view->page.".phtml";
	}
}

?>