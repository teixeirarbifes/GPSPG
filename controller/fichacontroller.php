<?php
require_once('controller.php');
require_once(GPATH.'controller'.S.'processoscontroller.php');
require_once(GPATH.'model'.S.'inscricao.php');
require_once(GPATH.'model'.S.'processo.php');
require_once(GPATH.'model'.S.'ficha.php');
require_once(GPATH.'model'.S.'modalidade.php');
require_once(GPATH.'utils'.S.'validation.php');
require_once(GPATH.'sendmail'.S.'mail.php');

class FichaController extends Controller
{
     /**
     * Lista os contatos
     */
    public function listar()
    {       
        //ADMINISTRADOR 
        //if(!$this->check_auth([3,4],true)){
        //    $home = new HomeController();
        //    return $home->home();
        //}
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

        $conta = Processos::count();
        $pags = ceil($conta/$plim);
        if($ppag >$pags) $ppag  = $pags;

        $params = array();
        $params['pags'] = $pags;
        $params['pag'] = $ppag;
        $params['limit'] = $plim;

        $processos = Processos::all($num=$plim,$pag=$ppag,$orderby=$order);        
        if($processos)    
        for($i = 0;$i<count($processos);$i++){
            if($processos[$i]->status == "") $processos[$i]->status = "<font color=red>Sem status</font>";
        }
        return $this->view('processos'.S.'grade_processos', ['data_table' => $processos,'params' => $params]); 
    }

    public function listar_candidato()
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

        $conta = Processos::count();
        $pags = ceil($conta/$plim);
        if($ppag >$pags) $ppag  = $pags;

        $params = array();
        $params['pags'] = $pags;
        $params['pag'] = $ppag;
        $params['limit'] = $plim;

        $processos = Processos::all($num=$plim,$pag=$ppag,$orderby=$order);        
        if($processos)    
        for($i = 0;$i<count($processos);$i++){
            if($processos[$i]->status == "") $processos[$i]->status = "<font color=red>Sem status</font>";
        }
        return $this->view('processos'.S.'grade_processos_candidato', ['data_table' => $processos,'params' => $params]); 
    }
 
    /**
     * Mostrar formulario para criar um novo contato
     */
    public function criar($dados)
    {
        if(!$this->check_auth([3,4],true)){
            $home = new HomeController();
            return $home->home();
        }

        $status = Processos::all_status();
        return $this->view('processos'.S.'form_processos',['data_table' => $dados, 'status' => $status]);
    }
    
    
    /**
     * Mostrar formulário para editar um contato
     */
    public function editar($dados)
    {
        if(!UsuariosController::is_logged()){
            $this->msg('Você não está autorizado para acessar essa ficha',2);
            $home = new HomeController();
            return $home->home();
        }else if(!$this->check_auth([1,3,4],true)){
            return $this->listar();
        }

        

        $id_ficha      = (int) $dados['id_ficha'];
        $ficha = Ficha::find($id_ficha);       

        if(isset($ficha->id_ficha)){
            $inscricao = Inscricao::find($ficha->id_inscricao);         
            if(isset($inscricao->id_inscricao)){
                if($this->check_auth([1],false) && $inscricao->id_user != UsuariosController::get_usuario()['id_user']){
                    $this->msg('Esta ficha não está associada a seu usuário.',2);
                }else{
                    $processo = Processos::find($inscricao->id_processo);         
                    if(isset($processo->id_processo)){
                        if(ProcessosController::aberto($processo->id_processo)!=2){
                            $this->msg('Este processo seletivo não está aberto.',2);
                            return $this->listar_candidato($dados); 
                        }

                        if($ficha->txt_photo!=""){
                            $location = UPLOAD_DIR_PHOTOS.$ficha->txt_photo;

                            if(!file_exists($location)){
                                $this->msg('A sua foto salva não foi encontrada no servidor!',1);
                                $ficha->txt_photo = "";
                            }
                        }
                        $modalidade = Modalidade::get_vagas($inscricao->id_processo);


                        $cep = get_by_CEP($ficha->txt_cep,true);

                        return $this->view('ficha'.S.'form_ficha', ['data_table' => $dados, 'cep' => $cep, 'modalidade' => $modalidade, 'ficha' => $ficha,'processo' => $processo, 'inscricao' => $inscricao]);
                    }else{
                        $this->msg('Inscrição da ficha id#'.$id_ficha.' não associada a um processo.',1);
                        return $this->listar_candidato($dados); 
                    }
                }
            }else{
                $this->msg('Ficha id#'.$id_ficha.' não associada a uma inscrição.',1);
                return $this->listar_candidato($dados); 
            }
        }else{
            $this->msg('Ficha id#'.$id_ficha.' não encontrada.',1);
            return $this->listar_candidato($dados);
        }
    }

    
    public function visualizar($dados)
    {
        //if(!$this->check_auth([3,4],true)){
        //    return $this->listar();
        //}

        $id_processo      = (int) $dados['id_processo'];
        $processo = Processos::find($id_processo);       

        if(isset($processo->id_processo)){
            $status = Processos::all_status();
            return $this->view('processos'.S.'view_processos', ['data_table' => $dados, 'processo' => $processo, 'status' => $status]);
        }else{
            $this->msg('Processos id#'.$id_processo.' não encontrado.',1);
            return $this->listar($dados);
        }
    }
    

    public static function ficha_entregue($id_inscricao){
        $usuario = UsuariosController::get_usuario();

        $inscricao = Inscricao::find($id_inscricao);

        if(!isset($inscricao)){
            return null;
        }

        $id_ficha = $inscricao->id_ficha_enviada;
        $ficha = Inscricao::find($id_ficha);

        if(isset($ficha->id_ficha)){
            return $ficha;
        }
        return null;
    }

    
    public static function ficha_rascunho($id_inscricao)
    {
        $usuario = UsuariosController::get_usuario();

        $inscricao = Inscricao::find($id_inscricao);

        if(!isset($inscricao)){
            return null;
        }

        $id_ficha = $inscricao->id_ficha_rascunho;
        $ficha = Ficha::find($id_ficha);

        if(isset($ficha->id_ficha)){
            return $ficha;
        }else{
            $ficha = new Ficha;
            $ficha->id_inscricao = $id_inscricao;
            
            if($ficha->save()){
            }else{
                return null;
            }
            return $ficha;
        }
        return null;
    }
 
    /**
     * Atualizar o contato conforme dados submetidos
     */

    public function check_auth_ficha($dados,$group){
        $redirect = false;
        if(!$this->check_auth($group)){
            if(UsuariosController::is_logged()){
                $id_ficha           = (int) $dados['id_ficha'];
                $ficha = Ficha::find($id_ficha,false);
                if(isset($ficha->id_ficha)){
                    $inscricao = Inscricao::find($ficha->id_inscricao,false);
                    if(isset($inscricao->id_inscricao)){     
                        if($inscricao->id_user == UsuariosController::get_usuario()['id_user']){
                        }else{
                            $this->msg("A ficha de inscrição #".$ficha->id_ficha." está associado à inscrição ".$ficha->id_inscricao,2);
                            $redirect = true;
                        }
                    }else{
                        $this->msg("A ficha de inscrição #".$id_ficha." não está associada à uma inscrição".$ficha->id_inscricao,2);
                        $redirect = true;
                    }
                }else{
                    $this->msg("A ficha de inscrição #".$id_ficha." não existe",2);
                    $redirect = true;       
                }
            }else{
                $this->msg("Você não está logado para editar ficha de inscrição",2);
                $redirect = true;
            }      
        }
        return !$redirect;
    }

    public static function check_photo($id,$ficha){
            $filename = "";
            $new = "";
            $temp = "";
            $change = false;
            $old = $ficha->txt_photo;
            if($id>0){
                $session_id = session_id();
                $ext = isset($_SESSION['ext'.$id]) ? $_SESSION['ext'.$id] : "";
                if($ext !=""){                    
                    $filename = $session_id.'_'.$id.'.'.$ext;
                    $temp = UPLOAD_DIR_TEMP_PHOTOS.$filename;

                    if(file_exists($temp)){
                        $filename_new = 'photo_'.$ficha->id_ficha."_".uniqid().".".$ext;
                        
                        $new = UPLOAD_DIR_PHOTOS.$filename_new;
                      
                        if($filename_new==$old) $old = "";
                        $ficha->txt_photo = $filename_new;
                        $change = true;
                    } 
                }
            }else{
                if($ficha->txt_photo!=""){
                    $local = UPLOAD_DIR_PHOTOS.$ficha->txt_photo;
                   
                    if(!file_exists($local)){
                        $ficha->txt_photo = "";
                        $change = true;                  
                    }
                }

            }
            return [$change,$ficha->txt_photo,$old,$temp,$new,'teste',$id,session_id()];
    }


    public function atualizar($dados)
    {      
        if(!UsuariosController::is_logged()){
            $this->msg('Você não está autorizado para acessar essa ficha',2);
            $home = new HomeController();
            return $home->home();
        }
        if($this->request->salvar == 1){

            $id_ficha           = (int) $dados['id_ficha'];
            
            $inscricao = Inscricao::get_id_by_ficha_rascunho($id_ficha);

            if(!isset($inscricao->id_inscricao)){
                $this->msg('Não é possível alterar esta ficha.',2);
                return $this->editar($dados);
            }

            if($inscricao->id_user != UsuariosController::get_usuario()['id_user']){
                $this->msg('Não é possível alterar ficha de outro usuário.',2);
                return $this->editar($dados);
            }
            
            $ficha = Ficha::find($id_ficha,false);
            $ficha->txt_nome = UsuariosController::get_usuario()['txt_nome'];
            $ficha->txt_nome_mae = $dados['txt_nome_mae'];
            $ficha->txt_nome_pai = $dados['txt_nome_pai'];
            $ficha->txt_nascimento = $dados['txt_nascimento'];
            $ficha->txt_email = UsuariosController::get_usuario()['txt_email'];
            $ficha->txt_civil = $dados['txt_civil'];
            $ficha->txt_sexo = $dados['txt_sexo'];
            $ficha->txt_escolaridade = $dados['txt_escolaridade'];
            $ficha->txt_telefone = $dados['txt_telefone'];
            $ficha->txt_celular = $dados['txt_celular'];
            $ficha->txt_natural_pais = $dados['txt_natural_pais'];
            $ficha->txt_natural_estado = $dados['txt_natural_estado'];
            $ficha->txt_natural_cidade = $dados['txt_natural_cidade'];
            $ficha->txt_natural_estado_exterior = $dados['txt_natural_estado_exterior'];
            $ficha->txt_natural_cidade_exterior = $dados['txt_natural_cidade_exterior'];
            $ficha->txt_rg = $dados['txt_rg'];
            $ficha->txt_rg_orgao = $dados['txt_rg_orgao'];
            $ficha->txt_rg_uf = $dados['txt_rg_uf'];
            $ficha->txt_rg_expedicao = $dados['txt_rg_expedicao'];
            $ficha->txt_eleitor = $dados['txt_eleitor'];
            $ficha->txt_eleitor_zona = $dados['txt_eleitor_zona'];
            $ficha->txt_eleitor_secao = $dados['txt_eleitor_secao'];
            $ficha->txt_eleitor_estado = $dados['txt_eleitor_estado'];
            $ficha->txt_eleitor_emissao = $dados['txt_eleitor_emissao'];
            $ficha->txt_cep = $dados['txt_cep'];
            
            $cep = get_by_CEP($dados['txt_cep'],true);
            
            if($cep['logradouro']!="")
            $dados['txt_logadouro'] = $cep['logradouro'];

            if($cep['bairro']!="")
            $dados['txt_bairro'] = $cep['bairro'];

            $dados['txt_cidade'] = $cep['localidade'];
            $dados['txt_estado'] = $cep['uf'];

            $ficha->txt_logadouro = $dados['txt_logadouro'];
            $ficha->txt_numero = $dados['txt_numero'];
            $ficha->txt_complemento = $dados['txt_complemento'];
            $ficha->txt_bairro = $dados['txt_bairro'];
            $ficha->txt_cidade = $dados['txt_cidade'];
            //$ficha->txt_cidade = get_code_by_city($ficha->txt_code_cidade);
            $ficha->txt_estado = $dados['txt_estado'];
            $ficha->id_modalidade = $dados['id_modalidade'];
            $ficha->bl_condicao_especial = $dados['bl_condicao_especial2'];

            #$inscricao = Inscricao::find($ficha->id_inscricao,false);
            #$inscricao->id_modalidade = $dados['id_modalidade'];
            
            $photo = FichaController::check_photo($dados["id"],$ficha);
            $change = $photo[0];
            $ficha->txt_photo = $photo[1];
            $old = $photo[2];
            $temp = $photo[3];
            $new = $photo[4];
            $dados['txt_photo']  = $photo[1];

            /*$data = array();
            $data['id_ficha'] = $ficha->id_ficha;
            $data['txt_nome'] = $ficha->txt_nome;
            $data['txt_nome_mae'] = $ficha->txt_nome_mae; 
            $data['txt_nome_pai'] = $ficha->txt_nome_pai;
            $data['txt_email'] = $ficha->txt_email;
            $data['txt_civil'] = $ficha->txt_civil;
            $data['txt_sexo'] = $ficha->txt_sexo;
            $data['txt_telefone'] = $ficha->txt_telefone;
            $data['txt_celular'] = $ficha->txt_celular;
            $data['txt_natural_pais'] = $ficha->txt_natural_pais;
            $data['txt_natural_estado'] = $ficha->txt_natural_estado;
            $data['txt_natural_cidade'] = $ficha->txt_natural_cidade;
            $data['txt_cpf'] = $ficha->txt_cpf;
            $data['txt_rg'] = $ficha->txt_rg;
            $data['txt_rg_orgao'] = $ficha->txt_rg_orgao;
            $data['txt_rg_uf'] = $ficha->txt_rg_uf;
            $data['txt_rg_expedicao'] = $ficha->txt_rg_expedicao;
            $data['txt_eleitor'] = $ficha->txt_eleitor;
            $data['txt_eleitor_zona'] = $ficha->txt_eleitor_zona;
            $data['txt_eleitor_secao'] = $ficha->txt_eleitor_secao;
            $data['txt_eleitor_estado'] = $ficha->txt_eleitor_estado;
            $data['txt_eleitor_emissao'] = $ficha->txt_eleitor_emissao;
            $data['txt_logadouro'] = $ficha->txt_logadouro;
            $data['txt_numero'] = $ficha->txt_numero;
            $data['txt_complemento'] = $ficha->txt_complemento;
            $data['txt_cep'] = $ficha->txt_cep;
            $data['txt_bairro'] = $ficha->txt_bairro;
            $data['txt_cidade'] = $ficha->txt_cidade;
            $data['txt_estado'] = $ficha->txt_estado;
            $data['id'] = $dados['id'];
            $data['id_saved'] = $dados['id_saved'];*/
            
            if(Validation_Classe::validation_ficha($dados,false)==null){
                if($ficha->save($dados)){
                    if($change){  
                        if($new!=""){
                            if(!rename($temp,$new)){
                                $this->msg('Problema para salvar arquivo de imagem.',2);
                            }
                        }
                        if($old!="" && file_exists($_SERVER['DOCUMENT_ROOT'].'\\upload\\photos\\'.$old))
                            unlink($_SERVER['DOCUMENT_ROOT'].'\\upload\\photos\\'.$old);                      
                    }else{
                    }

                    $insc = new DocumentosController();
                    $this->msg('Sua ficha de inscrição foi salva com sucesso!',0);
                    if($dados['voltar'] == 1){
                        $dados['id_processo'] = $inscricao->id_processo;
                        return $insc->listar_ficha($dados);                    
                    }else{                       
                        return $this->editar($dados);
                    }
                }else{
                    $this->msg('Ocorreu um erro ao tentar salvar usuário',2);
                    return $this->editar($dados);
                }
                

            }else{                
                $this->msg('Não foi possível salvar o registro por incosistências.',2);
                return $this->editar($dados);
            }
        }else{
            return $this->editar($dados);
        }
    }

    
    /**
     * Apagar um contato conforme o id informado
     */
    public function excluir($dados)
    {

        if(!$this->check_auth([3,4],true)){
            return $this->editar($dados);
        }

        if($dados['excluir']==1){
            $id      = (int) $dados['id'];
            $processo = Processos::destroy($id);
            if($processo){
                $this->msg('Excluído com sucesso',0);
                return $this->listar();
            }else   
                $this->msg('Não foi possível excluir.',1);
        }else{
            $this->msg('Não foi possível excluir.',1);
        }
        return $this->editar($dados);
    }
 
}
?>