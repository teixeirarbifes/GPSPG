<?php
require_once('conf.php');
require_once('controller'.S.'fichacontroller.php');
require_once('model'.S.'ficha.php');


if($_GET['temp']==1){
   session_start();
   $session_id = session_id();
   $id = $_GET["id"];
   $imageFileType = $_SESSION['ext'.$id];
   if(DESENVOLVIMENTO==1)
   $location_transfer = str_replace('GPSPG','',str_replace("gpspgbeta","",$_SERVER['DOCUMENT_ROOT'])).'betaupload'.S.'temp_photos'.S.$session_id.'_'.$id.'.'.$imageFileType;
   else
   $location_transfer = str_replace("gpspg","",$_SERVER['DOCUMENT_ROOT']).'upload'.S.'temp_photos'.S.$session_id.'_'.$id.'.'.$imageFileType;
   $location_img = $location_transfer;
}else{
   $id_ficha = $_GET['id'];
   $ficha = Ficha::find($id_ficha);
   if(isset($ficha->txt_photo)){
      if(DESENVOLVIMENTO==1)
      $location_img = str_replace('GPSPG','',str_replace("gpspgbeta","",$_SERVER['DOCUMENT_ROOT'])).'betaupload'.S.'photos'.S.$ficha->txt_photo;
      else
      $location_img = str_replace("gpspg","",$_SERVER['DOCUMENT_ROOT']).'upload'.S.'photos'.S.$ficha->txt_photo;
      $array = explode('.',$ficha->txt_photo);
      $ext = end($array);


   }
}

$imagepath=$location_img;
if($ext == 'png')
$image=imagecreatefrompng($imagepath);
else
$image=imagecreatefromjpeg($imagepath);

// get image height

$imgheight=imagesy($image);

//allocate a color for image caption (white)

$color=imagecolorallocate($image, 255, 255, 255);

//Add text to image bottom

imagestring($image, 5, 100, $imgheight-50, "September 2005", $color);

header('Content-Type: image/jpeg');

imagejpeg($image);

?>