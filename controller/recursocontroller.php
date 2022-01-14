<?php
require_once('controller.php');
require_once(GPATH.'model'.S.'recurso.php');
require_once(GPATH.'model'.S.'inscricao.php');
require_once(GPATH.'utils'.S.'validation.php');
require_once(GPATH.'sendmail'.S.'mail.php');

class RecursoController extends Controller
{
    /**
     * Lista os contatos
     */
    /*public function listar()
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

        $conta = Recursos::count();
        $pags = ceil($conta/$plim);
        if($ppag >$pags) $ppag  = $pags;

        $params = array();
        $params['pags'] = $pags;
        $params['pag'] = $ppag;
        $params['limit'] = $plim;

        $recursos = Recursos::all($num=$plim,$pag=$ppag,$orderby=$order);        
        if($recursos)    
        for($i = 0;$i<count($processos);$i++){
            if($processos[$i]->status == "") $processos[$i]->status = "<font color=red>Sem status</font>";
        }
        return $this->view('processos'.S.'grade_processos', ['data_table' => $processos,'params' => $params]); 
    }*/

    /*public function listar_candidato()
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
                    if(isset($inscricao->id_inscricao)){
                        $processos[$i]->inscrito = true;
                        if($inscricao->id_ficha_enviada>0){
                            $processos[$i]->enviado = true;
                        }else{
                            $processos[$i]->enviado = false;
                        }
                    }else{
                        $processos[$i]->inscrito = false;
                        $processos[$i]->enviado = false;
                    }
                }else{
                    $processos[$i]->inscrito = false;
                    $processos[$i]->enviado = false;
                }
            
            if($processos[$i]->status == "") $processos[$i]->status = "<font color=red>Sem status</font>";
            $processos[$i]->cronograma = CronogramaController::get_cronograma_atual($processos[$i]->id_processo);
        }
        return $this->view('processos'.S.'grade_processos_candidato', ['data_table' => $processos,'params' => $params]); 
    }*/
 
    /**
     * Mostrar formulario para criar um novo contato
     */
    public function criar($dados)
    {
        if(!UsuariosController::is_logged()){
            $this->msg('Você não está logado no sistema. Não é possível submeter recurso.',1);
            $home = new HomeController();
            return $home->home();
        }
        $processos = Processos::find($dados['id_processo'],false);
        if(isset($dados['recurso'])) $recurso = $dados['recurso'];
        //$status = Processos::all_status();
        return $this->view('recurso'.S.'form_recurso',['data_table' => $dados, 'processo' => $processos, 'recurso' => $recurso]); //, 'status' => $status]);
    }
    

    public static function all_recurso($dados)
   {                     
       $conexao = Conexao::getInstance();                      
      
       $id_processo = $dados['id_processo'];
       //$inscrito = isset($dados['inscrito']) && $dados['inscrito'] == 1 ? " AND id_ficha_enviada > 0 " : " ";
       $jtStartIndex=$_GET['jtStartIndex'];
       $jtPageSize=$_GET['jtPageSize'];
       $jtSorting='txt_processo ASC'; //$_POST['jtSorting'];
       $stmt    = $conexao->prepare("SELECT tab_users.*, tab_recurso.*, tab_processos.txt_processo as txt_processo FROM tab_recurso LEFT JOIN tab_processos ON tab_recurso.id_processo = tab_processos.id_processo LEFT JOIN tab_users ON tab_recurso.id_user_autor = tab_users.id_user WHERE tab_recurso.id_processo = '{$id_processo}' ORDER BY txt_processo ASC LIMIT {$jtStartIndex}, {$jtPageSize};");
       $result  = array();
       $stmt->setFetchMode(PDO::FETCH_ASSOC);
       if ($stmt->execute()) {
           while ($rs = $stmt->fetchObject(Recurso::class)) {
               $result[] = $rs;
           }
       }
       if (count($result) > 0) {
           return $result;
       }
       return false;
   }
    
    /**
     * Mostrar formulário para editar um contato
     */
    public function editar($dados)
    {
        if(!$this->check_auth([3,4],true)){
            return "erro"; //$this->listar();
        }

        $id_recurso      = (int) $dados['id_recurso'];
        $recurso = Processos::find($id_recurso);       

        if(isset($recurso->id_recurso)){
            //$status = Recursos::all_status();
            return $this->view('recursos'.S.'form_recurso', ['data_table' => $dados, 'recurso' => $recurso]); //, 'status' => $status]);
        }else{
            $this->msg('Recursos id#'.$id_recurso.' não encontrado.',1);
            return; //$this->listar($dados);
        }
    }

    public function visualizar($dados)
    {
        if(!$this->check_auth([3,4],true)){
            return; //$this->listar();
        }

        $id_recurso      = (int) $dados['id_recurso'];
        $recurso = Processos::find($id_recurso);       

        if(isset($recurso->id_recurso)){
            //$status = Processos::all_status();
            return $this->view('recursos'.S.'view_recurso', ['data_table' => $dados, 'recurso' => $recurso]);//, 'status' => $status]);
        }else{
            $this->msg('Recursos id#'.$id_recurso.' não encontrado.',1);
            return; //$this->listar($dados);
        }
    }

    public function update_status($dados){
        $agora = new DateTime();
        echo $agora->format('d-m-Y H:i:s');
    }

    /*public function visualizar_candidato($dados)
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

            if(isset($inscricao->id_inscricao))
            $ficha = FichaController::ficha_rascunho($inscricao->id_inscricao);
            else
            $ficha = null;
            //$dados['id_ficha_enviada']= 0;

            $status = Processos::all_status();

            $cronograma = CronogramaController::get_cronograma_atual($processo->id_processo);

            return $this->view('processos'.S.'view_processos_candidato', ['ficha' => $ficha, 'iniciar' => isset($dados['iniciar']) ? $dados['iniciar'] : 0, 'evento' => $cronograma, 'usuario' => $usuario, 'data_table' => $dados, 'inscricao' => $inscricao, 'processo' => $processo, 'status' => $status]);
        }else{  
            $this->msg('Processos id#'.$id_processo.' não encontrado.',1);
            return $this->listar($dados);
        }
    }*/

    /**
     * Salvar o contato submetido pelo formulário
     */
            

    public function getRandomString($n,$letters) {
        if($letters)
        $characters = 'ABCDEFGHIJLMNOPQRSTUVXZKYW';
        else
        $characters = '123456789';
        $randomString = '';
      
        for ($i = 0; $i < $n; $i++) {
            $index = rand(0, strlen($characters) - 1);
            $randomString .= $characters[$index];
        }
      
        return $randomString;
    }


    public function salvar($dados)
    {
        //if(!$this->check_auth([3,4],true)){
        //    return;// $this->listar();
        //}

        $recurso           = new Recursos;
        $recurso->txt_recurso = $dados['txt_recurso'];
        $recurso->id_user_autor = UsuariosController::get_usuario()['id_user'];
        $recurso->id_processo = $dados['id_processo'];
        $recurso->id_classe_recurso = $dados['id_classe_recurso'];
        $recurso->dt_submissao = (new DateTime())->format('Y-m-d H:i:s');
        $recurso->bl_deferido = false;
        $recurso->txt_analise = "";
        $recurso->dt_analise = null;
        $recurso->id_user_analise = 0;
        $recurso->txt_conclusao = "";
        $recurso->dt_conclusao = null;
        $recurso->id_user_conclusao = 0;
    
        $data = array();
        $data['id_recurso'] = 0;
        $data['txt_recurso'] = $recurso->txt_recurso;

        if(Validation_Classe::validation_recurso($data)==null){
            if($recurso->save($dados)){
                $recurso->txt_protocolo = $this->getRandomString(3,true).$this->getRandomString(1,false).$recurso->id_recurso.$this->getRandomString(1,true).$this->getRandomString(1,false).$this->getRandomString(3,true);
                $recurso->save($dados);
                $this->msg('Salvo com sucesso',0);
                $dados['sucess'] = 1;
                $dados['recurso'] = $recurso;
                return $this->criar($dados);
            }else{
                $this->msg('Ocorreu um erro ao tentar salvar recurso',2);
                return $this->criar($dados);
            }
            
        }else{                
            $this->msg('Não foi possível salvar o registro por incosistências.',2);
            
            return; //$this->listar($dados);
        }
    }
 
    /**
     * Atualizar o contato conforme dados submetidos
     */
    /*public function atualizar($dados)
    {      
        if(!$this->check_auth([3,4],true)){
            return; //$this->listar();
        }

        if($this->request->salvar == 1){
            $id_recurso           = (int) $dados['id_recurso'];
            $recurso           = Recursos::find($id_recurso,false);
            $recurso->txt_recurso = $dados['txt_recurso'];
            $recurso->bl_deferido = $dados['bl_deferido'];
            $recurso->txt_analise = $dados['txt_analise'];
            $recurso->dt_analise = null;
            $recurso->id_user_analise = 0;
            $recurso->txt_conclusao = "";
            $recurso->dt_conclusao = null;
            $recurso->id_user_conclusao = 0;
    

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
    }*/

    
    /**
     * Apagar um contato conforme o id informado
     */
    public function excluir($dados)
    {

        if(!$this->check_auth([3,4],true)){
            return; //$this->editar($dados);
        }

        if($dados['excluir']==1){
            $id      = (int) $dados['id'];
            $recurso = Recursos::destroy($id);
            if($recurso){
                $this->msg('Excluído com sucesso',0);
                return; //$this->listar();
            }else   
                $this->msg('Não foi possível excluir.',1);
        }else{
            $this->msg('Não foi possível excluir.',1);
        }
        return $this->editar($dados);
    }
 
}
?>