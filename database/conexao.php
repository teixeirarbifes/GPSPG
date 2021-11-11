<?php

class Conexao
{
    private static $conexao;
 
    private function __construct()
    {}
 
    public static function getInstance()
    {
        if (is_null(self::$conexao)) {
            self::$conexao = new \PDO('mysql:host=mocha3036.mochahost.com;port=3306;dbname=gemadne2_gpspg', 'gemadne2_gpspg', 'Rafa1064*');
            self::$conexao->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
            self::$conexao->exec('set names utf8');
        }
        return self::$conexao;
    }
}

 ?>