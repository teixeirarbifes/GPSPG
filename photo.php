<?php
require_once('conf.php');
require_once('controller'.S.'fichacontroller.php');
require_once('model'.S.'ficha.php');

function LoadPNG($imgname)
{
    /* Attempt to open */
    $im = @imagecreatefrompng($imgname);

    /* See if it failed */
    if(!$im)
    {
        /* Create a blank image */
        $im  = imagecreatetruecolor(150, 30);
        $bgc = imagecolorallocate($im, 255, 255, 255);
        $tc  = imagecolorallocate($im, 0, 0, 0);

        imagefilledrectangle($im, 0, 0, 150, 30, $bgc);

        /* Output an error message */
        imagestring($im, 10, 5, 5, 'ERRO!', $tc);
        imagestring($im, 10, 5, 20, 'Foto', $tc);
        imagestring($im, 10, 5, 35, 'ausente!', $tc);
    }

    return $im;
}

function LoadJPG($imgname)
{
    /* Attempt to open */
    $im = @imagecreatefromjpeg($imgname);

    /* See if it failed */
    if(!$im)
    {
        /* Create a blank image */
        $im  = imagecreatetruecolor(100, 150);
        $bgc = imagecolorallocate($im, 255, 255, 255);
        $tc  = imagecolorallocate($im, 0, 0, 0);

        imagefilledrectangle($im, 0, 0, 100, 150, $bgc);

        /* Output an error message */
        imagestring($im, 10, 10, 15, 'ERRO!', $tc);
        imagestring($im, 10, 10, 50, 'Foto', $tc);
        imagestring($im, 10, 10, 65, 'ausente.', $tc);
    }

    return $im;
}

if($_GET['temp']==1){
   session_start();
   $session_id = session_id();
   $id = $_GET["id"];
   $ext = $_SESSION['ext'.$id];
  
   $location_img = UPLOAD_DIR_TEMP_PHOTOS.$session_id.'_'.$id.'.'.$ext;
}else{
   $id_ficha = $_GET['id'];
   $ficha = Ficha::find($id_ficha);
   if(isset($ficha->txt_photo)){
      $location_img = UPLOAD_DIR_PHOTOS.$ficha->txt_photo;
      $array = explode('.',$ficha->txt_photo);
      $ext = end($array);


   }
}

$imagepath=$location_img;
if(strtolower($ext) == 'png'){
header('Content-Type: image/png');
$image=LoadPNG($imagepath);
imagepng($image);
imagedestroy($image);
}else{
header('Content-Type: image/jpeg');
$image=LoadJPG($imagepath);
imagejpeg($image);
imagedestroy($image);
}



?>