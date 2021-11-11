<?php

/**
 * Classe Contato - baseado no modelo Active Record (Simplificado) 
 * @author Alexandre Bezerra Barbosa
 */

require_once(GPATH.'database'.S.'conexao.php');
require_once(GPATH.'model'.S.'status.php');

class Classes
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
}
 

class Documentos
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
        unset($colunas['id_ficha']);
        $inserindo = false;
        if (!isset($this->id_doc)) {
            $query = "INSERT INTO tab_docs (".
                implode(', ', array_keys($colunas)).
                ") VALUES (".
                implode(', ', array_values($colunas)).");";
            $inserindo = true;
        } else {
            foreach ($colunas as $key => $value) {
                if ($key !== 'id_doc') {
                    $definir[] = "{$key}={$value}";
                }
            }
            $query = "UPDATE tab_docs SET ".implode(', ', $definir)." WHERE id_doc='{$this->id_doc}';";                        
            #$controller = new Controller();
            #$controller->msg($query,2);   
        }
        if ($conexao = Conexao::getInstance()) {            
            $stmt = $conexao->prepare($query);
            if ($stmt->execute()) {
                if (!isset($this->id_doc)){
                    $this->id_doc = $conexao->lastInsertId();
                }
                if($inserindo) return $this->associar_doc($this->id_doc,$this->id_ficha);
                return true;
            }else{
            }            
        }   
        return false;
    }

    public static function copy_ficha($id_ficha,$new_ficha)
    {
        $query = "INSERT INTO tab_doc_ficha
                (id_doc, id_ficha)
                SELECT 
                    id_doc, '{$new_ficha}' as id_ficha
                FROM 
                    tab_doc_ficha
                WHERE 
                    id_ficha = '{$id_ficha}'";

        if ($conexao = Conexao::getInstance()) {            
            $stmt = $conexao->prepare($query);
            if ($stmt->execute()) {
                return true;
            }else{
            }            
        }   
        return false;
    }

    public function insert_new_doc()
    {
        $colunas = $this->preparar($this->atributos);
        $query = "INSERT INTO tab_docs (id_user,txt_filename,id_classe) VALUES ('".$this->id_user."','".$this->txt_filename."','".$this->id_classe."')";
            if ($conexao = Conexao::getInstance()) {
            $stmt = $conexao->prepare($query);
            if ($stmt->execute()) {
                if (!isset($this->id_doc)){
                    $this->id_doc = $conexao->lastInsertId();
                }
                return $this->associar_doc($this->id_doc,$this->id_ficha);
            }else{
            }            
        }   
        return false;
    }

    public function associar_doc($doc,$id_ficha)
    {
        $colunas = $this->preparar($this->atributos);
        $query = "INSERT INTO tab_doc_ficha (id_doc,id_ficha) VALUES ('".$doc."','".$id_ficha."')";
            if ($conexao = Conexao::getInstance()) {
            $stmt = $conexao->prepare($query);
            if ($stmt->execute()) {
                if (!isset($this->id_doc)){
                    $this->id_doc = $conexao->lastInsertId();
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

        $conta = Documentos::count();

        $pags = ceil($conta/5);
        if($pag>$pags) $pag = $pags;

        $offset = ($pag-1)*$num;
        if($offset <0) $offset  = 0;
        $limit = $num;

        $stmt    = $conexao->prepare("SELECT tab_docs.*, tab_docs.id_classe as classe FROM tab_docs LEFT JOIN tab_classe ON tab_docs.id_classe = tab_classe.id_classe {$torder} LIMIT {$offset},{$limit}  ;");
        $result  = array();
        $stmt->setFetchMode(PDO::FETCH_ASSOC);
        if ($stmt->execute()) {
            while ($rs = $stmt->fetchObject(Documentos::class)) {
                $result[] = $rs;
            }
        }
        if (count($result) > 0) {
            return $result;
        }
        return false;
    }


    public static function all_ficha($ficha,$cat = 0,$orderby='',$pag=1,$num=1000)
    {                      
        $conexao = Conexao::getInstance();                      
        
        if($cat>0)
        $stmt    = $conexao->prepare("SELECT tab_docs.*, tab_docs.id_classe as classe, tab_classe.txt_classe as txt_classe FROM tab_docs LEFT JOIN tab_classe ON tab_docs.id_classe = tab_classe.id_classe LEFT JOIN tab_doc_ficha ON tab_docs.id_doc = tab_doc_ficha.id_doc WHERE tab_doc_ficha.id_ficha = '{$ficha}' and  tab_classe.id_categoria = '{$cat}' ORDER BY tab_classe.id_classe;");
        else
        $stmt    = $conexao->prepare("SELECT tab_docs.*, tab_docs.id_classe as classe, tab_classe.txt_classe as txt_classe FROM tab_docs LEFT JOIN tab_classe ON tab_docs.id_classe = tab_classe.id_classe LEFT JOIN tab_doc_ficha ON tab_docs.id_doc = tab_doc_ficha.id_doc WHERE tab_doc_ficha.id_ficha = '{$ficha}' ORDER BY tab_classe.id_classe;");
        $result  = array();
        $stmt->setFetchMode(PDO::FETCH_ASSOC);
        if ($stmt->execute()) {
            while ($rs = $stmt->fetchObject(Documentos::class)) {
                $result[] = $rs;
            }
        }
        if (count($result) > 0) {
            return $result;
        }
        return false;
    }


    public static function matriz($ficha)
    {                      
        $conexao = Conexao::getInstance();                      
        
        $stmt    = $conexao->prepare("SELECT tab_classe.num_maximo as num_maximo, tab_classe.txt_classe, tab_classe.num_ponto,  IFNULL(matriz.num_doc,0) as num_docs, IFNULL(matriz.total,0) as total FROM tab_classe LEFT JOIN (SELECT *, num_doc*num_ponto as total FROM (SELECT tab_classe.*, COUNT(tab_docs.id_doc) as num_doc FROM tab_classe LEFT JOIN tab_docs ON tab_classe.id_classe = tab_docs.id_classe LEFT JOIN tab_doc_ficha ON tab_docs.id_doc = tab_doc_ficha.id_doc WHERE tab_classe.id_categoria = 2 and tab_doc_ficha.id_ficha = '{$ficha}' GROUP BY tab_docs.id_classe) matriz) as matriz ON tab_classe.id_classe = matriz.id_classe WHERE tab_classe.id_categoria = 2 ORDER BY tab_classe.id_classe");

        $result  = array();
        $stmt->setFetchMode(PDO::FETCH_ASSOC);
        if ($stmt->execute()) {
            while ($rs = $stmt->fetchObject(Classes::class)) {
                $result[] = $rs;
            }
            //return;
        }
        if (count($result) > 0) {
            return $result;
        }
        return false;
    }


    public static function get_vagas($id_processo)
    {         
             
        $conexao = Conexao::getInstance();                      
        $stmt    = $conexao->prepare("SELECT tab_vagas.*, tab_modalidade.txt_modalidade as modalidade, tab_modalidade.txt_descricao as descricao, tab_modalidade.txt_sigla as sigla FROM tab_vagas LEFT JOIN tab_modalidade ON tab_vagas.id_modalidade = tab_modalidade.id_modalidade WHERE tab_vagas.id_processo = '{$id_processo}';");
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


    public static function all_classes($tipo = -1)
    {
        $conexao = Conexao::getInstance();
        if($tipo == -1)
        $stmt    = $conexao->prepare("SELECT * FROM tab_classe;");
        else
        $stmt    = $conexao->prepare("SELECT * FROM tab_classe WHERE id_categoria = $tipo;");

        $result  = array();
        if ($stmt->execute()) {
            while ($rs = $stmt->fetchObject(Classes::class)) {
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
        $stmt    = $conexao->prepare("SELECT COUNT(*) FROM tab_docs;");        
        if($stmt->execute()){
            $count = $stmt->fetchColumn();
        }
        $stmt->closeCursor();
        if ($count) {
            return (int) $count;
        }
        return false;
    }

    public static function count_classe($id_classe,$id_ficha)
    {
        $conexao = Conexao::getInstance();        
        $stmt = $conexao->prepare("SELECT COUNT(*) FROM tab_docs LEFT JOIN tab_doc_ficha ON tab_docs.id_doc = tab_doc_ficha.id_doc WHERE tab_doc_ficha.id_ficha = '{$id_ficha}' and tab_docs.id_classe = '{$id_classe}';");
        
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
    public static function find($id_doc,$ficha = false,$id_ficha = -1)
    {
        if($ficha && $id_ficha >= 1){
            $where = " AND tab_doc_ficha.id_ficha = '".$id_ficha."'";
        } else $where = "";

        $conexao = Conexao::getInstance();
        if(!$ficha)
            $stmt    = $conexao->prepare("SELECT tab_docs.* FROM tab_docs WHERE id_doc = '{$id_doc}' {$where};");
        else
            $stmt    = $conexao->prepare("SELECT tab_docs.*, tab_doc_ficha.id_ficha as id_ficha FROM tab_docs LEFT JOIN tab_doc_ficha ON tab_docs.id_doc = tab_doc_ficha.id_doc WHERE tab_docs.id_doc = '{$id_doc}' {$where};");
            
        if ($stmt->execute()) {
            if ($stmt->rowCount() > 0) {
                $resultado = $stmt->fetchObject('Documentos');
                if ($resultado) {
                    
                    return $resultado;
                }
            }
        }
        return false;
    }

    public static function find_classe($id_classe)
    {
        
        $conexao = Conexao::getInstance();
         $stmt    = $conexao->prepare("SELECT * FROM tab_classe WHERE id_classe = '{$id_classe}';");
       
        if ($stmt->execute()) {
            if ($stmt->rowCount() > 0) {
                $resultado = $stmt->fetchObject('Classes');
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
    public static function destroy($id_doc)
    {
        $conexao = Conexao::getInstance();
        if ($conexao->exec("DELETE FROM tab_docs WHERE id_doc='{$id_doc}';")) {
            return true;
        }
        return false;

    }  

    public static function destroy_relation($id_doc,$id_ficha)
    {
        $conexao = Conexao::getInstance();
        if ($conexao->exec("DELETE FROM tab_doc_ficha WHERE id_doc='{$id_doc}' and id_ficha='{$id_ficha}';")) {
            return true;
        }
        return false;

    }  
    
}
?>