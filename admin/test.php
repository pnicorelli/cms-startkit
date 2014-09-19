<?php
require("../class/Upload.class.php");

if(isset($_POST["salva"]))
{
 $handle = new upload($_FILES['image_field']);
  if ($handle->uploaded) {
      $handle->file_new_name_body   = 'image_resized';
      $handle->image_resize         = true;
      $handle->image_x              = 100;
	  $handle->image_y			  = 100;
      $handle->image_ratio        = true;
      $handle->process('../upload/');
      if ($handle->processed) {
          echo 'image resized.<br />';
		  echo $handle->file_dst_name;
          $handle->clean();
      } else {
          echo 'error : ' . $handle->error;
      }
  }	
}
?>
<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>Untitled Document</title>
</head>

<body>

 <form enctype="multipart/form-data" method="post" action="test.php">
   <input type="hidden" name="salva">
   <input type="file" size="32" name="image_field" value="">
   <input type="submit" name="Submit" value="upload">
 </form>
 
 
</body>
</html>
