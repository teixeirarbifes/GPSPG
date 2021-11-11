<?php
require_once('controller.php');
require_once(GPATH.'model'.S.'status.php');
require_once(GPATH.'utils'.S.'validation.php');
require_once(GPATH.'sendmail'.S.'mail.php');

class StatusController extends Controller
{
    /**
     * Lista os contatos
     */
    public function listar()
    {       
        //ADMINISTRADOR 
        if(!$this->check_auth([3,4],true)){
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

        $conta = Status::count();
        $pags = ceil($conta/$plim);
        if($ppag >$pags) $ppag  = $pags;

        $params = array();
        $params['pags'] = $pags;
        $params['pag'] = $ppag;
        $params['limit'] = $plim;
        $status = Status::all($num=$plim,$pag=$ppag,$orderby=$order);        
        
        return $this->view('status'.S.'grade_status', ['data_table' => $status,'params' => $params]); 
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

        return $this->view('status'.S.'form_status',['data_table' => $dados]);
    }
    
    /**
     * Mostrar formulário para editar um contato
     */
    public function editar($dados)
    {
        if(!$this->check_auth([3,4],true)){
            return $this->listar();
        }

        $id_status      = (int) $dados['id_status'];
        $status = Status::find($id_status);       

        if(isset($status->id_status)){
            return $this->view('status'.S.'form_status', ['data_table' => $dados, 'status' => $status]);
        }else{
            $this->msg('Status id#'.$id_status.' não encontrado.',1);
            return $this->listar($dados);
        }
    }
 

    /**
     * Salvar o contato submetido pelo formulário
     */
    public function salvar($dados)
    {
        if(!$this->check_auth([3,4],true)){
            return $this->listar();
        }

        $status           = new Status;
        $status->id_status = $dados['id_status_field'];
        $status->txt_status = $dados['txt_status'];
        $status->num_ordem = $dados['num_order'];
        $status->bl_publicado = (isset($dados['bl_publicado']) ? 1 : 0);
        $status->bl_aberto = (isset($dados['bl_aberto']) ? 1 : 0);
        $status->bl_recurso = (isset($dados['bl_recurso']) ? 1 : 0);
            
        $data = array();
        $data['id_status'] = 0;
        $data['id_status_field'] = $dados['id_status_field'];
        $data['txt_status'] = $status->txt_status;
        $data['num_ordem'] = $status->num_ordem;

        $dados['novo'] = 1;
        if(Validation_Classe::validation_status($data)==null){
            if($status->save($dados)){
                $this->msg('Salvo com sucesso',0);
            }else{
                $this->msg('Ocorreu um erro ao tentar salvar usuário',2);
            }
            $dados['id_status'] = $dados['id_status_field'];
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
            return $this->listar();
        }

        if($this->request->salvar == 1){
            $id_status         = (int) $dados['id_status'];
            $status           = Status::find($id_status,false);
            $status->id_status = $dados['id_status_field'];
            $status->txt_status = $dados['txt_status'];
            $status->num_ordem = $dados['num_ordem'];
            $status->bl_publicado = (isset($dados['bl_publicado']) ? 1 : 0);
            $status->bl_aberto = (isset($dados['bl_aberto']) ? 1 : 0);
            $status->bl_recurso = (isset($dados['bl_recurso']) ? 1 : 0);
                
            $data = array();
            $data['id_status'] = $status;
            $data['id_status_field'] = $dados['id_status_field'];
            $data['txt_status'] = $status->txt_status;
            
            $dados['novo'] = 0;
            if(Validation_Classe::validation_status($data)==null){
                if($status->save($dados)){
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

        if(!$this->check_auth([3,4],true)){
            return $this->editar($dados);
        }

        if($dados['excluir']==1){
            $id      = (int) $dados['id'];
            $status = Status::destroy($id);
            if($status){
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