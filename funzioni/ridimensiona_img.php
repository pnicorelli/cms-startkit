<?php
$nome_file = $_GET["nome_file"];
$baseThumb = $_GET["baseThumb"];
$hThumb = $_GET["hThumb"];

$myImage = imagecreatefromjpeg($nome_file);
$width = imagesx($myImage);
$height = imagesy($myImage);

imagedestroy($myImage);

//fisso la base e recupero le dimensioni della thumb
$h = ($baseThumb * $height) / $width;
$diff = ($h - $hThumb) / 2;
$y = $diff;

$mainImage = imagecreatefromjpeg($nome_file);

$myThumb_temp = imagecreatetruecolor($baseThumb, $h);

//creo la prima immagine piccolina, rispettando le proporzioni	
imagecopyresampled($myThumb_temp, $mainImage, 0, 0, 0, 0, $baseThumb, $h, $width, $height);


$myThumb = imagecreatetruecolor($baseThumb, $hThumb);

//creo l'immagine delle dimensioni giuste, tagliando l'eccedenza!	
imagecopyresampled($myThumb, $myThumb_temp, 0, 0, 0, $y, $baseThumb, $hThumb, $baseThumb, $hThumb);

header("Content-type: image/jpeg");
imagejpeg($myThumb);

imagedestroy($myThumb_temp);
imagedestroy($myThumb);
?>