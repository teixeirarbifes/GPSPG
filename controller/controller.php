<?php
require_once(GPATH.'request'.S.'request.php');
require_once(GPATH.'controller'.S.'mensageirocontroller.php');

if(!is_array($_SESSION['mensagens'])) $_SESSION['mensagens'] = array();
class Controller
{

    public $request;
 
    public function __construct()
    {
        $this->request = new Request;
    }
 
    public function view($arquivo, $array = null)
    {
        if (!is_null($array)) {
            foreach ($array as $var => $value) {                                
                global ${$var};
                ${$var} = $value;
            }            
        }        
        ob_start();        
        include GPATH."view".S."{$arquivo}.php";
        $result = ob_get_contents();
        ob_end_clean();
        
        return $result;
    }

    public function msg($msg,$tipo){
        //if(!is_array($_SESSION['mensagens'])) $_SESSION['mensagens'] = array();
        $data = Session::getInstance(); 
        $data->mensagens->append([$msg,$tipo]);        
    }

    public function check_auth($roles,$ismsg=false){
        $this_role = UsuariosController::get_role();
        foreach ($roles as $role){
            if($role == $this_role){
                return true;
            }
        }
        if($ismsg){
            $msg = new MensageiroController();
            echo($ismsg);
            $msg->msg("Não tem autorização para esse recurso.",2);
        }
        return false;
    }

    public function redirect($url){
        exit(header("location: ".$url));
    }
}

?>
