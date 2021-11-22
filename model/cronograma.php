<?php

/**
 * Classe Contato - baseado no modelo Active Record (Simplificado) 
 * @author Alexandre Bezerra Barbosa
 */

require_once(GPATH.'database'.S.'conexao.php');
require_once(GPATH.'model'.S.'status.php');


class Cronograma
{
    private $atributos;
 
    public function __construct()
    {
 
    }
 
    public function __set(string $atributo, $valor)
    {
        $this->atributos[$atributo] = $valor;
        return $this;
    }
 
    public function __get(string $atributo)
    {
        return $this->atributos[$atributo];
    }
 
    public function __isset($atributo)
    {
        return isset($this->atributos[$atributo]);
    }
 
    /**
     * Salvar o contato
     * @return boolean
     */
    public function save($dados = null)
    {
        $colunas = $this->preparar($this->atributos);
        if (!isset($this->id_cronograma)) {
            $query = "INSERT INTO tab_cronograma (".
                implode(', ', array_keys($colunas)).
                ") VALUES (".
                implode(', ', array_values($colunas)).");";
        } else {
            foreach ($colunas as $key => $value) {
                if ($key !== 'id_cronograma') {
                    $definir[] = "{$key}={$value}";
                }
            }
            $query = "UPDATE tab_cronograma SET ".implode(', ', $definir)." WHERE id_cronograma='{$this->id_cronograma}';";                        
            #$controller = new Controller();
            #$controller->msg($query,2);   
        }
        if ($conexao = Conexao::getInstance()) {            
            $stmt = $conexao->prepare($query);
            if ($stmt->execute()) {
                return true;
            }else{
            }            
        }   
        return false;
    }
 
    /**
     * Tornar valores aceitos para sintaxe SQL
     * @param type $dados
     * @return string
     */
    private function escapar($dados)
    {
        if (is_string($dados) & !empty($dados)) {
            return "'".addslashes($dados)."'";
        } elseif (is_bool($dados)) {
            return $dados ? 'TRUE' : 'FALSE';
        } elseif ($dados !== '') {
            return $dados;
        } else {
            return 'NULL';
        }
    }
 
    /**
     * Verifica se dados são próprios para ser salvos
     * @param array $dados
     * @return array
     */
    private function preparar($dados)
    {
        $resultado = array();
        foreach ($dados as $k => $v) {
            if (is_scalar($v)) {
                $resultado[$k] = $this->escapar($v);
            }
        }
        return $resultado;
    }
 
    /**
     * Retorna uma lista de contatos
     * @return array/boolean
     */
    public static function all($id_processo = -1,$num=5,$pag=0,$orderby='')
    {         
             
        $conexao = Conexao::getInstance();                      
        $torder = "";
        if($orderby!=''){
            $orderbyexp = explode("#",$orderby);
            foreach($orderbyexp as $o){            
                if($torder=="") $torder = " ORDER BY ";
                else $torder.", ";
                $e = explode("|",$o);
                $torder.=$e[0].' '.$e[1];
            }
        }
        $torder = " ORDER BY id_cronograma ASC ";

        $conta = Cronograma::count();

        $pags = ceil($conta/5);
        if($pag>$pags) $pag = $pags;

        $offset = ($pag-1)*$num;
        if($offset <0) $offset  = 0;
        $limit = $num;

        $stmt    = $conexao->prepare("SELECT tab_cronograma.*, tab_status.txt_status as status FROM tab_cronograma LEFT JOIN tab_status ON tab_cronograma.id_status = tab_status.id_status WHERE id_processo='{$id_processo}' {$torder} LIMIT {$offset},{$limit}  ;");
        $result  = array();
        $stmt->setFetchMode(PDO::FETCH_ASSOC);
        if ($stmt->execute()) {
            while ($rs = $stmt->fetchObject(Usuarios::class)) {
                $result[] = $rs;
            }
        }
        if (count($result) > 0) {
            return $result;
        }
        return false;
    }


    public static function all_status()
    {
        $conexao = Conexao::getInstance();
        $stmt    = $conexao->prepare("SELECT * FROM tab_status;");
        $result  = array();
        if ($stmt->execute()) {
            while ($rs = $stmt->fetchObject(Status::class)) {
                $result[] = $rs;
            }
        }
        if (count($result) > 0) {
            return $result;
        }
        return false;
    }
 
    /**
     * Retornar o número de registros
     * @return int/boolean
     */
    public static function count()
    {
        $conexao = Conexao::getInstance();        
        $stmt    = $conexao->prepare("SELECT COUNT(*) FROM tab_cronograma;");        
        if($stmt->execute()){
            $count = $stmt->fetchColumn();
        }
        $stmt->closeCursor();
        if ($count) {
            return (int) $count;
        }
        return false;
    }
 
    /**
     * Encontra um recurso pelo id
     * @param type $id
     * @return type
     */
    public static function find($id_cronograma,$with_role = true)
    {
        $conexao = Conexao::getInstance();
        if($with_role)
            $stmt    = $conexao->prepare("SELECT tab_cronograma.*,  tab_status.txt_status as status FROM tab_cronograma LEFT JOIN tab_status ON tab_cronograma.id_status = tab_status.id_status WHERE id_cronograma={$id_cronograma};");
        else{
            $stmt    = $conexao->prepare("SELECT tab_cronograma.* FROM tab_cronograma WHERE id_cronograma='{$id_cronograma}';");
        }
        //$controller = new MensageiroController();
        //$controller->msg("SELECT tab_cronograma.*,  tab_status.txt_status as status FROM tab_cronograma LEFT JOIN tab_status ON tab_cronograma.id_status = tab_status.id_status WHERE id_cronograma={$id_cronograma};",1);
        if ($stmt->execute()) {
            if ($stmt->rowCount() > 0) {
                $resultado = $stmt->fetchObject('Cronograma');
                if ($resultado) {
                    return $resultado;
                }
            }
        }
        return false;
    }

    public static function get_act_event($id_processo)
    {
        $conexao = Conexao::getInstance();
        $stmt    = $conexao->prepare("SELECT tab_cronograma.*, tab_status.txt_status as status, tab_status.bl_publicado as bl_publicado, tab_status.bl_aberto as bl_aberto, tab_status.bl_recurso as bl_recurso FROM tab_cronograma LEFT JOIN tab_status ON tab_cronograma.id_status = tab_status.id_status LEFT JOIN tab_processos ON tab_cronograma.id_processo = tab_processos.id_processo WHERE (dt_inicio > tab_processos.id_data_cronograma or tab_processos.id_data_cronograma = null) and DATE_SUB(dt_inicio,INTERVAL 2 HOUR) <= NOW() and tab_cronograma.id_processo={$id_processo} ORDER BY dt_inicio ASC LIMIT 1");
        
        //$controller = new MensageiroController();
        //$controller->msg("SELECT tab_cronograma.*,  tab_status.txt_status as status FROM tab_cronograma LEFT JOIN tab_status ON tab_cronograma.id_status = tab_status.id_status WHERE id_cronograma={$id_cronograma};",1);
        if ($stmt->execute()) {
            if ($stmt->rowCount() > 0) {
                $resultado = $stmt->fetchObject('Cronograma');
                if ($resultado) {
                    return $resultado;
                }
            }
        }
        return false;
    }
   
    /**
     * Destruir um recurso
     * @param type $id
     * @return boolean
     */
    public static function destroy($id_cronograma)
    {
        $conexao = Conexao::getInstance();
        if ($conexao->exec("DELETE FROM tab_cronograma WHERE id_cronograma='{$id_cronograma}';")) {
            return true;
        }
        return false;

    }  
    
}
?>