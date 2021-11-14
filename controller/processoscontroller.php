<?php
require_once('controller.php');
require_once(GPATH.'model'.S.'processo.php');
require_once(GPATH.'model'.S.'inscricao.php');
require_once(GPATH.'utils'.S.'validation.php');
require_once(GPATH.'sendmail'.S.'mail.php');

class ProcessosController extends Controller
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

        $processos = Processos::all($num=$plim,$pag=$ppag,$orderby=$order,false,true);        
        if($processos)    
        for($i = 0;$i<count($processos);$i++){
                if(UsuariosController::is_logged()){
                    $inscricao = Inscricao::get_id_by_processo($processos[$i]->id_processo,UsuariosController::get_usuario()['id_user'],false);
                    if(isset($inscricao->id_inscricao))
                    $processos[$i]->inscrito = true;
                    else
                    $processos[$i]->inscrito = false;
                }else{
                    $processos[$i]->inscrito = false;
                }
            
            if($processos[$i]->status == "") $processos[$i]->status = "<font color=red>Sem status</font>";
            $processos[$i]->cronograma = CronogramaController::get_cronograma_atual($processos[$i]->id_processo);
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
        if(!$this->check_auth([3,4],true)){
            return $this->listar();
        }

        $id_processo      = (int) $dados['id_processo'];
        $processo = Processos::find($id_processo);       

        if(isset($processo->id_processo)){
            $status = Processos::all_status();
            return $this->view('processos'.S.'form_processos', ['data_table' => $dados, 'processo' => $processo, 'status' => $status]);
        }else{
            $this->msg('Processos id#'.$id_processo.' não encontrado.',1);
            return $this->listar($dados);
        }
    }

    public function visualizar($dados)
    {
        if(!$this->check_auth([3,4],true)){
            return $this->listar();
        }

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

    public function update_status($dados){
        
    }

    public static function aberto($id_processo){
        $processo = Processos::find($id_processo);
        $ini = new DateTime($processo->dt_inicio_inscricao);
        $fim = new DateTime($processo->dt_fim_inscricao);

        $agora = new DateTime();
        
        $k_ini = $agora->getTimestamp() - $ini->getTimestamp();
        $k_fim = $fim->getTimestamp() - $agora->getTimestamp();
            
        $v = 2;   
        if($k_ini < 0 || $k_fim < 0){
            if($k_ini<0) $v = 0;
            else if($k_fim<0) $v = 1;
        }
        return $v;
    }

    public function visualizar_candidato($dados)
    {
        $id_processo      = (int) $dados['id_processo'];
        $processo = Processos::find($id_processo);       

        if(isset($processo->id_processo)){

            if(UsuariosController::is_logged()){
                $usuario = UsuariosController::get_usuario();
                $inscricao = Inscricao::get_id_by_processo($dados['id_processo'],$usuario['id_user']);
                $dados['id_inscricao'] = isset($inscricao->id_inscricao) ? $inscricao->id_inscricao : 0;
                $dados['id_ficha_enviada']= isset($inscricao->id_ficha_enviada) ? $inscricao->id_ficha_enviada : 0;            
                if(isset($inscricao->dt_enviado)){
                    $date = new DateTime($inscricao->dt_enviado);
                    $inscricao->dt_enviado = $date->format('d/m/Y h:i:s');
                }
            }else{
                $usuario = null;
                $inscricao = null;
                $dados['id_inscricao'] = 0;
                $dados['id_ficha_enviada'] = 0;
            }
            
            //$dados['id_inscricao'] = 0;
            //$dados['id_ficha_enviada']= 0;

            $status = Processos::all_status();

            $cronograma = CronogramaController::get_cronograma_atual($processo->id_processo);

            return $this->view('processos'.S.'view_processos_candidato', ['iniciar' => isset($dados['iniciar']) ? $dados['iniciar'] : 0, 'evento' => $cronograma, 'usuario' => $usuario, 'data_table' => $dados, 'inscricao' => $inscricao, 'processo' => $processo, 'status' => $status]);
        }else{  
            $this->msg('Processos id#'.$id_processo.' não encontrado.',1);
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

        $processo           = new Processos;
        $processo->id_owner = 0;
        $processo->txt_processo = $dados['txt_processo'];
        //$processo->dt_criacao    = $dados['txt_email'];
        //$processo->dt_modificacao    = $dados['txt_email'];
        $processo->txt_descricao = $dados['txt_descricao'];
        $processo->id_status = $dados['id_status'];
    
        $data = array();
        $data['id_processo'] = 0;
        $data['txt_processo'] = $processo->txt_processo;

        if(Validation_Classe::validation_processo($data)==null){
            if($processo->save($dados)){
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
            return $this->listar();
        }

        if($this->request->salvar == 1){
            $id_processo           = (int) $dados['id_processo'];
            $processo           = Processos::find($id_processo,false);
            $processo->txt_processo = $dados['txt_processo'];
            //$processo->dt_criacao    = $dados['txt_email'];
            //$processo->dt_modificacao    = $dados['txt_email'];
            $processo->txt_descricao = $dados['txt_descricao'];
            $processo->id_status = $dados['id_status'];

            $data = array();
            $data['id_processo'] = $processo->id_processo;
            $data['txt_processo'] = $processo->txt_processo;
            
            if(Validation_Classe::validation_processo($data)==null){
                if($processo->save($dados)){
                    $this->msg('Salvo com sucesso',0);
                }else{
                    $this->msg('Ocorreu um erro ao tentar salvar usuário',2);
                }
                
                return $this->visualizar($dados);
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