<?php

/**
 * Classe Contato - baseado no modelo Active Record (Simplificado) 
 * @author Alexandre Bezerra Barbosa
 */

require_once(GPATH.'database'.S.'conexao.php');
require_once(GPATH.'model'.S.'status.php');


class Ficha
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
 

    public static function clone_ficha($id_ficha){
        $ficha = Ficha::find($id_ficha);
        $ficha->id_ficha = null;
        $ficha->save();
        return $ficha->id_ficha;
    }

    /**
     * Salvar o contato
     * @return boolean
     */
    public function save()
    {
        $colunas = $this->preparar($this->atributos);
        if (!isset($this->id_ficha) || $this->id_ficha==null) {
            unset($colunas['id_ficha']);
            $query = "INSERT INTO tab_ficha (".
                implode(', ', array_keys($colunas)).
                ") VALUES (".
                implode(', ', array_values($colunas)).");";
        } else {
            foreach ($colunas as $key => $value) {
                if ($key !== 'id_ficha') {
                    $definir[] = "{$key}={$value}";
                }
            }
            $query = "UPDATE tab_ficha SET ".implode(', ', $definir)." WHERE id_ficha='{$this->id_ficha}';";                        
            #$controller = new Controller();
            #$controller->msg($query,2);   
        }
        if ($conexao = Conexao::getInstance()) {            
            $stmt = $conexao->prepare($query);
            if ($stmt->execute()) {
                if (!isset($this->id_ficha)){
                    $this->id_ficha = $conexao->lastInsertId();
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
    public static function all($num=5,$pag=0,$orderby='')
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

        $conta = Ficha::count();

        $pags = ceil($conta/5);
        if($pag>$pags) $pag = $pags;

        $offset = ($pag-1)*$num;
        if($offset <0) $offset  = 0;
        $limit = $num;

        $stmt    = $conexao->prepare("SELECT * FROM tab_inscricao {$torder} LIMIT {$offset},{$limit}  ;");
        $result  = array();
        $stmt->setFetchMode(PDO::FETCH_ASSOC);
        if ($stmt->execute()) {
            while ($rs = $stmt->fetchObject(Ficha::class)) {
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
        $stmt    = $conexao->prepare("SELECT COUNT(*) FROM tab_ficha;");        
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

    public static function get_id_by_inscricao($id_inscricao)
    {
        $conexao = Conexao::getInstance();
        $stmt    = $conexao->prepare("SELECT * FROM tab_ficha WHERE id_inscricao='{$id_inscricao}';");

        if ($stmt->execute()) {
            if ($stmt->rowCount() > 0) {
                $resultado = $stmt->fetchObject('Ficha');
                if ($resultado) {
                    return $resultado;
                }
            }
        }
        return false;
    }
   
    public static function find($id_ficha)
    {
        $conexao = Conexao::getInstance();
         $stmt = $conexao->prepare("SELECT * FROM tab_ficha WHERE id_ficha='{$id_ficha}';");
        //$controller = new MensageiroController();
        //$controller->msg("SELECT tab_processos.*,  tab_status.txt_status as status FROM tab_processos LEFT JOIN tab_status ON tab_processos.id_status = tab_status.id_status WHERE id_processo={$id_processo};",1);
        if ($stmt->execute()) {
            if ($stmt->rowCount() > 0) {
                $resultado = $stmt->fetchObject('Ficha');
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
    public static function destroy($id_inscricao)
    {
        $conexao = Conexao::getInstance();
        if ($conexao->exec("DELETE FROM tab_inscricao WHERE id_inscricao='{$id_inscricao}';")) {
            return true;
        }
        return false;

    }  
    
}
?>