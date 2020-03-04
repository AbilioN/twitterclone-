<?php

namespace App\Controllers;

//os recursos do miniframework
use MF\Controller\Action;
use MF\Model\Container;

class IndexController extends Action {
	
	public function index() {

		$this->view->login = isset($_GET['login']) ? $_GET['login'] : '';
		
		
		$this->render('index');

		
	}
	
	public function inscreverse(){
		$this->view->erroCadastro = false;

		$this->view->usuario = Array(
			'nome' => '',
			'email' => '',
			'senha' => '',  
		);

		$this->render('inscreverse'); 
	}
	function registrar(){
		
		
		// receber dados de um formulario.
		echo '<pre>'; 	
		// print_r($_POST);
		echo '</pre>';
		// criar modelo de ususario e popular com os dados.
		$usuario = Container::getModel('Usuario');

		$usuario->__set('nome', $_POST['nome']);
		$usuario->__set('email',$_POST['email']);
		$usuario->__set('senha', md5($_POST['senha']));
		// print_r($usuario);
		
		if($usuario->validarCadastro() 	&& count($usuario->getUsuarioPorEmail()) == 0){
			// print_r($usuario->getUsuarioPorEmail());
			// print_r(count($usuario->getUsuarioPorEmail()));
			
				$usuario->salvar();
				// echo 'validou email';
				$this->render('cadastro'); 
			}else{

				$this->view->usuario = Array(
					'nome' => $_POST['nome'],
					'email' => $_POST['email'],
					'senha' => $_POST['senha'],
				);
				$this->view->erroCadastro = True;
				$this->render('inscreverse');
			}
			
			
		
		
		
		
		// echo '<pre>';
		// print_r($usuario);
		// echo '</pre>';
		
	}	
	
	
}


?>