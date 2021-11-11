<?php
#Este é o template de configuração

$gpath = $_SERVER['DOCUMENT_ROOT'];
if($gpath[0]=='/')
define('S','/');
else
define('S','\\');
define('GPATH',$gpath.S);


define('STRING_DB',"mysql:host=server;port=3306;dbname=banco");        
define('USUARIO_DB',"usuario");        
define('SENHA_DB',"senha,");    

define('SMTP_SERVER','server');
define('SMTP_USER','usuarioemail');
define('SMTP_PASS','senhaemail');
define('SMTP_MAIL','email');
define('SMTP_CONFIRM','emailconfirmacao');

define('DESENVOLVIMENTO',1);
fclose($myfile);
?>