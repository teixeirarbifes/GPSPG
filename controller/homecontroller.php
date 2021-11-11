<?php
require_once('controller.php');
require_once(GPATH.'controller'.S.'processoscontroller.php');

class HomeController extends Controller
{
 
    /**
     * Lista os contatos
     */

    public function home()
    {    
        $proc = new ProcessosController();
        return $proc->listar_candidato();
    }

}
?>