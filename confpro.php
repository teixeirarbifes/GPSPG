<?php
$gpath = $_SERVER['DOCUMENT_ROOT'];
if($gpath[0]=='/')
define('S','/');
else
define('S','\\');
define('GPATH',$gpath.S);
                   
define('STRING_DB',"mysql:host=mocha3036.mochahost.com;port=3306;dbname=gemadne2_gpspg");        
define('USUARIO_DB',"gemadne2_gpspg");        
define('SENHA_DB',"Rafa1064*");    

define('SMTP_SERVER','mocha3036.mochahost.com');
define('SMTP_USER','gpspg@gemad.net');
define('SMTP_PASS','S@V~T80!bt71');
define('SMTP_MAIL','gpspg@gemad.net');
define('SMTP_CONFIRM','gpspg@gemad.net');

define('DESENVOLVIMENTO',0);
define('UPLOAD_DIR_PHOTOS',"/home/gemadne2/upload/photos/");
define('UPLOAD_DIR_TEMP_PHOTOS',"/home/gemadne2/upload/temp_photos/");
define('UPLOAD_DIR_FILES',"/home/gemadne2/upload/");

fclose($myfile);
?>