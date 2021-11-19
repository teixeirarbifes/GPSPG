<?php
require_once('controller.php');
require_once(GPATH.'model'.S.'usuarios.php');
require_once(GPATH.'utils'.S.'validation.php');
require_once(GPATH.'sendmail'.S.'mail.php');

class UsuariosController extends Controller
{
 
    /**
     * Lista os contatos
     */
    public function listar()
    {       
        //ADMINISTRADOR 
        if(!$this->check_auth([4],true)){
            $home = new HomeController();
            return $home->home();
        }
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

        $conta = Usuarios::count();
        $pags = ceil($conta/$plim);
        if($ppag >$pags) $ppag  = $pags;

        $params = array();
        $params['pags'] = $pags;
        $params['pag'] = $ppag;
        $params['limit'] = $plim;

        $usuarios = Usuarios::all($num=$plim,$pag=$ppag,$orderby=$order);        
        if($usuarios)    
        for($i = 0;$i<count($usuarios);$i++){
            if($usuarios[$i]->role == "") $usuarios[$i]->role = "<font color=red>Sem função</font>";
        }
        return $this->view('grade_usuarios', ['data_table' => $usuarios,'params' => $params]); 
    }
 
    /**
     * Mostrar formulario para criar um novo contato
     */
    public function criar($dados)
    {
        if(!$this->check_auth([4],true)){
            $home = new HomeController();
            return $home->home();
        }

        $roles = Usuarios::all_roles();
        return $this->view('form_usuarios',['data_table' => $dados, 'roles' => $roles]);
    }

    public function registrar1()
    {
        
        if(UsuariosController::is_logged()){
            $this->msg('Você já está logado. Para novo registro, saia dos sistema.',1);
            $controller = new HomeController();
            return $controller->home();        
        }else{
            $roles = Usuarios::all_roles();
            return $this->view('form_registro');
        }
    }

    public function criar_senha($dados)
    {
        if(!UsuariosController::is_logged()){
            $this->msg('Você não está logado para criar senha.',1);
            $controller = new HomeController();
            return $controller->home();    
        }
        $usuario = UsuariosController::get_usuario();
        return $this->view('form_criar_senha',['data_table' => $dados,'usuario' => $usuario]);
    }

    public function form_ativar($dados)
    {
        return $this->view('form_ativar',['data_table' => $dados]);
    }

    public function envio_email_senha($dados){
        $mensagem = "body";      
        $to_email = $dados['txt_email'];
        $to_nome = $dados['txt_nome'];
        $modelo = 'senha';
        $data = ['senha' => $dados['txt_senha']];
        $status = criar_email($to_email,$to_nome,'',$modelo,$data);
        $email = $dados['txt_email'];
        if($status==2){            
            $this->msg('Um e-mail com a nova senha foi enviado para o e-mail "'.$email.'".',0);
        }else{
            $this->msg('Um e-mail com a nova senha será enviado para o e-mail "'.$email.'".',0);
        }
        return $this->editar($dados);
    }
    
    
    public function envio_email_validacao($dados,$oculta_email = true,$changeemail = false){
        $mensagem = "body";      
        
        $to_nome = $dados['txt_nome'];
        if($changeemail){
            $to_email = $dados['txt_email2'];
            $modelo = 'changeemail';
        }else{
            $to_email = $dados['txt_email'];
            if($dados['bl_bloqueado'])
                $modelo = 'ativacao';
            else
                $modelo = 'recuperacao';
        }
        $data = ['key' => $dados['chave'],'txt_chave2' => isset($dados['txt_chave2']) ? $dados['txt_chave2'] : "" ];
        $status = criar_email($to_email,$to_nome,'',$modelo,$data);
        if($oculta_email){
            $email = $dados['txt_email'];
        }else{
            $email = $dados['txt_email'];
        }
        if($status==2){        
            if($changeemail)    
                $this->msg('Um e-mail de validação já foi enviado para o email "'.$email.'" alterado. Siga as instruções!',0);
            else
                $this->msg('Um e-mail de validação já foi enviado para "'.$email.'". Siga as instruções!',0);
        }else{
            if($changeemail)    
                $this->msg('Um e-mail de validação será enviado para o email "'.$email.'" alterado em até 1 hora. Aguarde e siga as instruções!',0);        
            else
                $this->msg('Um e-mail de validação será enviado para o "'.$email.'" em até 1 hora. Aguarde e siga as instruções!',0);        
        }
        return true;
    }
    
    function getRandomString($n) {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $randomString = '';
    
        for ($i = 0; $i < $n; $i++) {
            $index = rand(0, strlen($characters) - 1);
            $randomString .= $characters[$index];
        }
    
        return $randomString;
    }

    public function ativar_conta($dados){
        if(Validation_Classe::validation_ativar($dados)==null){
            $usuario = Usuarios::find_by_email($dados['txt_email']);        
            if(isset($usuario->id_user)){
                if($usuario->txt_chave == hash('sha256',$dados['key'])){
                    $primeiroacesso = false;
                    if($usuario->bl_bloqueado == true){
                        $primeiroacesso = true;
                    }
                    $usuario->bl_bloqueado = false;
                    $usuario->txt_chave = 'nothing';
                    $usuario->txt_senha = hash('sha256',$dados['txt_senha']);
                    if($usuario->save($dados)){
                        $this->msg('Sua conta foi validada com sucesso e a senha definida.',0);                        
                        $this->process_login($usuario->txt_usuario,$dados['txt_senha']);
                        return "reload";
                        /*if($primeiroacesso)
                        return $this->welcome($dados);
                        else
                        return $this->form_login($dados);*/
                    }else{
                        $this->msg('Erro ao tentar salvar dados de usuário.',2);
                        return $this->form_ativar($dados);
                    }
                }else{
                    $this->msg('E-mail ou código informado inválido2!',2);
                    return $this->form_ativar($dados);    
                }
            }else{
                $this->msg('E-mail ou código informado inválido!',2);
                return $this->form_ativar($dados);
            }
        }else{
            $this->msg('Inconsistência de dados. Não é possível ativar.',2);
            return $this->form_ativar($dados);
        }
    }

    public function alterar_senha($dados){  
        if(!UsuariosController::is_logged()){
            $this->msg('Você não está logado para alterar sua senha.',1);
            $controller = new HomeController();
            return $controller->home();    
        }     
        return $this->view('form_criar_senha');  
    }

    public function form_perfil($dados){       
        if(UsuariosController::is_logged()){
            $usuario = Usuarios::find(UsuariosController::get_usuario()['id_user']);
            if(isset($usuario->id_user)){                
                return $this->view('form_perfil',['data_table' => $dados,'usuario' => $usuario]);
            }else{
                $this->msg('Ocorreu um erro ao tentar encontrar usuário.',2);            
                $home = new HomeController();
                $home->home();
            }
        }else{
            $this->msg('Você não está logado para alterar o perfil. Faça seu login primeiro.',2);            
            return $this->form_login($dados);
        }
    }

    public function alterar_perfil($dados){
        if(UsuariosController::is_logged()){
            $usuario = Usuarios::find(UsuariosController::get_usuario()['id_user'],false);            
            if(Validation_Classe::validation_perfil($dados)==null){
                $usuario->txt_nome = $dados['txt_nome'];
                $alteracao_email = false;   
                $chave=false;             
                    if($usuario->txt_email != $dados['txt_email']){                    
                        $key = isset($dados['txt_chave2']) ? $dados['txt_chave2'] : "";
                        $hash = hash('sha256',$key);
                        if($usuario->txt_chave2 == $hash){
                            $alteracao_email = false;
                            $usuario->txt_email = $usuario->txt_email2;
                            $alteracao_email = false;                            
                            $usuario->txt_email2 = "";
                            $usuario->txt_chave = '1';
                            $dados['txt_email2'] = "";
                            $dados['txt_chave2'] = '1';    
                            $chave=true;
                        }else if($key!=""){
                            $this->msg('Código de validação inválido para alteração do e-mail.',2); 
                            return $this->view('form_perfil',['data_table' => $dados,'usuario' => $usuario]);
                        }else{
                            $usuario->txt_email2 = $dados['txt_email'];   
                            $key = $this->getRandomString(10);
                            $usuario->txt_chave2 = hash('sha256',$key);
                            $dados['txt_chave2'] = $key;                
                            $dados['txt_email2'] = $dados['txt_email']; 
                            $alteracao_email = true;
                        }
                    }else{
                        $usuario->txt_email2 = "";
                        $usuario->txt_chave = '1';
                        $dados['txt_email2'] = "";
                        $dados['txt_chave2'] = '1';    
                    }
                if($usuario->save($dados)){
                    if($alteracao_email){
                        $dados['chave'] = $key;
                        $this->envio_email_validacao($dados,false,true);
                    }else if($chave){
                        $this->msg('Registro salva e e-mail alterado com êxito.',0);                             
                    }else{
                        $this->msg('Registro salvo com sucesso.',0);                             
                    }

                    return $this->view('form_perfil',['data_table' => $dados,'usuario' => $usuario]);           
                }else{
                    $this->msg('Não foi possível salvar o registro.',2);            
                    return $this->view('form_perfil',['data_table' => $dados,'usuario' => $usuario]);
                }
            }else{
                $this->msg('Não foi possível salvar o registro por incosistências.',2);            
                return $this->view('form_perfil',['data_table' => $dados,'usuario' => $usuario]);
            }                    
        }
    }


    public function save_alterar_senha($dados){
        if(!UsuariosController::is_logged()){
            $this->msg('Você não está logado para alterar sua senha.',1);
            $controller = new HomeController();
            return $controller->home();    
        }        
        if(UsuariosController::is_logged()){
            if(Validation_Classe::validation_senha($dados)==null){
               $usuario = Usuarios::find(UsuariosController::get_usuario()['id_user'],false);
                if(isset($usuario->id_user)){
                    if($usuario->id_user>0){
                        if($dados['txt_senha']==$dados['txt_atual']){
                        $this->msg('A senha atual e nova são identicas. Digite uma nova senha direrente!',2);            
                        return $this->view('form_criar_senha');          
                        }else if($usuario->txt_senha == hash('sha256',$dados['txt_atual'])){
                            $usuario->txt_senha = hash('sha256',$dados['txt_senha']);      
                            $usuario->bl_force_change = false;                  
                            if($usuario->save()){                            
                                $this->msg('Senha alterada com êxito! Use-a no próximo acesso.',0);            
                                $usuario->save($dados);
                                return $this->form_perfil($dados);
                            }else{
                                $this->msg('Não foi possível salvar a senha.',2);                                            
                                return $this->view('form_criar_senha');  
                            }
                        }else{
                            $this->msg('Senha atual não está correta.',0);            
                            return $this->view('form_criar_senha');          
                        }
                    }else{
                        $this->msg('Não foi possível alterar senha.',2);            
                        return $this->view('form_criar_senha');            
                    }
                }else{
                    $this->msg('Não foi possível alterar senha.',2);            
                    return $this->view('form_criar_senha');        
                }
            }else{
                $this->msg('Não foi possível alterar a senha por incosistências.',2);            
                return $this->view('form_criar_senha');
            }
        }else{
            $this->msg('Você não está logado para alterar a senha.',2);            
            return $this->view('form_login');
        }
    }


    public function salvar_registro1($dados){
        if(UsuariosController::is_logged()){
            $this->msg('Você já está logado. Saia do sistema para criar novo registro.',1);
            $controller = new HomeController();
            return $controller->home();    
        }
        $usuario           = new Usuarios;
        $array = explode(' ',$dados['txt_nome']);
        $nome = "";
        foreach($array as $a){
            if($a!="") $nome .= $a;
        }
        $usuario->txt_nome     = $dados['txt_nome'];
        $usuario->txt_usuario = $dados['txt_usuario'];
        $usuario->txt_email    = $dados['txt_email'];
        $usuario->txt_cpf    = $dados['txt_cpf'];
        $usuario->id_role = 1;
        $key = $this->getRandomString(10);
        $usuario->txt_chave = hash('sha256',$key);
        $usuario->bl_bloqueado = true ;
        $usuario->txt_senha    = "0";
        $dados['chave'] =  $key;

        $data = array();
        $data['id_user'] = 0;
        $data['txt_nome'] = $usuario->txt_nome;
        $data['txt_usuario'] = $usuario->txt_usuario;
        $data['txt_email'] = $usuario->txt_email;
        $data['txt_cpf'] = $usuario->txt_cpf;

        if(Validation_Classe::validation_registro1($data)==null){
            if($usuario->save($dados)){
                //$this->msg('Salvo com sucesso',0);
                $id = Usuarios::find_by_user($data['txt_usuario']);
                $dados['id_user'] = $id->id_user;
                $dados['bl_bloqueado'] = $id->bl_bloqueado;
                if($this->envio_email_validacao($dados)){
                    return $this->form_ativar($dados);
                }else{
                    $this->msg('Ocorreu um problema ao tentar enviar e-mail.',2); 
                }
                
            }else{
                $this->msg('Ocorreu um erro ao tentar salvar usuário',2);
                return $this->registrar1($dados);
            }
        }else{                
            $this->msg('Não foi possível salvar o registro por incosistências.',2);            
            return $this->registrar1($dados);
        }        
    }

    public function form_recuperar()
    {
        //return "teste";
        return $this->view('form_recuperar_senha');
    }
 

    public function reenviar_codigo($dados){
        if(UsuariosController::is_logged()){
            $this->msg('Você já está logado.',1);
            $controller = new HomeController();
            return $controller->home();    
        }
        $usuario  = Usuarios::find_by_user($dados['txt_usuario']);

        if(!isset($usuario->id_user)){
            $this->msg('Usuário não identificado.Usuário',1);
            $controller = new HomeController();
            return $controller->home();    
        }        
        $key = $this->getRandomString(10);
        $usuario->txt_chave = hash('sha256',$key);
        $dados['chave'] =  $key;
        $dados['txt_nome'] = $usuario->txt_nome;
        $dados['txt_usuario'] = $usuario->txt_usuario;
        $dados['txt_email'] = $usuario->txt_email;
        $dados['txt_cpf'] = $usuario->txt_cpf;
        $dados['id_user'] = $usuario->id_user;
        if($usuario->save($dados)){            
                $dados['bl_bloqueado'] = $usuario->bl_bloqueado;    
                if($this->envio_email_validacao($dados)){
                    return $this->form_ativar($dados);
                }else{
                    $this->msg('Ocorreu um problema ao tentar enviar e-mail.',2); 
                }
        }else{
            $this->msg('Ocorreu um erro ao tentar salvar usuário',2);
            return $this->registrar1($dados);
        }        
    }

    public function form_login()
    {
        //return "teste";
        return $this->view('form_login');
    }
 
    /**
     * Mostrar formulário para editar um contato
     */
    public function editar($dados)
    {
        if(!$this->check_auth([4],true)){
            return $this->listar();
        }

        $id_user      = (int) $dados['id_user'];
        $usuario = Usuarios::find($id_user);

        if(isset($usuario->id_user)){
            $roles = Usuarios::all_roles();
            return $this->view('form_usuarios', ['data_table' => $dados, 'usuario' => $usuario, 'roles' => $roles]);
        }else{
            $this->msg('Usuário id#'.$id_user.' não encontrado.',1);
            return $this->listar($dados);
        }
    }
 

    /**
     * Salvar o contato submetido pelo formulário
     */
    public function salvar($dados)
    {
        if(!$this->check_auth([4],true)){
            return $this->listar();
        }

        $usuario           = new Usuarios;
        $usuario->txt_nome     = $dados['txt_nome'];
        $usuario->txt_usuario = $dados['txt_usuario'];
        $usuario->txt_email    = $dados['txt_email'];
        $usuario->txt_cpf = $dados['txt_cpf'];
        $usuario->id_role = $dados['id_role'];
        $usuario->txt_chave = "";
        $usuario->bl_bloqueado = true;
        $senha = utf8_decode($dados['txt_senha']);
        $usuario->txt_senha    = hash('sha256',$senha);
        $usuario->bl_force_change = true;

        $data = array();
        $data['id_user'] = 0;
        $data['txt_nome'] = $usuario->txt_nome;
        $data['txt_usuario'] = $usuario->txt_usuario;
        $data['txt_cpf'] = $usuario->txt_cpf;
        $data['txt_email'] = $usuario->txt_email;
        $data['txt_senha'] = $senha;

        if(Validation_Classe::validation_usuario($data)==null){
            if($usuario->save($dados)){
                $this->msg('Salvo com sucesso',0);
                $this->envio_email_senha($dados);
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
        if(!$this->check_auth([4],true)){
            return $this->listar();
        }

        if($this->request->salvar == 1){
            $id_user           = (int) $dados['id_user'];
            $usuario           = Usuarios::find($id_user,false);
            $usuario->txt_nome = $dados['txt_nome'];
            $usuario->txt_usuario = $dados['txt_usuario'];
            $usuario->txt_email = $dados['txt_email'];
            $usuario->txt_cpf = $dados['txt_cpf'];
            $usuario->id_role   = $dados['id_role'];
            $senha = "";
            if($this->request->txt_senha!=""){
                $senha = utf8_decode($dados['txt_senha']);
                $usuario->txt_senha    = hash('sha256',$senha);
                $usuario->bl_force_change = true;
            }

            $data = array();
            $data['id_user'] = $usuario->id_user;
            $data['txt_nome'] = $usuario->txt_nome;
            $data['txt_usuario'] = $usuario->txt_usuario;
            $data['txt_email'] = $usuario->txt_email;
            $data['txt_cpf'] = $usuario->txt_cpf;
            $data['txt_senha'] = $senha;
            $data['id_user'] = $this->request->id_user;
            
            if(Validation_Classe::validation_usuario($data)==null){
                if($usuario->save($dados)){
                    $this->msg('Salvo com sucesso',0);
                    if($senha!=""){
                        $this->envio_email_senha($dados);
                    }
                }else{
                    $this->msg('Ocorreu um erro ao tentar salvar usuário',2);
                }
                
                return $this->editar($dados);
            }else{                
                $this->msg('Não foi possível salvar o registro por incosistências.',2);
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
        if(!$this->check_auth([4],true)){
            return $this->editar($dados);
        }

        if($dados['excluir']==1){
            $id      = (int) $dados['id'];
            $usuario = Usuarios::destroy($id);
            if($usuario){
                $this->msg('Excluído com sucesso',0);
                return $this->listar();
            }else   
                $this->msg('Não foi possível excluir.',1);
        }else{
            $this->msg('Não foi possível excluir.',1);
        }
        return $this->editar($dados);
    }


    public function welcome($dados){
        return $this->view('view_welcome',$dados);
    }

    public function process_login($user,$pass){
        if($user==""){
            $this->msg('Você precisa digitar o seu usuário e senha. Como usuário, pode ser o cpf ou e-mail.',1);                            
            return false;        
        }else if($pass==""){
            $this->msg('Você precisa digitar o seu usuário e senha. Como usuário, pode ser o cpf ou e-mail.',1);                            
            return false;        
        }
        $usuario = Usuarios::find_by_user($user);
        if($usuario){
            if($usuario->bl_bloqueado){
                $_SESSION['usuario'] = NULL;
                $this->msg('Este usuário se encontra bloqueado.',0);            
                return false;
            }else if(hash('sha256',$pass)==$usuario->txt_senha){               
                $_SESSION['usuario'] = array();
                $_SESSION['usuario']['id_user'] = $usuario->id_user;
                $_SESSION['usuario']['txt_nome'] = $usuario->txt_nome;
                $_SESSION['usuario']['id_usuario'] = $usuario->id_user;
                $_SESSION['usuario']['txt_cpf'] = $usuario->txt_cpf;
                $_SESSION['usuario']['txt_email'] = $usuario->txt_email;
                $_SESSION['usuario']['id_role'] = $usuario->id_role;
                $_SESSION['usuario']['dt_ultimoacesso'] = $usuario->dt_ultimoacesso;

                if($usuario->dt_ultimoacesso == 0)
                    $_SESSION['usuario']['primeiroacesso'] = 1;
                else
                    $_SESSION['usuario']['primeiroacesso'] = 0;
                $usuario->dt_ultimoacesso = date('Y-m-d H-i-s');
                $usuario->save();
                return true;
            }else{
                $_SESSION['usuario'] = NULL;
                $this->msg('O seu usuário ou senha informados estão inválidos!',2);                            
                return false;
            }
        }else{
            $this->msg('O seu usuário ou senha informados estão inválidos!',2);                            
            return false;        
        }
        return false;
    }

    public function login($dados){
        if($this->request->logando == 1){
            if($this->process_login($this->request->txt_usuario,$this->request->txt_senha)){
                $usuario = Usuarios::find(UsuariosController::get_usuario()['id_user']);
                if(isset($usuario->id_user)){
                    $this->msg($usuario->txt_nome.', seja bem vindo do GPS-GP.',0);  
                    return "reload";
                }else{
                    return $this->view('form_login',$dados);    
                }                
            }else{                
                return $this->view('form_login',$dados);
            }
        }else{
            return $this->view('form_login',$dados);
        }
    }

    public static function is_logged(){
        if(isset($_SESSION['usuario']))
            return ($_SESSION['usuario'] != NULL);
        
        return false;
    }

    public static function get_usuario(){
        return $_SESSION['usuario'];
    }

    public static function primeiroacesso(){
        if(isset($_SESSION['usuario']))
        if(isset($_SESSION['usuario']['primeiroacesso']))
        if($_SESSION['usuario']['primeiroacesso'] == 1){
            $_SESSION['usuario']['primeiroacesso'] = 0;
            return true;
        }
        return false;
    }

    public function sair(){
        $_SESSION['usuario'] = NULL;
        $this->msg('Você saiu do sistema. Te aguardamos em breve!',0);   
        return "reload";
    }

    public static function get_role(){
        if(UsuariosController::is_logged()){
            return UsuariosController::get_usuario()['id_role'];
        }else{
            return -1;
        }
    }

 
}
?>