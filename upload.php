<?php
require_once('conf.php');
session_start();
$session_id = session_id();
if(isset($_FILES['file']['name'])){
   /* Getting file name */
   $filename = $_FILES['file']['name'];
   $size = $_FILES['file']['size'];

   /* Location */
   #mkdir("c:\\inetpub\\wwwroot\\gpspg\\upload\\", 0700);
   #mkdir("c:\inetpub\\wwwroot\\gpspg\\upload\\temp_photos\\", 0700);
   $location = $filename;
   $imageFileType = pathinfo($location,PATHINFO_EXTENSION);
   $imageFileType = strtolower($imageFileType);

   /* Valid extensions */
   $valid_extensions = array("jpg","jpeg","png");

   $response = 0;
   /* Check file extension */
   if(($size > 2097152)){
      $response = 'wrong_size';      
   }else if(in_array(strtolower($imageFileType), $valid_extensions)) {

      if(isset($_POST['id']) && $_POST['id'] != ""){
         $id = $_POST['id'];         
      }else{
         $_SESSION['unique'] = isset($_SESSION['unique']) ? $_SESSION['unique'] + 1 : 1;
         $id = $_SESSION['unique'];
      }
      $_SESSION['ext'.$id] = $imageFileType;
      
      $location_transfer =  UPLOAD_DIR_TEMP_PHOTOS.$session_id.'_'.$id.'.'.$imageFileType;
      
      
      /* Upload file */
      if(move_uploaded_file($_FILES['file']['tmp_name'],$location_transfer)){
         $response = $id;
      }
   }else{
      $response = 'wrong_ext';
   }

   echo $response;
   exit();
}
?>