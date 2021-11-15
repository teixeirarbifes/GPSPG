<?php
use PHPWee\CssCompressExpressionValuesMinifierPlugin;
require_once('controller.php');
require_once(GPATH.'model'.S.'usuarios.php');
require_once(GPATH.'controller'.S.'fichacontroller.php');
require_once(GPATH.'model'.S.'documentos.php');
require_once(GPATH.'utils'.S.'validation.php');
require_once(GPATH.'sendmail'.S.'mail.php');

class DocumentosController extends Controller
{
 
    /**
     * Lista os contatos
     */
    public function listar()
    {       
        //ADMINISTRADOR 
        if(!$this->check_auth([1],true)){
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

        $conta = Documentos::count();
        $pags = ceil($conta/$plim);
        if($ppag >$pags) $ppag  = $pags;

        $params = array();
        $params['pags'] = $pags;
        $params['pag'] = $ppag;
        $params['limit'] = $plim;

        $documentos = Documentos::all($num=$plim,$pag=$ppag,$orderby=$order);        
        //if($usuarios)    
        /*for($i = 0;$i<count($usuarios);$i++){
            if($usuarios[$i]->role == "") $usuarios[$i]->role = "<font color=red>Sem função</font>";
        }*/
        return $this->view('documentos'.S.'grade_documentos', ['data_table' => $documentos,'params' => $params]); 
    }

    public function download($dados){
        if(!UsuariosController::is_logged()){
            $this->msg('Você não está autorizado para acessar essa ficha',2);
            $home = new HomeController();
            return $home->home();
        /*}else if(!$this->check_auth([1],true)){
            return $this->listar();*/
        }

        $id_ficha = (int) $dados['id_ficha'];
        $ficha = Ficha::find($id_ficha);       
        $controllerficha = new FichaController();
        
        if(isset($ficha->id_ficha)){
            $inscricao = Inscricao::find($ficha->id_inscricao);         
            if(isset($inscricao->id_inscricao)){
                if($this->check_auth([1],false) && $inscricao->id_user != UsuariosController::get_usuario()['id_user']){
                    $this->msg('Não autorizado para gerenciar os documentos da Ficha id#'.$id_ficha.' com o seu usuário.',2);
                }else{
                    $processo = Processos::find($inscricao->id_processo);         
                    if(isset($processo->id_processo)){   
                        $doc = null;
                        if(isset($dados['id_doc'])) $doc = Documentos::find($dados['id_doc'],true,$id_ficha);
                        if(!isset($doc->id_ficha) || $doc->id_ficha != $id_ficha){
                            $this->msg('Ficha id#'.$id_ficha.' não associada ao documento',1);
                            return $this->listar_ficha($dados);                              
                        }else{
                            $classe = Documentos::find_classe($doc->id_classe);                            
                            $file=$doc->txt_location;
                            $name=str_replace(' ','_',$classe->txt_classe).'_'.$doc->id_doc.'_'.str_replace(' ','_',$doc->txt_filename);
                            header('Content-type: application/pdf');
                            header('Content-Disposition: attachment; filename="'.$name.'"');
                            readfile($file);
                            exit();
                        }
                    }else{
                        $this->msg('Ficha id#'.$id_ficha.' não associada a um processo.',1);
                        return $this->listar_ficha($dados); 
                    }
                }
            }else{
                $this->msg('Ficha id#'.$id_ficha.' não associada a uma inscrição.',1);
                return $this->listar_ficha($dados); 
            }
        }else{
            $this->msg('Ficha id#'.$id_ficha.' não encontrada.',1);
            return $this->listar_ficha($dados);
        }      

    }

    public function upload($dados){      
            if(!UsuariosController::is_logged()){
                $this->msg('Você não está autorizado para acessar essa ficha',2);
                $home = new HomeController();
                return $home->home();
            /*}else if(!$this->check_auth([1],true)){
                return $this->listar();*/
            }
    
            $id_ficha = (int) $dados['id_ficha'];
            $ficha = Ficha::find($id_ficha);       
    
            $controllerficha = new FichaController();
    
    
            if(isset($ficha->id_ficha)){
                $inscricao = Inscricao::find($ficha->id_inscricao);         
                if(isset($inscricao->id_inscricao)){
                    if($this->check_auth([1],false) && $inscricao->id_user != UsuariosController::get_usuario()['id_user']){
                        $this->msg('Não autorizado para gerenciar os documentos da Ficha id#'.$id_ficha.' com o seu usuário.',2);
                    }else{
                        $processo = Processos::find($inscricao->id_processo);         
                        if(isset($processo->id_processo)){   
                            $classe = Documentos::find_classe($dados['id_classe']);
                            if($dados["tipo"]!=1 && $dados["tipo"]!=2){
                                $this->msg("ERRO: Categoria inexistente.",2);
                                $control = new HomeController();
                                return $control->home();
                            }else if($classe->id_categoria != $dados["tipo"]){
                                $this->msg("Este tipo de arquivo não é do tipo definido.",2);
                                return $dados['tipo']==1 ? $this->listar_ficha($dados) : $this->listar_curriculo($dados);
                            }else if(Documentos::count_classe($dados['id_classe'],$ficha->id_ficha)>=$classe->num_docs){
                                $this->msg("Não é pemitido carregar mais que ".$classe->num_docs." documento(s) PDF para esse tipo.",2);
                                return $dados['tipo']==1 ? $this->listar_ficha($dados) : $this->listar_curriculo($dados);
                            }else if(!isset($_FILES['txt_filename']) || $_FILES['txt_filename']['tmp_name']==''){
                                $this->msg('O arquivo precisa ser selecionado para ser carregado.',2); 
                                return $dados['tipo']==1 ? $this->listar_ficha($dados) : $this->listar_curriculo($dados);
                            }else if($_FILES['txt_filename']['error']!=UPLOAD_ERR_OK){
                                if($_FILES['txt_filename']['error']==UPLOAD_ERR_CANT_WRITE){
                                    $this->msg('Ocorreu um erro ao salvar o arquivo.',2); 
                                }else if($_FILES['txt_filename']['error']==UPLOAD_ERR_EXTENSION){
                                    $this->msg('A extensão do arquivo não é permitida.tar',2); 
                                }else if($_FILES['txt_filename']['error']==UPLOAD_ERR_NO_FILE){
                                    $this->msg('O arquivo não foi selecionado',2); 
                                }else if($_FILES['txt_filename']['error']==UPLOAD_ERR_INI_SIZE){
                                    $this->msg('O arquivo excede o tamanho máximo de upload',2); 
                                }else{
                                    $this->msg('Ocorreu um erro ao salvar o arquivo.',2); 
                                }
                                return $dados['tipo']==1 ? $this->listar_ficha($dados) : $this->listar_curriculo($dados);
                            }else{
                                $doc = new Documentos();
                                $doc->id_ficha = $ficha->id_ficha;
                                $doc->id_user = UsuariosController::get_usuario()['id_user'];
                                $doc->txt_filename = $_FILES['txt_filename']['name'];
                                $doc->id_classe = $dados['id_classe'];
                                $dir = str_replace('GPSPG','',str_replace('gpspg','',$_SERVER['DOCUMENT_ROOT'])).'upload'.S.'user_'.UsuariosController::get_usuario()['id_user'];
                                if (!file_exists($dir.S)) {
                                    mkdir($dir, 0777);
                                }
                                if($doc->save()){
                                    $doc->txt_location = $dir.S.str_replace(' ','_',$classe->id_classe).'_doc_'.$doc->id_doc.'.pdf';
                                    move_uploaded_file($_FILES['txt_filename']['tmp_name'],$doc->txt_location);
                                    if($doc->save()){
                                        $this->msg('O arquivo foi carregado com êxito!',0); 
                                    }else{
                                        $this->msg('Houve um problema no banco de dados.',0); 
                                    }
                                }else{
                                    $this->msg('Ocorreu um erro ao salvar o arquivo',2); 
                                }
                                return $dados['tipo']==1 ? $this->listar_ficha($dados) : $this->listar_curriculo($dados);                                                       
                            }                            
                        }else{
                            $this->msg('Ficha id#'.$id_ficha.' não associada a um processo.',1);
                            return $this->listar_ficha($dados); 
                        }
                    }
                }else{
                    $this->msg('Ficha id#'.$id_ficha.' não associada a uma inscrição.',1);
                    return $this->listar_ficha($dados); 
                }
            }else{
                $this->msg('Ficha id#'.$id_ficha.' não encontrada.',1);
                return $this->listar_ficha($dados);
            }            
    }

    public function listar_ficha($dados)
    {       
        if(!UsuariosController::is_logged()){
            $this->msg('Você não está autorizado para acessar essa ficha',2);
            $home = new HomeController();
            return $home->home();
        /*}else if(!$this->check_auth([1,3,4],true)){
            return $this->listar();*/
        }
        $id_ficha      = $dados['id_ficha'];
        $ficha = Ficha::find($id_ficha);       

        $controllerficha = new FichaController();


        if(isset($ficha->id_ficha)){
            $inscricao = Inscricao::find($ficha->id_inscricao);        
            echo $inscricao->id_ficha_rascunho.'' == ''.$id_ficha;
            if($inscricao->id_ficha_rascunho.'' == ''.$id_ficha){
                if($this->check_auth([1],false) && $inscricao->id_user != UsuariosController::get_usuario()['id_user']){
                    $this->msg('Não autorizado para gerenciar os documentos da Ficha id#'.$id_ficha.' com o seu usuário.',2);
                }else{
                    $processo = Processos::find($inscricao->id_processo);         
                    if(isset($processo->id_processo)){                      
                        if(ProcessosController::aberto($processo->id_processo)!=2){
                            $this->msg('Este processo seletivo não está aberto.',2);
                            return $controllerficha->listar_candidato($dados); 
                        }
                        $params = array();
                        $params['pags'] = 1;;
                        $params['pag'] = 1;
                        $params['limit'] = 1000;
                
                        $documentos = Documentos::all_ficha($dados['id_ficha'],1);  
                        
                        $classes = Documentos::all_classes(1);
                        //if($usuarios)    
                        /*for($i = 0;$i<count($usuarios);$i++){
                            if($usuarios[$i]->role == "") $usuarios[$i]->role = "<font color=red>Sem função</font>";
                        }*/
                        return $this->view('documentos'.S.'grade_documentos', ['classes' => $classes, 'processo' => $processo, 'inscricao' => $inscricao, 'ficha' => $ficha, 'data_table' => $documentos,'params' => $params]); 
                        
                    }else{
                        $this->msg('Ficha id#'.$id_ficha.' não associada a um processo.',1);
                        return $controllerficha->listar_candidato($dados); 
                    }
                }
            }else{
                $this->msg('Ficha id#'.$id_ficha.' não associada a uma inscrição.',1);
                return $controllerficha->listar_candidato($dados); 
            }
        }else{
            $this->msg('Ficha id#'.$id_ficha.' não encontrada.',1);
            return $controllerficha->listar_candidato($dados);
        }
                   
       
       
    }


    public function listar_curriculo($dados)
    {       
        if(!UsuariosController::is_logged()){
            $this->msg('Você não está autorizado para acessar essa ficha',2);
            $home = new HomeController();
            return $home->home();
        /*}else if(!$this->check_auth([1,3,4],true)){
            return $this->listar();*/
        }
        
        $id_ficha      = (int) $dados['id_ficha'];
        $ficha = Ficha::find($id_ficha);       

        $controllerficha = new FichaController();


        if(isset($ficha->id_ficha)){
            $inscricao = Inscricao::find($ficha->id_inscricao);        
            if(isset($inscricao->id_inscricao) && $inscricao->id_ficha_rascunho == $id_ficha){
                if($this->check_auth([1],false) && $inscricao->id_user != UsuariosController::get_usuario()['id_user']){
                    $this->msg('Não autorizado para gerenciar os documentos da Ficha id#'.$id_ficha.' com o seu usuário.',2);
                }else{
                    $processo = Processos::find($inscricao->id_processo);         
                    if(isset($processo->id_processo)){                      
                        if(ProcessosController::aberto($processo->id_processo)!=2){
                            $this->msg('Este processo seletivo não está aberto.',2);
                            return $controllerficha->listar_candidato($dados); 
                        }
                        $params = array();
                        $params['pags'] = 1;;
                        $params['pag'] = 1;
                        $params['limit'] = 1000;
                
                        $documentos = Documentos::all_ficha($dados['id_ficha'],2);  
                        
                        $classes = Documentos::all_classes(2);

                        $matriz_classe = Documentos::matriz($ficha->id_ficha);
                        //if($usuarios)    
                        /*for($i = 0;$i<count($usuarios);$i++){
                            if($usuarios[$i]->role == "") $usuarios[$i]->role = "<font color=red>Sem função</font>";
                        }*/
                        return $this->view('documentos'.S.'grade_curriculo', ['matriz_classe' => $matriz_classe, 'classes' => $classes, 'processo' => $processo, 'inscricao' => $inscricao, 'ficha' => $ficha, 'data_table' => $documentos,'params' => $params]); 
                        
                    }else{
                        $this->msg('Ficha id#'.$id_ficha.' não associada a um processo.',1);
                        return $controllerficha->listar_candidato($dados); 
                    }
                }
            }else{
                $this->msg('Ficha id#'.$id_ficha.' não associada a uma inscrição.',1);
                return $controllerficha->listar_candidato($dados); 
            }
        }else{
            $this->msg('Ficha id#'.$id_ficha.' não encontrada.',1);
            return $controllerficha->listar_candidato($dados);
        }
                   
       
       
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

        $classes = Documentos::all_classes();
        return $this->view('form_documento',['data_table' => $dados, 'classes' => $classes]);
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
        $documento = Documentos::find($id_documento);

        if(isset($usuario->id_user)){
            $roles = Documentos::all_classes();
            return $this->view('form_documento', ['data_table' => $dados, 'documento' => $documento, 'classes' => $classes]);
        }else{
            $this->msg('Documento id#'.$id_documento.' não encontrado.',1);
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

        $documento           = new Documentos;
        $documento->id_user     = Usuarios::get_usuario()['id_user'];
        $documento->txt_titulo = $dados['txt_titulo'];
        $documento->txt_filename    = $dados['txt_filename'];
        $documento->id_classe = $dados['id_classe'];
        $documento->dt_criacao = $dados['dt_criacao'];


        if(Validation_Classe::validation_documento($dados)==null){
            if($documento->save($dados)){
                $this->msg('Salvo com sucesso',0);
                //$this->envio_email_senha($dados);
            }else{
                $this->msg('Ocorreu um erro ao tentar salvar documento',2);
            }
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
            $id_user           = (int) $dados['id_documento'];
            $documento->id_user     = Usuarios::get_usuario()['id_user'];
            $documento->txt_titulo = $dados['txt_titulo'];
            $documento->txt_filename    = $dados['txt_filename'];
            $documento->id_classe = $dados['id_classe'];
            $documento->dt_criacao = $dados['dt_criacao'];

   
            if(Validation_Classe::validation_usuario($dados)==null){
                if($documento->save($dados)){
                    $this->msg('Salvo com sucesso',0);
                }else{
                    $this->msg('Ocorreu um erro ao tentar salvar documento',2);
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
            $usuario = Documentos::destroy($id);
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

    public function excluir_doc($dados)
    {
        if(1==1 || $dados['excluir']==1){
            if(!UsuariosController::is_logged()){
                $this->msg('Você não está autorizado para acessar essa ficha',2);
                $home = new HomeController();
                return $home->home();
            }

            $id = (int) $dados['id'];  
              
            $doc = Documentos::find($id,true); 
                     
            $id_ficha = 0;
            if(isset($doc->id_ficha)) $id_ficha = $doc->id_ficha;

            $ficha = Ficha::find($id_ficha);       

            $dados['id_ficha'] = $id_ficha;
            
            $controllerficha = new FichaController();

            if(isset($ficha->id_ficha)){
                $inscricao = Inscricao::find($ficha->id_inscricao);         
                if(isset($inscricao->id_inscricao)){
                    if($this->check_auth([1],false) && $inscricao->id_user != UsuariosController::get_usuario()['id_user']){
                        $this->msg('Não autorizado para excluir o documento da Ficha id#'.$id_ficha.' com o seu usuário.',2);
                    }else{
                        $processo = Processos::find($inscricao->id_processo);         
                        if(isset($processo->id_processo)){                      
                            if(isset($doc)){ 
                                $ok = Documentos::destroy_relation($id,$id_ficha);
                                if($ok){
                                    $this->msg('Excluído com sucesso',0);
                                    return $dados['tipo']==1 ? $this->listar_ficha($dados) : $this->listar_curriculo($dados);
                                }else   
                                    $this->msg('Não foi possível excluir.',1);
                                    return $dados['tipo']==1 ? $this->listar_ficha($dados) : $this->listar_curriculo($dados);
                            }else{
                                $this->msg('Documento não encontrado',1);
                                return $dados['tipo']==1 ? $this->listar_ficha($dados) : $this->listar_curriculo($dados);
                            }
                        }else{
                            $this->msg('Ficha id#'.$id_ficha.' não associada a um processo.',1);
                            return $dados['tipo']==1 ? $this->listar_ficha($dados) : $this->listar_curriculo($dados);
                        }
                    }
                }else{
                    $this->msg('Ficha id#'.$id_ficha.' não associada a uma inscrição.',1);
                    return $dados['tipo']==1 ? $this->listar_ficha($dados) : $this->listar_curriculo($dados);
                }
            }else{
                $this->msg('Ficha id#'.$id_ficha.' não encontrada.',1);
                return $dados['tipo']==1 ? $this->listar_ficha($dados) : $this->listar_curriculo($dados);
            }    
        }else{
            $this->msg('Não foi possível excluir.',1);
            return $dados['tipo']==1 ? $this->listar_ficha($dados) : $this->listar_curriculo($dados);
        }
        return $dados['tipo']==1 ? $this->listar_ficha($dados) : $this->listar_curriculo($dados);
    }

 
}
?>