<?php

/**
 * Classe Contato - baseado no modelo Active Record (Simplificado) 
 * @author Alexandre Bezerra Barbosa
 */

require_once(GPATH.'database'.S.'conexao.php');
require_once(GPATH.'model'.S.'status.php');


class Inscricao
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
        if (!isset($this->id_inscricao)) {
            $query = "INSERT INTO tab_inscricao (".
                implode(', ', array_keys($colunas)).
                ") VALUES (".
                implode(', ', array_values($colunas)).");";
        } else {
            foreach ($colunas as $key => $value) {
                if ($key !== 'id_inscricao' && $key !== 'data_enviado'  && $key !== 'hora_enviado') {
                    $definir[] = "{$key}={$value}";
                }
            }
            $query = "UPDATE tab_inscricao SET ".implode(', ', $definir)." WHERE id_inscricao='{$this->id_inscricao}';";                        
            #$controller = new Controller();
            #$controller->msg($query,2);   
        }
        if ($conexao = Conexao::getInstance()) {            
            $stmt = $conexao->prepare($query);
            if ($stmt->execute()) {
                if (!isset($this->id_inscricao)){
                    $this->id_inscricao = $conexao->lastInsertId();
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

        $conta = Inscricao::count();

        $pags = ceil($conta/5);
        if($pag>$pags) $pag = $pags;

        $offset = ($pag-1)*$num;
        if($offset <0) $offset  = 0;
        $limit = $num;

        $stmt    = $conexao->prepare("SELECT tab_inscricao.*, tab_processos.txt_processo as status FROM tab_inscricao LEFT JOIN tab_processos ON tab_inscricao.id_processo = tab_processo.id_processo {$torder} LIMIT {$offset},{$limit}  ;");
        $result  = array();
        $stmt->setFetchMode(PDO::FETCH_ASSOC);
        if ($stmt->execute()) {
            while ($rs = $stmt->fetchObject(Inscricao::class)) {
                $result[] = $rs;
            }
        }
        if (count($result) > 0) {
            return $result;
        }
        return false;
    }


   public static function all_inscricao($dados)
   {                     
       $conexao = Conexao::getInstance();                      
      
       $id_processo = $dados['id_processo'];
       $inscrito = isset($dados['inscrito']) && $dados['inscrito'] == 1 ? " AND dt_enviado != '0000-00-00 00:00:00' " : "";
       $stmt    = $conexao->prepare("SELECT tab_users.*, tab_inscricao.*, tab_processos.txt_processo as txt_processo FROM tab_inscricao LEFT JOIN tab_processos ON tab_inscricao.id_processo = tab_processos.id_processo LEFT JOIN tab_users ON tab_inscricao.id_user = tab_users.id_user WHERE tab_processos.id_processo = '{$id_processo}' {$inscrito};");
       $result  = array();
       $stmt->setFetchMode(PDO::FETCH_ASSOC);
       if ($stmt->execute()) {
           while ($rs = $stmt->fetchObject(Inscricao::class)) {
               $result[] = $rs;
           }
       }
       if (count($result) > 0) {
           return $result;
       }
       return false;
   }


    public static function all_user($id_user,$num=5,$pag=0,$orderby='')
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

        $conta = Inscricao::count();

        $pags = ceil($conta/5);
        if($pag>$pags) $pag = $pags;

        $offset = ($pag-1)*$num;
        if($offset <0) $offset  = 0;
        $limit = $num;
        //if($offset<=0) $offset = 1;
        $stmt    = $conexao->prepare("SELECT tab_inscricao.*, tab_processos.txt_processo as txt_processo FROM tab_inscricao INNER JOIN tab_processos ON tab_inscricao.id_processo = tab_processos.id_processo WHERE tab_inscricao.id_user = '{$id_user}' {$torder} LIMIT {$offset},{$limit}  ;");
        $result  = array();
        $stmt->setFetchMode(PDO::FETCH_ASSOC);
        if ($stmt->execute()) {
            while ($rs = $stmt->fetchObject(Inscricao::class)) {
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
        $stmt    = $conexao->prepare("SELECT COUNT(*) FROM tab_inscricao;");        
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

    public static function get_id_by_processo($id_processo,$id_user,$hora_format = true)
    {
        $conexao = Conexao::getInstance();
        if($hora_format)
        $stmt    = $conexao->prepare("SELECT DATE_FORMAT(dt_enviado, '%d de %M de %Y') as data_enviado, DATE_FORMAT(dt_enviado, '%H:%i:%S') as hora_enviado, tab_inscricao.* FROM tab_inscricao WHERE id_processo='{$id_processo}' AND id_user='{$id_user}';");
        else
        $stmt    = $conexao->prepare("SELECT DATE_FORMAT(dt_enviado, '%d de %M de %Y') as data_enviado, DATE_FORMAT(dt_enviado, '%H:%i:%S') as hora_enviado, tab_inscricao.* FROM tab_inscricao WHERE id_processo='{$id_processo}' AND id_user='{$id_user}';");

        if ($stmt->execute()) {
            if ($stmt->rowCount() > 0) {
                $resultado = $stmt->fetchObject('Inscricao');
                if ($resultado) {
                    return $resultado;
                }
            }
        }
        return false;
    }

    public static function get_id_by_ficha_rascunho($id_ficha)
    {
        $conexao = Conexao::getInstance();
        $stmt    = $conexao->prepare("SELECT * FROM tab_inscricao WHERE id_ficha_rascunho='{$id_ficha}';");

        if ($stmt->execute()) {
            if ($stmt->rowCount() > 0) {
                $resultado = $stmt->fetchObject('Inscricao');
                if ($resultado) {
                    return $resultado;
                }
            }
        }
        return false;
    }
   
    public static function find($id_inscricao,$with_role = true)
    {
        $conexao = Conexao::getInstance();
        if($with_role)
            $stmt    = $conexao->prepare("SELECT tab_inscricao.*,  tab_processos.txt_processo as processo FROM tab_inscricao LEFT JOIN tab_processos ON tab_inscricao.id_processo = tab_processos.id_processo WHERE id_inscricao={$id_inscricao};");
        else{
            $stmt    = $conexao->prepare("SELECT tab_inscricao.* FROM tab_inscricao WHERE id_inscricao='{$id_inscricao}';");
        }
        //$controller = new MensageiroController();
        //$controller->msg("SELECT tab_processos.*,  tab_status.txt_status as status FROM tab_processos LEFT JOIN tab_status ON tab_processos.id_status = tab_status.id_status WHERE id_processo={$id_processo};",1);
        if ($stmt->execute()) {
            if ($stmt->rowCount() > 0) {
                $resultado = $stmt->fetchObject('Inscricao');
                if ($resultado) {
                    return $resultado;
                }
            }
        }
        return false;
    }

    public static function find_user($id_processo,$id_user,$with_role = true)
    {
        $conexao = Conexao::getInstance();
        if($with_role)
            $stmt    = $conexao->prepare("SELECT DATE_FORMAT(dt_enviado, '%d de %M de %Y') as data_enviado, DATE_FORMAT(dt_enviado, '%H:%i:%S') as hora_enviado, tab_inscricao.*,  tab_processos.txt_processo as processo FROM tab_inscricao LEFT JOIN tab_processos ON tab_inscricao.id_processo = tab_processos.id_processo WHERE tab_inscricao.id_processo='{$id_processo}' AND id_user='{$id_user}';");
        else{
            $stmt    = $conexao->prepare("SELECT DATE_FORMAT(dt_enviado, '%d de %M de %Y') as data_enviado, DATE_FORMAT(dt_enviado, '%H:%i:%S') as hora_enviado, tab_inscricao.* FROM tab_inscricao WHERE tab_inscricao.id_processo='{$id_processo}' AND id_user='{$id_user}';");
        }
        //$controller = new MensageiroController();
        //$controller->msg("SELECT tab_processos.*,  tab_status.txt_status as status FROM tab_processos LEFT JOIN tab_status ON tab_processos.id_status = tab_status.id_status WHERE id_processo={$id_processo};",1);
        if ($stmt->execute()) {
            if ($stmt->rowCount() > 0) {
                $resultado = $stmt->fetchObject('Inscricao');
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