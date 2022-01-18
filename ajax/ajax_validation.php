<?php
    $gpath = $_SERVER['DOCUMENT_ROOT'];
    if($gpath[0]=='/')
    define('S','/');
    else
    define('S','\\');
    define('GPATH',$gpath.S);
    require_once(GPATH.'conf.php');
    require_once(GPATH.'utils'.S.'validation.php');
    require_once(GPATH.'controller'.S.'controller.php');
    require_once(GPATH.'controller'.S.'fichacontroller.php');
    require_once(GPATH.'model'.S.'ficha.php');
    #require_once(GPATH.'model'.S.'UsuariosController.php');
    
    $data = $_REQUEST;

    if(!isset($data['id_user']))
    if(UsuariosController::is_logged()){
        $data['id_user'] = UsuariosController::get_usuario()['id_user'];
    }else{
        $data['id_user'] = 0;
    }
   
    session_start();
    if($data['classe'] == 'usuario'){
        $retorna = Validation_Classe::validation_usuario($data);
    }else if($data['classe'] == 'registro1'){
        $retorna = Validation_Classe::validation_registro1($data);
    }else if($data['classe'] == 'ativar'){
        $retorna = Validation_Classe::validation_ativar($data);
    }else if($data['classe'] == 'senha'){
        $retorna = Validation_Classe::validation_senha($data);
    }else if($data['classe'] == 'perfil'){
        $retorna = Validation_Classe::validation_perfil($data);
    }else if($data['classe'] == 'processo'){
        $retorna = Validation_Classe::validation_processo($data);
    }else if($data['classe'] == 'recurso'){
        $retorna = Validation_Classe::validation_recurso($data);
    }else if($data['classe'] == 'status'){
        $retorna = Validation_Classe::validation_status($data);
    }else if($data['classe'] == 'cronograma'){
        $retorna = Validation_Classe::validation_cronograma($data);
    }else if($data['classe'] == 'upload'){
        $retorna = Validation_Classe::validation_upload($data);
    }else if($data['classe'] == 'upload_recurso'){
        $retorna = Validation_Classe::validation_upload_recurso($data);
    }else if($data['classe'] == 'ficha'){
        $c = new Controller();
        
        $ficha = Ficha::find($data['id_ficha']);
        $photo = FichaController::check_photo($data["id"],$ficha);
        $data['txt_photo'] = $photo[1];     
        $retorna = Validation_Classe::validation_ficha($data);

    }else if($data['classe'] == 'entregar'){
        $retorna = Validation_Classe::validation_entregar($data);
    }
    if($retorna==null){
        echo '200';
        exit;
    }else{
        echo json_encode($retorna);
        exit;
    }   
?>