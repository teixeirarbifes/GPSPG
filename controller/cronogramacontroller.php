<?php
require_once('controller.php');
require_once(GPATH.'model'.S.'cronograma.php');
require_once(GPATH.'model'.S.'processo.php');
require_once(GPATH.'utils'.S.'validation.php');
require_once(GPATH.'sendmail'.S.'mail.php');

class CronogramaController extends Controller
{
    /**
     * Lista os contatos
     */
    public function listar($dados)
    {       
        //ADMINISTRADOR 
        if(!$this->check_auth([3,4],true)){
            $home = new HomeController();
            return $home->home();
        }

        $processo = Processos::find($dados['id_processo']);
        if(!isset($processo->id_processo)){
            $this->msg("Não foi possível encontrar processo",2);
            return "";
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

        $conta = Cronograma::count();
        $pags = ceil($conta/$plim);
        if($ppag >$pags) $ppag  = $pags;

        $params = array();
        $params['pags'] = $pags;
        $params['pag'] = $ppag;
        $params['limit'] = $plim;



        $cronograma = Cronograma::all($dados['id_processo'],$num=$plim,$pag=$ppag,$orderby=$order);    
        if($cronograma)    
        for($i = 0;$i<count($cronograma);$i++){
            if($cronograma[$i]->status == "") $cronograma[$i]->status = "<font color=red>Sem status</font>";
        }
        return $this->view('processos'.S.'grade_cronograma', ['data_table' => $cronograma,'params' => $params,'processo' => $processo]); 
    }
 
    /**
     * Mostrar formulario para criar um novo contato
     */
    public function criar($dados)
    {
        $processo = Processos::find($dados['id_processo']);
        if(!isset($processo->id_processo)){
            $this->msg("Não foi possível encontrar processo",2);
            return "";
        }

        if(!$this->check_auth([3,4],true)){
            $home = new HomeController();
            return $home->home();
        }

        $status = Cronograma::all_status();
        return $this->view('processos'.S.'form_cronograma',['data_table' => $dados, 'status' => $status,'processo' => $processo]);
    }
    
    
    /**
     * Mostrar formulário para editar um contato
     */
    public function editar($dados)
    {
        if(!$this->check_auth([3,4],true)){
            return $this->listar($dados);
        }

        $processo = Processos::find($dados['id_processo']);
        if(!isset($processo->id_processo)){
            $this->msg("Não foi possível encontrar processo",2);
            return "";
        }

        $id_cronograma      = (int) $dados['id_cronograma'];
        $cronograma = Cronograma::find($id_cronograma);       

        if(isset($cronograma->id_cronograma)){
            $status = Cronograma::all_status();
            return $this->view('processos'.S.'form_cronograma', ['data_table' => $dados, 'cronograma' => $cronograma, 'status' => $status,'processo' => $processo]);
        }else{
            $this->msg('cronograma id#'.$id_cronograma.' não encontrado.',1);
            return $this->listar($dados);
        }
    }

    /*
    public function visualizar($dados)
    {
        if(!$this->check_auth([3,4],true)){
            return $this->listar();
        }

        $id_cronograma      = (int) $dados['id_cronograma'];
        $cronograma = Cronograma::find($id_cronograma);       

        if(isset($cronograma->id_cronograma)){
            $status = Cronograma::all_status();
            return $this->view('processos'.S.'view_cronograma', ['data_table' => $dados, 'cronograma' => $cronograma, 'status' => $status]);
        }else{
            $this->msg('cronograma id#'.$id_cronograma.' não encontrado.',1);
            return $this->listar($dados);
        }
    }*/
 

    public static function update_status(){
        $processos = Processos::all(5,0,'',true);
        foreach($processos as $processo){
            $cronograma = Cronograma::get_act_event($processo->id_processo);
            if(isset($cronograma->id_cronograma)){
                $processo->id_status = $cronograma->id_status;
                $processo->id_cronograma = $cronograma->id_cronograma;
                $processo->id_data_cronograma = $cronograma->dt_inicio;
                $processo->bl_publicado = $cronograma->bl_publicado;
                $processo->bl_recurso = $cronograma->bl_recurso;
                $processo->bl_aberto = $cronograma->bl_aberto;
                $processo->save();
            }else{
                $processo->bl_publicado = false;
                $processo->bl_recurso = false;
                $processo->bl_aberto = false;
            }           
        }
    }

    public static function get_cronograma_atual($id_processo){
        $processo = Processos::find($id_processo);
        if(isset($processo->id_processo)){
            $cronograma = Cronograma::find($processo->id_cronograma);
            return $cronograma;
        }else{
            return null;
        }   
    }

    /**
     * Salvar o contato submetido pelo formulário
     */
    public function salvar($dados)
    {
        if(!$this->check_auth([3,4],true)){
            return $this->listar($dados);
        }
        $cronograma           = new Cronograma;
        //$cronograma->id_owner = 0;
        $cronograma->id_processo = $dados['id_processo'];
        $cronograma->id_status = $dados['id_status'];
        $cronograma->txt_descricao = $dados['txt_descricao'];
        
    
        $data = array();
        $data['id_cronograma'] = 0;
        //$data['dt_inicio'] = $cronograma->dt_inicio;
        //$data['dt_fim'] = $cronograma->dt_fim;
        

        if(Validation_Classe::validation_cronograma($data)==null){
            if($cronograma->save($dados)){
                $this->msg('Salvo com sucesso',0);
            }else{
                $this->msg('Ocorreu um erro ao tentar salvar usuário',2);
            }
            return $this->listar($dados);
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
        if(!$this->check_auth([3,4],true)){
            return $this->listar($dados);
        }

        if($this->request->salvar == 1){
            $id_cronograma           = (int) $dados['id_cronograma'];
            $cronograma           = Cronograma::find($id_cronograma,false);
            $cronograma->id_processo = $dados['id_processo'];
            $cronograma->id_status = $dados['id_status'];
            $cronograma->txt_descricao = $dados['txt_descricao'];
            $cronograma->dt_inicio = $dados['dt_inicio'];
            
        
            $data = array();
            $data['id_cronograma'] = 0;
            //$data['dt_inicio'] = $cronograma->dt_inicio;
            //$data['dt_fim'] = $cronograma->dt_fim;
            
            if(Validation_Classe::validation_cronograma($data)==null){
                if($cronograma->save($dados)){
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
            return $this->listar($dados);
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
            $cronograma = Cronograma::destroy($id);
            if($cronograma){
                $this->msg('Excluído com sucesso',0);
                return $this->listar($dados);
            }else   
                $this->msg('Não foi possível excluir.',1);
        }else{
            $this->msg('Não foi possível excluir.',1);
        }
        return $this->editar($dados);
    }
 
}
?>