<?php

/**
 * Classe Contato - baseado no modelo Active Record (Simplificado) 
 * @author Alexandre Bezerra Barbosa
 */

require_once(GPATH.'database'.S.'conexao.php');

class Roles
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

class Usuarios
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
        if (!isset($this->id_user)) {
            $query = "INSERT INTO tab_users (".
                implode(', ', array_keys($colunas)).
                ") VALUES (".
                implode(', ', array_values($colunas)).");";
        } else {
            foreach ($colunas as $key => $value) {
                if ($key !== 'id_user') {
                    $definir[] = "{$key}={$value}";
                }
            }
            $query = "UPDATE tab_users SET ".implode(', ', $definir)." WHERE id_user='{$this->id_user}';";                        
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

        $conta = Usuarios::count();

        $pags = ceil($conta/5);
        if($pag>$pags) $pag = $pags;

        $offset = ($pag-1)*$num;
        if($offset <0) $offset  = 0;
        $limit = $num;

        $stmt    = $conexao->prepare("SELECT tab_users.*, tab_roleuser.role as role FROM tab_users LEFT JOIN tab_roleuser ON tab_users.id_role = tab_roleuser.id_role {$torder} LIMIT {$offset},{$limit}  ;");
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


    public static function all_roles()
    {
        $conexao = Conexao::getInstance();
        $stmt    = $conexao->prepare("SELECT * FROM tab_roleuser;");
        $result  = array();
        if ($stmt->execute()) {
            while ($rs = $stmt->fetchObject(Roles::class)) {
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
        $stmt    = $conexao->prepare("SELECT COUNT(*) FROM tab_users;");        
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
    public static function find($id_user,$with_role = true)
    {
        $conexao = Conexao::getInstance();
        if($with_role)
            $stmt    = $conexao->prepare("SELECT tab_users.*,  tab_roleuser.role as role FROM tab_users LEFT JOIN tab_roleuser ON tab_users.id_role = tab_roleuser.id_role WHERE id_user={$id_user};");
        else{
            $stmt    = $conexao->prepare("SELECT tab_users.* FROM tab_users WHERE id_user='{$id_user}';");
        }
        if ($stmt->execute()) {
            if ($stmt->rowCount() > 0) {
                $resultado = $stmt->fetchObject('Usuarios');
                if ($resultado) {
                    return $resultado;
                }
            }
        }
        return false;
    }

    public static function formatCnpjCpf($value)
    {
    $CPF_LENGTH = 11;
    $cnpj_cpf = preg_replace("/\D/", '', $value);
    
    if (strlen($cnpj_cpf) === $CPF_LENGTH) {
        return preg_replace("/(\d{3})(\d{3})(\d{3})(\d{2})/", "\$1.\$2.\$3-\$4", $cnpj_cpf);
    } 
    
    return preg_replace("/(\d{2})(\d{3})(\d{3})(\d{4})(\d{2})/", "\$1.\$2.\$3/\$4-\$5", $cnpj_cpf);
    }

    public static function find_by_user($username)
    {
        $conexao = Conexao::getInstance();
        //$cpf = Usuarios::formatCnpjCpf($username);
        //$email = $username;
        $stmt    = $conexao->prepare("SELECT * FROM tab_users WHERE txt_usuario LIKE '{$username}';");
        if ($stmt->execute()) {
            if ($stmt->rowCount() > 0) {
                $resultado = $stmt->fetchObject('Usuarios');
                if ($resultado) {
                    return $resultado;
                }
            }
        }
        return false;
    }

    public static function find_by_email($email)
    {
        $conexao = Conexao::getInstance();
        $stmt    = $conexao->prepare("SELECT * FROM tab_users WHERE txt_email LIKE '{$email}';");
        if ($stmt->execute()) {
            if ($stmt->rowCount() > 0) {
                $resultado = $stmt->fetchObject('Usuarios');
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
    public static function destroy($id_user)
    {
        $conexao = Conexao::getInstance();
        if ($conexao->exec("DELETE FROM tab_users WHERE id_user='{$id_user}';")) {
            return true;
        }
        return false;

    }  
    
}
?>