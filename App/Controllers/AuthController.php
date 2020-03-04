<?php 
namespace App\Controllers;

//os recursos do miniframework
use MF\Controller\Action;
use MF\Model\Container;

class AuthController extends Action {
    
    public function autenticar(){
        
        // echo '<pre>';
        // print_r($_POST);
        // echo '</pre>';
        
        $usuario = Container::getModel('Usuario');
        
        $usuario->__set('email',$_POST['email']);
        $usuario->__set('senha', md5($_POST['senha']));
        
        // print_r($usuario);
        
        // echo '<pre>';
        // print_r($usuario);
        // echo '</pre>';
        // $usuario->autenticar();
        $retorno = $usuario->autenticar();
        
        // echo '<pre>';
        // print_r($usuario);
        // echo '</pre>';
        if($usuario->__get('id') != '' && $usuario->__get('nome')){

            // passar os dados de usuario model para super global session
            session_start();
            $_SESSION['id'] = $usuario->__get('id');
            $_SESSION['nome'] = $usuario->__get('nome');

            header('location: /timeline');

        }else{
            // echo 'erro na autenticacao';
            header('location: /?login=erro');
        }
    }

    public function sair(){
        session_start();
        session_destroy();
        header('location: /');
    }
}

?>