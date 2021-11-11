<?php
$gpath = $_SERVER['DOCUMENT_ROOT'];
if($gpath[0]=='/')
define('S','/');
else
define('S','\\');
define('GPATH',$gpath.S);
                   
//define('STRING_DB',"mysql:host=mocha3036.mochahost.com;port=3306;dbname=gemadne2_gpspg");        
//define('USUARIO_DB',"gemadne2_gpspg");        
//define('SENHA_DB',"Rafa1064*");    


define('STRING_DB',"mysql:host=mocha3036.mochahost.com;port=3306;dbname=gemadne2_gpspgdev");        
define('USUARIO_DB',"gemadne2_gpspgdev");        
define('SENHA_DB',"afszJZHHIsh,");    

define('SMTP_SERVER','mocha3036.mochahost.com');
define('SMTP_USER','gpspg@gemad.net');
define('SMTP_PASS','S@V~T80!bt71');
define('SMTP_MAIL','gpspg@gemad.net');
define('SMTP_CONFIRM','gpspg@gemad.net');
fclose($myfile);
?>