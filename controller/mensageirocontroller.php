<?php
require_once('controller.php');
require(GPATH.'model'.S.'mensageiro.php');

class MensageiroController extends Controller
{
 
    /**
     * Lista os contatos
     */

    public function listar_session()
    {    
        return $this->view('lista_mensagens');
    }

    public function salvar($msg,$tipo)
    {
        
    }
 
    /**
     * Apagar um contato conforme o id informado
     */
    public function excluir_session($mensagens)
    {
        $msg->id;
    }
}
?>