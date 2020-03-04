<?php

namespace App\Controllers;

//os recursos do miniframework
use MF\Controller\Action;
use MF\Model\Container;

class AppController extends Action {

    public function timeline(){

        $this->validarAutenticacao();
            // criando a instancia de objeto model com db configurado
            $tweet = Container::getModel('Tweet'); 
            //recuperação dos tweets no banco de dados
            $tweet->__set('id_usuario',$_SESSION['id']);
            $tweets = $tweet->getAll();
            $this->view->tweets = $tweets;
            $this->render('timeline'); 
    }

    public function tweet(){
        
        $this->validarAutenticacao();
            // recuperando objeto de modelo com db configurado
            $tweet = Container::getModel('Tweet');

            $tweet->__set('tweet', $_POST['tweet']);
            $tweet->__set('id_usuario', $_SESSION['id']);
            
            $tweet->salvar();
            header('location:/timeline');


            // echo 'chegamos no tweet'; 
            // print_r($_SESSION);
       

        
    }

    public function validarAutenticacao(){

        session_start();


        if(!isset($_SESSION['id']) || $_SESSION['id'] == '' || !isset($_SESSION['nome']) || $_SESSION['nome'] == '' ) {
            header('Location: /?login=erro');
        } 

    }

    public function quemSeguir(){
        $this->validarAutenticacao(); 

        $pesquisarPor = isset($_GET['pesquisarPor']) ? $_GET['pesquisarPor'] : '';
        //se $GET está setado então $pesquisarPor recebe $_GET['pesquisarPor']
        // print_r($_GET['pesquisarPor']);
        
        // echo 'Pesquisando por: '.$pesquisarPor;
       
        $usuarios = array(); 
        // echo '<br /><br /><br /><br /><br /> <pre>';
        // print_r($_SESSION['id']);
        
        echo '</pre>';
        if($pesquisarPor != ''){
            $usuario = Container::getModel('Usuario');
            $usuario->__set('nome',$pesquisarPor);  
            $usuario->__set('id',$_SESSION['id']);    
            $usuarios= $usuario->getAll();

            // echo '<pre>';
            // print_r($usuarios);
            // echo '</pre>';


        }

        $this->view->usuarios = $usuarios;
        $this->render('quemSeguir');
    }

    public function acao(){
        $this->validarAutenticacao();
        // echo '<pre>';
        // print_r($_GET);
        // echo '</pre>';

        $acao = isset($_GET['acao'])? $_GET['acao']:'';
        $id_usuario_seguindo = isset($_GET['id_usuario'])? $_GET['id_usuario']:''; //id_usuario = id_usuario_seguindo

        $usuario = Container::getModel('Usuario');//futuramente emitir outra classe para id usuario, usando usuario provisoriamente.

        $usuario->__set('id', $_SESSION['id']);

        // as funções abaixo também devem ser migradas para a nova classe.
        if($acao == 'seguir'){
            $usuario->seguirUsuario($id_usuario_seguindo);

        }else if($acao == 'deixar_de_seguir'){
            $usuario->deixarSeguirUsuario($id_usuario_seguindo);

        }

        header('Location: /quem_seguir'); 

    }

}


?>

