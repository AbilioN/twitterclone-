<?php 

namespace App\Models;
use MF\Model\Model;

class Usuario extends Model{
    
    private $id;
    private $nome;
    private $email;
    private $senha;
    
    
    public function __get($atributo)
    {
        return $this->$atributo;
    }
    public function __set($atributo, $valor)
    {
        $this->$atributo =  $valor;
    }
    
    
    
    // salvar
    public function salvar(){
        $query = "insert into usuarios(nome,email,senha)values(:nome, :email,:senha)";
        $stmt = $this->db->prepare($query); //o objeto P DO pode ser extendido como $this->db porque eu extendi o model.
        
        
        $stmt->bindValue(':nome',$this->__get('nome'));
        $stmt->bindValue(':email',$this->__get('email'));
        $stmt->bindValue(':senha',$this->__get('senha')); 
        $stmt->execute();
        
        return $this; 
    }
    // validar se o cadastro pode ser feito
    public function validarCadastro(){
        $valido = true;
        if(strlen($this->__get('nome')) < 3){
            $valido = false;
        }
        if(strlen($this->__get('email')) < 3){
            $valido = false;
        }
        if(strlen($this->__get('senha')) < 3){
            $valido = false;
        }
        
        // print_r($valido);
        
        return $valido;
    }
    // recuperar o usuario por email
    
    public function getUsuarioPorEmail(){
        
        $query = "select nome, email from usuarios where email = :email";
        $stmt = $this->db->prepare($query);
        $stmt->bindValue(':email',$this->__get('email'));
        $stmt->execute();
        
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
        
    }
    
    public function autenticar(){
        // echo 'autenticando';
        // print_r($this->get__('email'));
        $query = "select id, nome, email from usuarios where email = :email and senha = :senha";
        $stmt = $this->db->prepare($query);
        // print_r($stmt);
        $stmt->bindValue(':email', $this->__get('email'));
        $stmt->bindValue(':senha', $this->__get('senha'));
        $stmt->execute();
        
        $usuario = $stmt->fetch(\PDO::FETCH_ASSOC); //apenas fetch pois trata-se de apenas um registro
        
        
        if($usuario['id'] != '' && $usuario['nome'] != ''){

            $this->__set('id',$usuario['id']);
            $this->__set('nome',$usuario['nome']);  
            
        }

        return $this;

           
    }

    function getAll(){
        $query = "select
             u.id, 
             u.nome, 
             u.email,
             (
                select
                    count(*)
                from
                    usuarios_seguidores as us
                where
                    us.id_usuario = :id_usuario and us.id_usuario_seguindo = u.id 

             ) as seguindo_sn
            from 
                usuarios as u
            where 
            u.nome like :nome and u.id != :id_usuario
            ";
        $stmt = $this->db->prepare($query);
        $stmt->bindValue(':nome' , '%'.$this->__get('nome').'%'); //pode ter qualquer coisa antes ou qualquer coisa depois.
        $stmt->bindValue(':id_usuario',$this->__get('id'));
        $stmt->execute();

        return $stmt->fetchAll(\PDO::FETCH_ASSOC);


    } 

    public function seguirUsuario($id_usuario_seguindo){
        // echo 'seguindo usuario';
        $query = "insert into usuarios_seguidores(id_usuario, id_usuario_seguindo)
            values(:id_usuario, :id_usuario_seguindo)";
        $stmt = $this->db->prepare($query);
        $stmt->bindValue(':id_usuario', $this->__get('id'));
        $stmt->bindValue(':id_usuario_seguindo', $id_usuario_seguindo);
        $stmt->execute();
        return true;

    }

    public function deixarSeguirUsuario($id_usuario_seguindo){

        $query = "DELETE from `usuarios_seguidores` where id_usuario = :id_usuario and 
        id_usuario_seguindo = :id_usuario_seguindo";
        $stmt = $this->db->prepare($query);
        
        // $this->__get('id')
        print_r($id_usuario_seguindo);
        $stmt->bindValue(':id_usuario', $this->__get('id'));
        $stmt->bindValue(':id_usuario_seguindo', $id_usuario_seguindo);
        $stmt->execute();
        return true;

    }

    // informações do usuario
    public function getInfoUsuario(){
        $query = "select nome from usuarios where id = id:usuario";
        $stmt = $this->db->prepare($query);
        $stmt->bindValue(':id_usuario', $this->__get('id'));
        $stmt->execute();

        return $stmt->fetch(\PDO::FETCH_ASSOC);


    }
    // total de tweets
    public function getTotalTweets(){
        $query = "select nome from usuarios where id = id:usuario";
        $stmt = $this->db->prepare($query);
        $stmt->bindValue(':id_usuario', $this->__get('id'));
        $stmt->execute();

        return $stmt->fetch(\PDO::FETCH_ASSOC);


    }
    // total de usuarios que estamos seguindo

    // total de seguidores
}

?>