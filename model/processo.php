<?php

/**
 * Classe Contato - baseado no modelo Active Record (Simplificado) 
 * @author Alexandre Bezerra Barbosa
 */

require_once(GPATH.'database'.S.'conexao.php');
require_once(GPATH.'model'.S.'status.php');


class Processos
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
        if (!isset($this->id_processo)) {
            $query = "INSERT INTO tab_processos (".
                implode(', ', array_keys($colunas)).
                ") VALUES (".
                implode(', ', array_values($colunas)).");";
        } else {
            foreach ($colunas as $key => $value) {
                if ($key !== 'id_processo') {
                    $definir[] = "{$key}={$value}";
                }
            }
            $query = "UPDATE tab_processos SET ".implode(', ', $definir)." WHERE id_processo='{$this->id_processo}';";                        
            #$controller = new Controller();
            #$controller->msg($query,2);   
        }
        if ($conexao = Conexao::getInstance()) {            
            $stmt = $conexao->prepare($query);
            if ($stmt->execute()) {
                if (!isset($this->id_processo)){
                    $this->id_processo = $conexao->lastInsertId();
                }
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
    public static function all($num=5,$pag=0,$orderby='',$semstatus = false,$publicado = false)
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

        $conta = Processos::count();

        $pags = ceil($conta/5);
        if($pag>$pags) $pag = $pags;

        $offset = ($pag-1)*$num;
        if($offset <0) $offset  = 0;
        $limit = $num;
        if($publicado) $where = " WHERE tab_processos.bl_publicado = true ";
        else $where = "";
        if($semstatus)
        $stmt    = $conexao->prepare("SELECT tab_processos.* FROM tab_processos {$where} {$torder} LIMIT {$offset},{$limit} ;");
        else
        $stmt    = $conexao->prepare("SELECT tab_processos.*, tab_status.txt_status as status FROM tab_processos LEFT JOIN tab_status ON tab_processos.id_status = tab_status.id_status {$where} {$torder} LIMIT {$offset},{$limit}  ;");
        $result  = array();
        $stmt->setFetchMode(PDO::FETCH_ASSOC);
        if ($stmt->execute()) {
            while ($rs = $stmt->fetchObject(Processos::class)) {
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
        $stmt    = $conexao->prepare("SELECT COUNT(*) FROM tab_processos;");        
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
    public static function find($id_processo,$with_role = true)
    {
        $conexao = Conexao::getInstance();
        if($with_role)
            $stmt    = $conexao->prepare("SELECT tab_processos.*,  tab_status.txt_status as status FROM tab_processos LEFT JOIN tab_status ON tab_processos.id_status = tab_status.id_status WHERE id_processo={$id_processo};");
        else{
            $stmt    = $conexao->prepare("SELECT tab_processos.* FROM tab_processos WHERE id_processo='{$id_processo}';");
        }
        //$controller = new MensageiroController();
        //$controller->msg("SELECT tab_processos.*,  tab_status.txt_status as status FROM tab_processos LEFT JOIN tab_status ON tab_processos.id_status = tab_status.id_status WHERE id_processo={$id_processo};",1);
        if ($stmt->execute()) {
            if ($stmt->rowCount() > 0) {
                $resultado = $stmt->fetchObject('Processos');
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
    public static function destroy($id_processo)
    {
        $conexao = Conexao::getInstance();
        if ($conexao->exec("DELETE FROM tab_processos WHERE id_processo='{$id_processo}';")) {
            return true;
        }
        return false;

    }  
    
}
?>