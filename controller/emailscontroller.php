<?php
require_once('controller.php');
require_once(GPATH.'model'.S.'emails.php');
require_once(GPATH.'utils'.S.'validation.php');
require_once(GPATH.'sendmail'.S.'mail.php');


class EmailsController extends Controller
{
 
    /**
     * Lista os contatos
     */
    public function listar()
    {        
        $order = $this->request->orderby;
        $ppag = (integer)$this->request->pag;
        $plim = (integer)$this->request->num;

        if($plim>=1000) $plim = 1000;
        else if($plim>=500) $plim = 500;
        else if($plim>=200) $plim = 200;
        else if($plim>=100) $plim = 100;
        else if($plim>=50) $plim = 50;
        else if($plim>=20) $plim = 20;
        else if($plim>=10) $plim = 10;
        else $plim = 5;

        if(!is_integer($ppag)) $ppag = 1;
        else if($ppag < 1) $ppag = 1;
        if(!is_integer($plim)) $plim = 5;
        else if($plim < 1) $plim = 1;

        $conta = Emails::count();
        $pags = ceil($conta/$plim);
        if($ppag >$pags) $ppag  = $pags;

        $params = array();
        $params['pags'] = $pags;
        $params['pag'] = $ppag;
        $params['limit'] = $plim;

        $emails = Emails::all($num=$plim,$pag=$ppag,$orderby=$order);        
        return $this->view('grade_emails', ['data_table' => $emails,'params' => $params]); 
    }
 
    /**
     * Mostrar formulário para editar um contato
     */
    public function editar($dados)
    {
        $id_email      = (int) $dados['id_email'];
        $email = Emails::find($id_email);

        if(isset($email->id_email)){
            //$roles = Usuarios::all_roles();
            return $this->view('form_emails', ['data_table' => $dados, 'email' => $email]);
        }else{
            $this->msg('Email id#'.$id_email.' não encontrado.',1);
            return $this->listar($dados);
        }
    }
 

    /**
     * Salvar o contato submetido pelo formulário
     */
    public function salvar($dados)
    {
        $usuario           = new Usuarios;
        $usuario->txt_nome     = $dados['txt_nome'];
        $usuario->txt_usuario = $dados['txt_usuario'];
        $usuario->txt_email    = $dados['txt_email'];
        $usuario->txt_chave = "";
        $usuario->bl_bloqueado = true;
        $senha = utf8_decode($dados['txt_senha']);
        $usuario->txt_senha    = hash('sha256',$senha);

        $data = array();
        $data['id_user'] = 0;
        $data['txt_nome'] = $usuario->txt_nome;
        $data['txt_usuario'] = $usuario->txt_usuario;
        $data['txt_email'] = $usuario->txt_email;
        $data['txt_senha'] = $senha;

        if(Validation_Classe::validation_usuario($data)==null){
            if($usuario->save($dados)){
                $this->msg('Salvo com sucesso',0);
            }else{
                $this->msg('Ocorreu um erro ao tentar salvar usuário',2);
            }
            $id = Usuarios::find_by_user($data['txt_usuario']);
            $dados['id_user'] = $id->id_user;
            return $this->editar($dados);
        }else{                
            $this->msg('Não foi possível salvar o registro por incosistências.',2);
            
            return $this->listar($dados);
        }
    }
 
    /**
     * Atualizar o contato conforme dados submetidos
     */
    public function atualizar($dados)
    {      
        if($this->request->salvar == 1){
            $id_user           = (int) $dados['id_user'];
            $usuario           = Usuarios::find($id_user,false);
            $usuario->txt_nome = $dados['txt_nome'];
            $usuario->txt_usuario = $dados['txt_usuario'];
            $usuario->txt_email = $dados['txt_email'];
            $senha = "";
            if($this->request->txt_senha!=""){
                $senha = utf8_decode($dados['txt_senha']);
                $usuario->txt_senha    = hash('sha256',$senha);
            }

            $data = array();
            $data['id_user'] = $usuario->id_user;
            $data['txt_nome'] = $usuario->txt_nome;
            $data['txt_usuario'] = $usuario->txt_usuario;
            $data['txt_email'] = $usuario->txt_email;
            $data['txt_senha'] = $senha;
            $data['id_user'] = $this->request->id_user;
            
            if(Validation_Classe::validation_usuario($data)==null){
                if($usuario->save($dados)){
                    $this->msg('Salvo com sucesso',0);
                }else{
                    $this->msg('Ocorreu um erro ao tentar salvar usuário',2);
                }
                
                return $this->editar($dados);
            }else{                
                $this->msg('Não foi possível salvar o registro por incosistências.',2);
                return $this->editar($dados);
            }
        }else{
            return $this->listar();
        }
    }

    
    /**
     * Apagar um contato conforme o id informado
     */
    public function excluir($dados)
    {
        if($dados['excluir']==1){
            $id      = (int) $dados['id'];
            $email = Emails::destroy($id);
            if($email){
                $this->msg('Registro excluído com sucesso',0);
                return $this->listar();
            }else   
                $this->msg('Não foi possível excluir.',1);
        }else{
            $this->msg('Não foi possível excluir.',1);
        }
        return $this->editar($dados);
    }    

    public function pendentes($dados){
        enviar_emails();
    }
 
}
?>