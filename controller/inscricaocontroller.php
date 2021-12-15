<?php
use FFI\Exception;
require_once('controller.php');
require_once(GPATH.'controller'.S.'processoscontroller.php');
require_once(GPATH.'controller'.S.'fichacontroller.php');
require_once(GPATH.'controller'.S.'usuarioscontroller.php');
require_once(GPATH.'model'.S.'inscricao.php');
require_once(GPATH.'utils'.S.'validation.php');
require_once(GPATH.'model'.S.'processo.php');
require_once(GPATH.'model'.S.'documentos.php');
require_once(GPATH.'sendmail'.S.'mail.php');

class InscricaoController extends Controller
{

    public function inscrever($dados){
        $home = new HomeController();  
        $control = new ProcessosController();  
        if(!isset($dados['id_processo'])){
            $this->msg("Processo seletivo não identificado.",2);
            return $home->home();
        }
        if(!UsuariosController::is_logged()){
            $this->msg("Você não está logado no sistema",2);
            
            return $control->visualizar_candidato(['id_processo' => $dados['id_processo']]);
        }   
        $usuario = UsuariosController::get_usuario();
        $inscricao = Inscricao::get_id_by_processo($dados['id_processo'],$usuario['id_user']);
        $id_inscricao = isset($inscricao->id_inscricao) ? $inscricao->id_inscricao : 0;
        $id_ficha_enviada = isset($inscricao->id_ficha_enviada) ? $inscricao->id_ficha_enviada : 0;
                 
        if($id_inscricao > 0){
            if($id_ficha_enviada > 0){
                $this->msg("Você já fez a sua inscrição para esse processo seletivo.",1);
            }else{
                $this->msg("Você ja íniciou sua inscrição para esse processo seletivo.",1);
            }                    
            
            return $control->visualizar_candidato(['id_processo' => $dados['id_processo']]);
        }else{
            return $this->gerar_inscricao(["id_processo" => $dados['id_processo']]);
        }
    }

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

    public function dashboard($dados)
    {
        $home = new HomeController();
        $pproc = new ProcessosController();
        if(!UsuariosController::is_logged()){
            $this->msg("Você não está logado no sistema",2);
            return $home->home();
        }

        #if(!$this->check_auth([1],true)){
        #    return $home->home();
        #}

        $processo = Processos::find($dados['id_processo']);       
        if(isset($processo->id_processo)){
            $inscricao = Inscricao::find_user($dados['id_processo'],UsuariosController::get_usuario()['id_user']);
            if(isset($inscricao->id_inscricao)){
                $ficha = FichaController::ficha_rascunho($inscricao->id_inscricao);               
                return $this->view('ficha'.S.'view_dashboard', ['data_table' => $dados, 'processo' => $processo, 'inscricao' => $inscricao, 'ficha' => $ficha, 'usuario' => UsuariosController::get_usuario()]);    
            }else{
                $this->msg("Não há inscricao para esse processo seletivo",2);
                return $pproc->visualizar_candidato($dados);
            }
        }

        if(isset($processo->id_processo)){
            $status = Processos::all_status();
            return $this->view('processos'.S.'form_processos', ['data_table' => $dados, 'processo' => $processo, 'status' => $status]);
        }else{
            $this->msg('Processos id#'.$id_processo.' não encontrado.',1);
            return $this->listar($dados);
        }
    }


    public function listar_inscricao()
    {   
        $home = new HomeController();
        if(!UsuariosController::is_logged()){
            $this->msg("Você não está logado no sistema para ver suas inscrições.",2);
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
        if($ppag<1) $ppag = 1;
        $params = array();
        $params['pags'] = $pags;
        $params['pag'] = $ppag;
        $params['limit'] = $plim;

        $inscricoes = Inscricao::all_user($id_user=UsuariosController::get_usuario()['id_user'],$num=$plim,$pag=$ppag,$orderby=$order);        
        
        if($inscricoes)
        foreach($inscricoes as $i){
            $processo = Processos::find($i->id_processo);
            if($i->id_ficha_enviada==0)
                $i->txt_status = "<font color=red>Inscrição não concluída</font>";
            else
                $i->txt_status = "<font color=darkgreen>Inscrito</font>";

            $aberto = ProcessosController::aberto($processo->id_processo);
            if($aberto == 0){
                $i->txt_aberto = "<b><font color=red>Não, prazo expirado!</font></b>";
            }else if($aberto == 1){
                    $i->txt_aberto = "<b><font color=red>Não, em breve!</font></b>";
            }else{
                $i->txt_aberto = "<b><font color=darkgreen>Sim, dentro do prazo</font></b>";
            }


        }

        return $this->view('inscricao'.S.'grade_inscricoes_candidato', ['data_table' => $inscricoes,'params' => $params]); 
    }

    

    public function verificar($dados)
    {
        $home = new HomeController();
        $pproc = new ProcessosController();
        if(!UsuariosController::is_logged()){
            $this->msg("Você não está logado no sistema",2);
            return $home->home();
        }

        #if(!$this->check_auth([1],true)){
        #    return $home->home();
        #}

        $processo = Processos::find($dados['id_processo']);       

        if(isset($processo->id_processo)){
            $inscricao = Inscricao::find_user($dados['id_processo'],UsuariosController::get_usuario()['id_user']);
            if(isset($inscricao->id_inscricao)){
                $dados['rascunho'] = 1;
                $ficha = FichaController::ficha_rascunho($inscricao->id_inscricao);
                $modalidade = Modalidade::find($ficha->id_modalidade);
                if(isset($modalidade->id_modalidade)){
                    $ficha->modalidade = $modalidade->txt_modalidade;
                    $ficha->sigla = $modalidade->txt_sigla;
                    $ficha->desc_modalidade = $modalidade->txt_descricao;
                }else{
                    $ficha->modalidade = "(Não selecionada)";
                    $ficha->sigla = "***";
                    $ficha->desc_modalidade = '';    
                }


                if(isset($ficha->txt_natural_pais)){
                    $ficha->txt_natural_pais = get_pais($ficha->txt_natural_pais)[0]['nome'];
                }

                if(isset($ficha->txt_natural_estado)){
                    $ficha->txt_natural_estado = get_uf($ficha->txt_natural_estado)[0]['nome'];
                }


                if(isset($ficha->txt_natural_cidade)){
                    $ficha->txt_natural_cidade = get_city_by_code($ficha->txt_natural_cidade);
                }

                

                if(isset($ficha->txt_sexo)){
                    if($ficha->txt_sexo == 1)   
                        $ficha->txt_sexo = "Masculino";
                    else if($ficha->txt_sexo == 2)   
                        $ficha->txt_sexo = "Feminino";
                    else                        
                        $ficha->txt_sexo = "Ignorado";                    
                }

                if(isset($ficha->txt_civil)){
                    if($ficha->txt_civil == 1)   
                        $ficha->txt_civil = "Casado";
                    else if($ficha->txt_civil == 2)   
                        $ficha->txt_civil = "Divorciado";
                    else if($ficha->txt_civil == 3)   
                        $ficha->txt_civil = "Separado";
                    else if($ficha->txt_civil == 4)   
                        $ficha->txt_civil = "Solteiro";
                    else if($ficha->txt_civil == 5)   
                        $ficha->txt_civil = "União estável";
                    else                        
                        $ficha->txt_civil = "Viúvo";                    
                }

                if(isset($ficha->txt_escolaridade)){
                    if($ficha->txt_escolaridade == 1)   
                        $ficha->txt_escolaridade = "Doutorado";
                    else if($ficha->txt_escolaridade == 2)   
                        $ficha->txt_escolaridade = "Mestrado";
                    else if($ficha->txt_escolaridade == 3)   
                        $ficha->txt_escolaridade = "Superior completo";
                    else if($ficha->txt_escolaridade == 4)   
                        $ficha->txt_escolaridade = "Superior incompleto";
                    else if($ficha->txt_escolaridade == 5)   
                        $ficha->txt_escolaridade = "Ensino médio completo";
                    else if($ficha->txt_escolaridade == 6)   
                        $ficha->txt_escolaridade = "Ensino médio incompleto";
                    else if($ficha->txt_escolaridade == 7)   
                        $ficha->txt_escolaridade = "Ensino fundamental completo";
                    else if($ficha->txt_escolaridade == 8)   
                        $ficha->txt_escolaridade = "Ensino fundamental incompleto"; 
                    else                        
                        $ficha->txt_escolaridade = "Não sei informar";                 
                }

                //$ficha->txt_natural_pais = get_pais

                if(isset($ficha->txt_nascimento)){
                    $ficha->txt_nascimento = date('d-m-Y', strtotime($ficha->txt_nascimento) );
                }

                if(isset($ficha->txt_rg_expedicao)){
                    $ficha->txt_rg_expedicao = date('d-m-Y', strtotime($ficha->txt_rg_expedicao) );
                }
                if(isset($ficha->txt_eleitor_emissao)){
                    $ficha->txt_eleitor_emissao = date('d-m-Y', strtotime($ficha->txt_eleitor_emissao) );
                }
                
                $documentos_pessoais = Documentos::all_ficha($ficha->id_ficha,1);  
                $documentos_curriculo = Documentos::all_ficha($ficha->id_ficha,2);  

                $matriz_classe = Documentos::matriz($ficha->id_ficha);

                return $this->view('ficha'.S.'form_verificar', ['matriz_classe' => $matriz_classe,'documentos_curriculo' => $documentos_curriculo, 'documentos_pessoais' => $documentos_pessoais, 'data_table' => $dados, 'processo' => $processo, 'inscricao' => $inscricao, 'ficha' => $ficha, 'usuario' => UsuariosController::get_usuario()]);    
            }else{
                $this->msg("Não há inscricao para esse processo seletivo",2);
                return $pproc->visualizar_candidato($dados);
            }
        }else{
            $this->msg('Processos id#'.$dados['id_processo'].' não encontrado.',1);
            return $this->listar($dados);
        }
    }


    public function gerar_pdf_inscricao($dados){
        
    }

    public function corrigir_arquivo($dados){

            $documentos = Documentos::all(10000,0,'');
            

            foreach($documentos as $doc){
                $dir_file = dirname($doc->txt_location); 
                echo $dir_file.' '.$filename.' '.$doc->txt_location.'</br>';
                $filename = $doc->txt_location;
                $classe = Documentos::find_classe($doc->id_classe);
                $doc->txt_location = $dir_file.S.$classe->txt_classe.'_'.$classe->id_classe.'_'.$doc->id_doc.'.pdf';
                copy($filename,$doc->txt_location);
                $doc->save();
            }          
            return 'ok';
    }

    public static function zip_inscricao($id_processo,$id_user = 0){
            $zip = new ZipArchive();
            
            if($id_user==0) 
                $id_user = UsuariosController::get_usuario()['id_user'];

            $user = Usuarios::find($id_user);
            $dir = UPLOAD_DIR_FILES;

            $filename = str_replace(' ','_',$user->txt_nome).'_'.uniqid().'.zip';
            
            if ($zip->open($dir.$filename, ZipArchive::CREATE)!==TRUE) {
                exit("cannot open <$filename>\n");
            }
                     
            $inscricao = Inscricao::find_user($id_processo,$id_user);
            $documentos = Documentos::all_ficha($inscricao->id_ficha_enviada,1);
            
            if($documentos)
            foreach($documentos as $doc){
               $new_filename = substr($doc->txt_location,strrpos($doc->txt_location,S) + 1);
               $zip->addFile($doc->txt_location,'documentos_pessoais'.S.$new_filename);
            }          

            $documentos = Documentos::all_ficha($inscricao->id_ficha_enviada,2);
            if($documentos)
            foreach($documentos as $doc){
               $new_filename = substr($doc->txt_location,strrpos($doc->txt_location,S) + 1);
               $zip->addFile($doc->txt_location,'curriculo'.S.$new_filename);
            }          

            $dados['id_processo'] = $id_processo;
            $dados['user'] = $id_user;
            
            $inscricao = new InscricaoController();
            $arquivo = explode(';',$inscricao->ver_entregue($dados,1));

            $zip->addFile($arquivo[0],$arquivo[1]);

            //$zip->addFile($thisdir . "/too.php","/testfromfile.php");
            $zip->close();
            if($zip->status==0){
                return [$dir.$filename,$filename];
            }else return "";

    }

    public function download_zip($dados){
        $zip = InscricaoController::zip_inscricao($dados['id_processo'],isset($dados['user']) ? $dados['user'] : 0);
        if($zip == ""){

        }else{
            header('Content-type: application/zip');
            header('Content-Description: File Transfer');
            header('Content-Type: application/octet-stream');
            header('Content-Transfer-Encoding: binary');
            header('Expires: 0');
            header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
            header('Pragma: public');
            header('Content-Length: ' . filesize($zip[0])); //Absolute URL
            header('Content-Disposition: attachment;filename="'.$zip[1].'"');
            //echo $file;
            readfile($zip[0]);
            exit();
        }
    }
    
    public function generate_zips($dados){

        $inscricao = Inscricao::all_inscricao($dados);

        foreach($inscricao as $i){
            if($i->id_ficha_enviada > 0){
                $zip = InscricaoController::zip_inscricao($dados['id_processo'],$i->id_user);
                if($zip == ""){

                }else{
                    /*header('Content-type: application/zip');
                    header('Content-Description: File Transfer');
                    header('Content-Type: application/octet-stream');
                    header('Content-Transfer-Encoding: binary');
                    header('Expires: 0');
                    header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
                    header('Pragma: public');
                    header('Content-Length: ' . filesize($zip[0])); //Absolute URL
                    header('Content-Disposition: attachment;filename="'.$zip[1].'"');
                    //echo $file;
                    readfile($zip[0]);
                    exit();*/
                }
            }
        }
    }



    public function list_inscricao($dados){
        if(!isset($dados['action'])){
            $processo = Processos::find($dados['id_processo']);
            if(!isset($processo->id_processo)){
                return "erro";
            }
            return $this->view('inscricao'.S.'grade_inscricoes',['processo' => $processo]);                                
        }else{
            if($dados['action'] == "list"){
                                //Return result to jTable
                
                $result = Inscricao::all_inscricao($dados);

                $rows = array();
                $recordCount = 0;
                if($result){
                    $row = [];

                    foreach($result as $res){
                            $row['id_inscricao'] = $res->id_inscricao;
                            $row['id_inscricao2'] = $res->id_inscricao;
                            $row['txt_nome'] = $res->txt_nome;
                            $row['key_inscricao'] = $res->key_inscricao;
                            $row['dt_enviado'] = $res->dt_enviado;                        
                            $rows[] = $row;
                            $recordCount += 1;
                    }  
               }   

                $recordCount = inscricao::contar_inscricao($dados);
                $jTableResult = array();
                $jTableResult['Result'] = "OK";
                $jTableResult['TotalRecordCount'] = (int)$recordCount;
                $jTableResult['Records'] = $rows;
                print json_encode($jTableResult);       
            } 
        }
    }


    public static function enviar_email_lembrete($dados,$modelo){
        $status = criar_email($dados['to_email'],$dados['txt_nome'],'',$modelo,$dados);
        if($status==2){        
            echo $dados['to_email'].' - um e-mail de confirmação da sua inscrição foi enviado.</br>';
        }else{
            echo $dados['to_email'].' - não enviado!</br>';
        }
    }

    public function enviar_lembrete($dados){
                       
                $result = Inscricao::all_processo($dados['id_processo']);

                $rows = array();
                $recordCount = 0;
                if($result){
                    $row = [];

                    foreach($result as $res){
                            $usuario = Usuarios::find($res->id_user);
                            $row['txt_nome'] = $usuario->txt_nome;
                            $row['to_email'] = $usuario->txt_email;     
                            $date = new DateTime($res->dt_enviado);
                            $row['key'] = $res->key_inscricao;
                            $row['txt_processo'] = $res->txt_processo;
                            $row['dt_enviado'] = $date->format('d/m/Y h:i:s');      
                            if($dados['r']==1 && $res->id_ficha_enviada > 0){
                                echo 'Retificacao - ';
                                $this->enviar_email_lembrete($row,'lembrar_retificacao');
                            }else if($dados['l']==1 && $res->id_ficha_enviada == 0){
                                echo 'Envio - ';
                                $this->enviar_email_lembrete($row,'lembrar_envio');
                            }
                    }  
               }      
    }

    public function entregar($dados){
        $home = new HomeController();
        $pproc = new ProcessosController();
        if(!UsuariosController::is_logged()){
            $this->msg("Você precisa estar logado para entregar uma inscrição.",2);
            return $home->home();
        }        

        $usuario = UsuariosController::get_usuario();
        $dados['id_user'] = $usuario['id_user'];

        if(Validation_Classe::validation_entregar($dados)==null){


            $id = $dados['id_processo'];

            $aberto = ProcessosController::aberto($id);
            if($aberto==0){
                $this->msg("O período de inscrições ainda não começou. Não é possível entregar a inscrição.",2);
                return $this->dashboard($dados); 
            }else if($aberto==1){
                $this->msg("O período de inscrições se encontra encerrado! Não é possível entregar a inscrição.",2);
                return $this->dashboard($dados); 
            }

            $processo = Processos::find($id);
            $dados['processo'] = $processo->txt_processo;
            if(!isset($processo->id_processo)){
                $this->msg("A processo é inexistente.",2);
                return $home->home();
            }        

            $inscricao = Inscricao::get_id_by_processo($id,$usuario['id_usuario'],false);
            
            if(!isset($inscricao->id_inscricao)){
                $this->msg("A inscrição não é valida.",2);
                return $home->home();
            }
            $id_ficha = Ficha::clone_ficha($inscricao->id_ficha_rascunho);    
            $inscricao->id_ficha_enviada = $inscricao->id_ficha_rascunho;
            $inscricao->id_ficha_rascunho = $id_ficha;

            Documentos::copy_ficha($inscricao->id_ficha_enviada,$inscricao->id_ficha_rascunho);

            $inscricao->key_inscricao = $this->getRandomString(3,true).$this->getRandomString(2,false).$usuario['id_usuario'].$this->getRandomString(2,false).$inscricao->id_inscricao.$this->getRandomString(2,false);        
            $date = new DateTime();
            $inscricao->dt_enviado = $date->format('Y-m-d H:i:s');
            $inscricao->ip_envio = $_SERVER['REMOTE_ADDR'];
            $inscricao->save($dados);

            $dados['protocolo'] = $inscricao->key_inscricao.' ';
            $dados['data'] = $inscricao->dt_enviado;

            $this->msg("A sua inscrição foi enviada com êxito.",0);
            $this->envio_email_entregue($dados);
            return $pproc->visualizar_candidato($dados);  
        }else{
            $this->msg(print_r($dados,true) ,2);
            $this->msg("Não é possível entregar por inconsistências.",2);
            return $this->verificar($dados);
        }

    }


    public function envio_email_entregue($dados){
        $mensagem = "body";      
        
        $to_nome = UsuariosController::get_usuario()['txt_nome'];
        $to_email = UsuariosController::get_usuario()['txt_email'];
        $modelo = 'confirmacao_inscricao';
        $data = ['processo' => $dados['processo'],'protocolo' => $dados['protocolo'],'data' => $dados['data'] ];
        $status = criar_email($to_email,$to_nome,'',$modelo,$data);
        if($status==2){        
            $this->msg('Um e-mail de confirmação da sua inscrição foi enviado.',0);
        }else{
            $this->msg('Um e-mail de confirmação da sua inscrição será enviado.',0);     
        }
        return true;
    }

    public function ver_entregue_pdf($dados){
        return $this->ver_entregue($dados,true);
    }

    public function ver_entregue($dados,$pdf = 0)
    {
        if(isset($dados['pdf']))
            $pdf = $dados['pdf'];
        $home = new HomeController();
        $pproc = new ProcessosController();
        if(!UsuariosController::is_logged()){
            $this->msg("Você não está logado no sistema",2);
            return $home->home();
        }

        #if(!$this->check_auth([1],true)){
        #    return $home->home();
        #}

        $processo = Processos::find($dados['id_processo']);       
            
        $usuario = UsuariosController::get_usuario()['id_user'];
        if(isset($dados['user']))
            if($this->check_auth([3,4],true)){
                $usuario = $dados['user'];
            }
        //if($usuario==0) 
        //$usuario = UsuariosController::get_usuario()['id_user'];

        $dir = UPLOAD_DIR_FILES;

        if (!file_exists($dir.S)) {
            mkdir($dir, 0777);
        }
            
        if(isset($processo->id_processo)){
            $inscricao = Inscricao::find_user($dados['id_processo'],$usuario);
            if(isset($inscricao->id_inscricao)){
                $dados['rascunho'] = 0;
                $id_ficha = $inscricao->id_ficha_enviada;
                if($id_ficha>0){
                    
                }else{
                    $this->msg("Você ainda não realizou o envio dessa inscrição.",2);
                    return $pproc->visualizar_candidato($dados); 
                }
                $ficha = Ficha::find($id_ficha);

                if(!isset($ficha->id_ficha)){
                    $this->msg("Ocorreu um erro ao procurar sua ficha.",2);
                    return $pproc->visualizar_candidato($dados); 
                }

                $modalidade = Modalidade::find($ficha->id_modalidade);
                if(isset($modalidade->id_modalidade)){
                    $ficha->modalidade = $modalidade->txt_modalidade;
                    $ficha->sigla = $modalidade->txt_sigla;
                    $ficha->desc_modalidade = $modalidade->txt_descricao;
                }else{
                    $ficha->modalidade = "(Não selecionada)";
                    $ficha->sigla = "***";
                    $ficha->desc_modalidade = '';    
                }
                $documentos = Documentos::all_ficha($ficha->id_ficha);  

                if(isset($ficha->txt_sexo)){
                    if($ficha->txt_sexo == 1)   
                        $ficha->txt_sexo = "Masculino";
                    else if($ficha->txt_sexo == 2)   
                        $ficha->txt_sexo = "Feminino";
                    else                        
                        $ficha->txt_sexo = "Ignorado";                    
                }

                if(isset($ficha->txt_civil)){
                    if($ficha->txt_civil == 1)   
                        $ficha->txt_civil = "Casado";
                    else if($ficha->txt_civil == 2)   
                        $ficha->txt_civil = "Divorciado";
                    else if($ficha->txt_civil == 3)   
                        $ficha->txt_civil = "Separado";
                    else if($ficha->txt_civil == 4)   
                        $ficha->txt_civil = "Solteiro";
                    else if($ficha->txt_civil == 5)   
                        $ficha->txt_civil = "União estável";
                    else                        
                        $ficha->txt_civil = "Viúvo";                    
                }

               

                if(isset($ficha->txt_escolaridade)){
                    if($ficha->txt_escolaridade == 1)   
                        $ficha->txt_escolaridade = "Doutorado";
                    else if($ficha->txt_escolaridade == 2)   
                        $ficha->txt_escolaridade = "Mestrado";
                    else if($ficha->txt_escolaridade == 3)   
                        $ficha->txt_escolaridade = "Superior completo";
                    else if($ficha->txt_escolaridade == 4)   
                        $ficha->txt_escolaridade = "Superior incompleto";
                    else if($ficha->txt_escolaridade == 5)   
                        $ficha->txt_escolaridade = "Ensino médio completo";
                    else if($ficha->txt_escolaridade == 6)   
                        $ficha->txt_escolaridade = "Ensino médio incompleto";
                    else if($ficha->txt_escolaridade == 7)   
                        $ficha->txt_escolaridade = "Ensino fundamental completo";
                    else if($ficha->txt_escolaridade == 8)   
                        $ficha->txt_escolaridade = "Ensino fundamental incompleto"; 
                    else                        
                        $ficha->txt_escolaridade = "Não sei informar";                 
                }

                if(isset($ficha->txt_natural_pais)){
                    $ficha->txt_natural_pais = get_pais($ficha->txt_natural_pais)[0]['nome'];
                }

                if(isset($ficha->txt_natural_estado)){
                    $ficha->txt_natural_estado = get_uf($ficha->txt_natural_estado)[0]['nome'];
                }

                if(isset($ficha->txt_natural_cidade)){
                    $ficha->txt_natural_cidade = get_city_by_code($ficha->txt_natural_cidade);
                }

                if(isset($ficha->txt_nascimento)){
                    $ficha->txt_nascimento = date('d-m-Y', strtotime($ficha->txt_nascimento) );
                }

                if(isset($ficha->txt_rg_expedicao)){
                    $ficha->txt_rg_expedicao = date('d-m-Y', strtotime($ficha->txt_rg_expedicao) );
                }
                if(isset($ficha->txt_eleitor_emissao)){
                    $ficha->txt_eleitor_emissao = date('d-m-Y', strtotime($ficha->txt_eleitor_emissao) );
                }
                
                $documentos_pessoais = Documentos::all_ficha($ficha->id_ficha,1);  
                $documentos_curriculo = Documentos::all_ficha($ficha->id_ficha,2);  

                $matriz_classe = Documentos::matriz($ficha->id_ficha);
                if($pdf==1){

                    $dados['txt_photo'] = "/photo.php?uniq=".uniqid()."&id=".$ficha->id_ficha;
                    $dados['txt_nome'] = $ficha->txt_nome;
                    $dados['txt_email'] = $ficha->txt_email;
                    $dados['txt_nascimento'] = $ficha->txt_nascimento;
                    $dados['txt_nome_mae'] = $ficha->txt_nome_mae;
                    $dados['txt_nome_pai'] = $ficha->txt_nome_pai;
                    $dados['txt_telefone'] = $ficha->txt_telefone;
                    $dados['txt_celular'] = $ficha->txt_celular;
                    $dados['txt_civil'] = $ficha->txt_civil;
                    $dados['txt_sexo'] = $ficha->txt_sexo;
                    $dados['txt_escolaridade'] = $ficha->txt_escolaridade;
                    $dados['txt_natural_pais'] = $ficha->txt_natural_pais;
                    $dados['txt_natural_estado'] = $ficha->txt_natural_estado;
                    $dados['txt_natural_cidade'] = $ficha->txt_natural_cidade;
                    $dados['txt_cpf'] = $ficha->txt_cpf;
                    $dados['txt_rg'] = $ficha->txt_rg;
                    $dados['txt_rg_orgao'] = $ficha->txt_rg_orgao;
                    $dados['txt_rg_uf'] = $ficha->txt_rg_uf;
                    $dados['txt_rg_expedicao'] = $ficha->txt_rg_expedicao;
                    $dados['txt_titulo'] = $ficha->txt_eleitor;
                    $dados['txt_titulo_zona'] = $ficha->txt_eleitor_zona;
                    $dados['txt_titulo_secao'] = $ficha->txt_eleitor_secao;
                    $dados['txt_titulo_uf'] = $ficha->txt_eleitor_estado;
                    $dados['txt_titulo_emissao'] = $ficha->txt_eleitor_emissao;
                    $dados['txt_logadouro'] = $ficha->txt_logadouro;
                    $dados['txt_numero'] = $ficha->txt_numero;
                    $dados['txt_complemento'] = $ficha->txt_complemento;
                    $dados['txt_cep'] = $ficha->txt_cep;
                    $dados['txt_bairro'] = $ficha->txt_bairro;
                    $dados['txt_cidade'] = $ficha->txt_cidade;       
                    $dados['txt_estado'] = $ficha->txt_estado;  
                    $dados['txt_modalidade'] =  $ficha->modalidade;
                    $dados['txt_sigla'] =  $ficha->sigla;
                    $dados['txt_processo'] =  $processo->txt_processo;

                    return FichaController::criar_pdf($dados,$dir.S.'FICHA_'.str_replace(' ','_',$ficha->txt_nome).'_'.$inscricao->key_inscricao.'.pdf').';'.'FICHA_'.str_replace(' ','_',$ficha->txt_nome).'_'.$inscricao->key_inscricao.'.pdf';
                }else{
                return $this->view('ficha'.S.'form_verificar', ['matriz_classe' => $matriz_classe,'documentos_curriculo' => $documentos_curriculo, 'documentos_pessoais' => $documentos_pessoais, 'documentos' => $documentos, 'data_table' => $dados, 'processo' => $processo, 'inscricao' => $inscricao, 'ficha' => $ficha, 'usuario' => UsuariosController::get_usuario()]);                    
                }
            }else{
                $this->msg("Não há inscricao para esse processo seletivo",2);
                return $pproc->visualizar_candidato($dados);
            }


        }
        

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

    /**
     * Salvar o contato submetido pelo formulário
     */

    public function getRandomString($n,$letters) {
        if($letters)
        $characters = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
        else
        $characters = '0123456789';
        $randomString = '';
      
        for ($i = 0; $i < $n; $i++) {
            $index = rand(0, strlen($characters) - 1);
            $randomString .= $characters[$index];
        }
      
        return $randomString;
    }

    public function gerar_inscricao($dados)
    {
        //return $this->visualizar_candidato($dados);
        if(!UsuariosController::is_logged()){
            $this->msg("Você não está logado no sistema",2);
            return $home->home();
        }
        $usuario = UsuariosController::get_usuario();

        $inscricao = new Inscricao;
        $inscricao->id_processo = $dados['id_processo'];
        $inscricao->id_user = isset($usuario['id_user']) ? $usuario['id_user'] : 0;
        
        if(ProcessosController::aberto($inscricao->id_processo)!=2){
            $this->msg('Este processo seletivo não está aberto.',2);
            $control = new ProcessosController();
            return $control->visualizar_candidato($dados);
        }
        $data = array();
        $data['id_processo'] =  $inscricao->id_processo;
        $data['id_user'] = $inscricao->id_user;      

        //if(Validation_Classe::validation_processo($data)==null){
        if($inscricao->save($dados)){                    
            $rascunho = FichaController::ficha_rascunho($inscricao->id_inscricao);
            if($rascunho!=null){
                $inscricao->id_ficha_rascunho = $rascunho->id_ficha;
                $date = new DateTime();
                $inscricao->dt_criacao = $date->format('d/m/Y h:i:s');
                if($inscricao->save($dados)){
                    $this->msg('Sua inscrição para o processo seletivo foi iniciada corretamente.',0);   
                    $dados['id_ficha'] = $rascunho->id_ficha;
                    $proc = new FichaController();
                    return $proc->editar($dados);
                }else{
                    $this->msg('Sua inscrição para o processo seletivo foi iniciada parcialmente.',0); 
                    $dados['id_ficha'] = $rascunho->id_ficha;
                    $proc = new ProcessosController();
                    return $proc->visualizar_candidato($dados);  
                }
                
            }else{
                $this->msg('Problema para inicializar ficha',0);  
                $control = new ProcessosController();
                return $control->visualizar_candidato($dados);          
            }    
        }else{
            $this->msg('Ocorreu um erro ao tentar iniciar inscrição',2);
            $control = new ProcessosController();
            return $control->visualizar_candidato($dados);
        }               
        

        //}else{                
        //    $this->msg('Não foi possível salvar o registro por incosistências.',2);
            
        //    return $this->listar($dados);
        //}
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