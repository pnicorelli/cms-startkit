<?php
include("initback.php");

$link_id = mysql_connect(DB_HOST,DB_LOGIN,DB_PASSW);
//seleziono il database
mysql_select_db(DB_SCHEMA) or die ("Non riesco a selezionare il database");


if( !isset($_GET["chiave_record"]) && !isset($_SESSION["chiave_record"]) )
{
	header("Location: login.php");
	exit();
}

if(isset($_GET["chiave_record"]))
{
	$_SESSION["chiave_record"]=$_GET["chiave_record"];
	$arrDati = explode("-",$_GET["idTabella"]);
	$_SESSION["idTabella"]=$arrDati[0];
	$_SESSION["classTbl"]=$arrDati[1];
	$_SESSION["random_key"] = "";
}


$stringa_get="";
$arr_campi_non_riportare=array("primoaccesso","a","imgCaricata");
foreach($_GET as $chiave => $valore)
{
	if(!in_array($chiave,$arr_campi_non_riportare))
		$stringa_get.="&".$chiave."=".$valore;
}
$stringa_get=substr($stringa_get,1,strlen($stringa_get));


//recupero le dimensioni delle img big e thumb da DB
$arrayDimensioni = dimmi_dimensioni_img($_SESSION["idTabella"]);

//cancello i file vecchi della cartella temp
$ore=date("H")-2;
cancella_file_old(CARTELLA_APPOGGIO_UPLOAD,strtotime(date('Y-m-d '.$ore.':i:s')));


//only assign a new timestamp if the session variable is empty
if (strlen($_SESSION['random_key'])==0){
    $_SESSION['random_key'] = strtotime(date('Y-m-d H:i:s')); //assign the timestamp to the session variable
}


//Constants
$upload_dir = CARTELLA_APPOGGIO_UPLOAD; 			// The directory for the images to be saved in
$upload_path = $upload_dir;				            // The path to where the image will be saved
$large_image_prefix = prefisso_img_originale; 		// The prefix name to large image
$max_file = MAX_SIZE_UPLOAD_Byte; 					// Approx 1MB
$max_width = MAX_WIDTH_IMAGES;						// Max width allowed for the large image

if(isset($_POST["nStep"]) && ($_POST["nStep"] == 3))
{
	if($arrayDimensioni[0]<>0)
	{ $thumb_width = $arrayDimensioni[0]; }  			// Width of thumbnail image
	else					
	{ $thumb_width = 0; }
	
	if($arrayDimensioni[1] != 0)
	{ $thumb_height = $arrayDimensioni[1]; }			// Height of thumbnail image
	else
	{ $thumb_height = 0; }
	
	$thumb_image_prefix = large_image_prefix;			// The prefix name to the thumb image
}
else
{
	$thumb_width = $arrayDimensioni[2];					// Width of thumbnail image
	$thumb_height = $arrayDimensioni[3];				// Height of thumbnail image
	$thumb_image_prefix = thumb_image_prefix;			// The prefix name to the thumb image
}

$large_image_name = $large_image_prefix.$_SESSION['random_key'];     // New name of the large image (append the timestamp to the filename)
$thumb_image_name = $thumb_image_prefix.$_SESSION['random_key'];     // New name of the thumbnail image (append the timestamp to the filename)



//Create the upload directory with the right permissions if it doesn't exist
if(!is_dir($upload_dir)){
	crea_percorso($upload_dir);
}


/*************si sta facendo l'upload dell'immagine grande***************/
if (isset($_POST["upload"])) {  
	//Get the file information
	$userfile_name = $_FILES['image']['name'];
	$userfile_tmp = $_FILES['image']['tmp_name'];
	$userfile_size = $_FILES['image']['size'];
	$filename = basename($_FILES['image']['name']);
	$file_ext = substr($filename, strrpos($filename, '.') + 1);
	
	$_SESSION["nome_file_originale"] =  $_FILES['image']['name'];
	
	//Only process if the file is a JPG and below the allowed limit
	if((!empty($_FILES["image"])) && ($_FILES['image']['error'] == 0))
	{
		if ((strtolower($file_ext)=="bmp") || ($userfile_size > $max_file)) 
		{ 
			$error= "Le immagini di tipo <b>bmp</b> non possono essere caricate.";
		}
		else if(strtolower($file_ext) <> "jpg" and strtolower($file_ext) <> "jpeg" and strtolower($file_ext) <> "gif")
		{
			$error = "Le immagini devono avere estensione <strong>jpg</strong> o <strong>gif</strong>.";
		}
	}
	else
	{
		$error= "Selezionare un'immagine da caricare";
	}
	
	
	//Everything is ok, so we can upload the image.
	if (!isset($error) or (strlen($error)==0)) {
		
		if (isset($_FILES['image']['name']))
		{
			$nomeFile = $_FILES["image"]["name"];

			$filename = dimminomefile($nomeFile);
			$tipo_file = tipo_file($nomeFile);
			
			$immagineDaUsare = $upload_dir . $large_image_name . "." . $tipo_file;
			
			move_uploaded_file($userfile_tmp, $immagineDaUsare);
			chmod($immagineDaUsare, 0777);

			$width = getWidth($immagineDaUsare);
			$height = getHeight($immagineDaUsare);

						
			//Scale the image if it is greater than the width set above
			if ($width > $max_width)
			{
				$scale = $max_width/$width;
				$uploaded = resizeImage($immagineDaUsare,$width,$height,$scale);
			}
			else
			{
				$scale = 1;
				$uploaded = resizeImage($immagineDaUsare,$width,$height,$scale);
			}
		}
		
	}
}


if (isset($_POST["upload_thumbnail"]) && isset($_POST["imgBig"])) {   //si sta confermando la thumb
	
	$large_image_location = $_POST["imgBig"];
	$immagineDaUsare = $large_image_location;
	
	$filename = dimminomefile($large_image_location);
	$tipo_file = tipo_file($large_image_location);
	
	$immagineThumb = $upload_dir . $thumb_image_prefix . $_SESSION['random_key'] . "." . $tipo_file;
	
	//Get the new coordinates to crop the image.
	$x1 = $_POST["x1"];
	$y1 = $_POST["y1"];
	$x2 = $_POST["x2"];
	$y2 = $_POST["y2"];
	$w = $_POST["w"];
	$h = $_POST["h"];
	
	
	$current_large_image_width = getWidth($large_image_location);
	$current_large_image_height = getHeight($large_image_location);
	
	if($thumb_height==0 && $thumb_width!=0)
	{
		$rapporto_caricata = $current_large_image_width/$current_large_image_height;
		$thumb_height = $thumb_width/$rapporto_caricata;
	}
	else if($thumb_width==0 && $thumb_height!=0)
	{
		$rapporto_caricata = $current_large_image_width/$current_large_image_height;
		$thumb_width = $thumb_height*$rapporto_caricata;
	}
	
	
	//Scale the image to the thumb_width set above
	//if($x2==$w && $y2==$h)
		//$scale = 1;
	//else
		$scale = $thumb_width/$w;
	
	$cropped = resizeThumbnailImage($immagineThumb, $large_image_location,$w,$h,$x1,$y1,$scale);

}


?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
	<link rel="stylesheet" href="css/stile.css" type="text/css">
	<meta name="generator" content="WebMotionUK" />
	<title><?php print NOME_SITO?> - Area Amministrativa</title>
	<script type="text/javascript" src="../js/jquery-1.8.3.min.js"></script>
	<script type="text/javascript" src="../js/jquery.imgareaselect.min.js"></script>
</head>
<body>
<div style="width:90%; margin:12px auto; padding:12px 12px 12px 12px; background-color:#FFFFFF; text-align:left; min-height: 580px;	height: auto !important; height: 580px;">
<!-- 
* Copyright (c) 2008 http://www.webmotionuk.com / http://www.webmotionuk.co.uk
* Date: 2008-09-05
* Ver 1.1
* Redistributions of source code must retain the above copyright notice, this list of conditions and the following disclaimer.
* Redistributions in binary form must reproduce the above copyright notice, this list of conditions and the following disclaimer in the documentation and/or other materials provided with the distribution.
*
* THIS SOFTWARE IS PROVIDED BY THE COPYRIGHT HOLDERS AND CONTRIBUTORS "AS IS" AND 
* ANY EXPRESS OR IMPLIED WARRANTIES, INCLUDING, BUT NOT LIMITED TO, THE IMPLIED 
* WARRANTIES OF MERCHANTABILITY AND FITNESS FOR A PARTICULAR PURPOSE ARE DISCLAIMED. 
* IN NO EVENT SHALL THE COPYRIGHT OWNER OR CONTRIBUTORS BE LIABLE FOR ANY DIRECT, INDIRECT, 
* INCIDENTAL, SPECIAL, EXEMPLARY, OR CONSEQUENTIAL DAMAGES (INCLUDING, BUT NOT LIMITED TO, 
* PROCUREMENT OF SUBSTITUTE GOODS OR SERVICES; LOSS OF USE, DATA, OR PROFITS; OR BUSINESS 
* INTERRUPTION) HOWEVER CAUSED AND ON ANY THEORY OF LIABILITY, WHETHER IN CONTRACT, 
* STRICT LIABILITY, OR TORT (INCLUDING NEGLIGENCE OR OTHERWISE) ARISING IN ANY WAY OUT OF 
* THE USE OF THIS SOFTWARE, EVEN IF ADVISED OF THE POSSIBILITY OF SUCH DAMAGE.
*
* http://www.opensource.org/licenses/bsd-license.php
-->
<?php
//Only display the javascript if an image has been uploaded
if(isset($immagineDaUsare) and strlen($immagineDaUsare)>0){
	$current_large_image_width = getWidth($immagineDaUsare);
	$current_large_image_height = getHeight($immagineDaUsare);
	
	
//controllo se stiamo lavorando sulla thumb (step 2) o sull'immagine a dimensione fissa
if(isset($immagineThumb))  //c'è già la thumb, quindi devo creare l'img a dimensione fissa
{
	if($arrayDimensioni[0]!=0)
		$thumb_width = $arrayDimensioni[0];						// Width of thumbnail image
	else
		$thumb_width = 0;

	if($arrayDimensioni[1]!=0)
		$thumb_height = $arrayDimensioni[1];					// Height of thumbnail image
	else
		$thumb_height = 0;

	if($thumb_width==0 && $thumb_height==0)
	{//passo allo step di salvataggio
	?>
		<form method="post" name="frm_salta" action="upload_image.php?<?php print $stringa_get; ?>"><input type="hidden" name="nStep" value="3" /><input type="hidden" name="imgBigSALTATA" value="si" /><input type="hidden" name="imgBig" id="imgBig" value="<?php print $immagineDaUsare; ?>"/>
</form>
		<script language="javascript" type="text/javascript">
		<!--
		document.frm_salta.submit();
		//-->
		</script>
	<?php 
	}
	$thumb_image_prefix = large_image_prefix;			// The prefix name to the thumb image
	$nStep = "3";
	$creaImg = "Crea immagine Ridimensionata";
}
else
{
	$nStep = "2";
	$creaImg = "Crea immagine";
	if($arrayDimensioni[0]!=0 || $arrayDimensioni[1])
		$creaImg .= " Thumb"; 
}
	
	
	
// controllo che siano settate entrambe le dimensioni della thumb
if($thumb_width==0 && $thumb_height==0 && $nStep == "2")
{
	exit("Errore: non sono state settate correttamente le dimensioni delle thumbnail da DB!!!");
}
else
{
	if($thumb_height==0 && $thumb_width!=0)
	{
		$rapporto_caricata = $current_large_image_width/$current_large_image_height;
		$thumb_height = $thumb_width/$rapporto_caricata;
	}
	else if($thumb_width==0 && $thumb_height!=0)
	{
		$rapporto_caricata = $current_large_image_width/$current_large_image_height;
		$thumb_width = $thumb_height*$rapporto_caricata;
	}
}


if($thumb_width>0 || $thumb_height>0)
	$creaImg .= "<span style='font-size:13px;'>&nbsp;&nbsp;[&nbsp;";
	if($thumb_width>0)
		$creaImg .= "Larghezza: ".ceil($thumb_width)."px";
	if($thumb_width>0 && $thumb_height>0)
		$creaImg .= " - ";
	if($thumb_height>0)
		$creaImg .= "Altezza: ".ceil($thumb_height)."px";
if($thumb_width>0 || $thumb_height>0)
	$creaImg .= "&nbsp;]</span>";


?>
<script type="text/javascript">
function preview(img, selection) { 
	var scaleX = <?php echo $thumb_width;?> / selection.width; 
	var scaleY = <?php echo $thumb_height;?> / selection.height; 
	
	$('#thumbnail + div > img').css({ 
		width: Math.round(scaleX * <?php echo $current_large_image_width;?>) + 'px', 
		height: Math.round(scaleY * <?php echo $current_large_image_height;?>) + 'px',
		marginLeft: '-' + Math.round(scaleX * selection.x1) + 'px', 
		marginTop: '-' + Math.round(scaleY * selection.y1) + 'px' 
	});
	$('#x1').val(selection.x1);
	$('#y1').val(selection.y1);
	$('#x2').val(selection.x2);
	$('#y2').val(selection.y2);
	$('#w').val(selection.width);
	$('#h').val(selection.height);
} 

$(document).ready(function () { 
	$('#save_thumb').click(function() {
		var x1 = $('#x1').val();
		var y1 = $('#y1').val();
		var x2 = $('#x2').val();
		var y2 = $('#y2').val();
		var w = $('#w').val();
		var h = $('#h').val();
		if(x1=="" || y1=="" || x2=="" || y2=="" || w=="" || h==""){
			alert("Attenzione!\nPrima di procedere occorre selezionare l'area di selezione");
			return false;
		}else{
			return true;
		}
	});
}); 

$(window).load(function () { 
	$('#thumbnail').imgAreaSelect({ aspectRatio: '1:<?php echo $thumb_height/$thumb_width;?>', onSelectChange: preview }); 
});

</script>
<?php }?>
<h1 style="font-size:24px; text-decoration:underline; ">Carica e ridimensiona un'immagine</h1>

<?php
//si è scelto di cancellare le immagini
if (isset($_GET['a']) && $_GET['a']=="delete" && strlen($_GET['t'])>0 and (isset($_GET["imgCaricata"]) and strlen($_GET["imgCaricata"])>0)) {  
//get the file locations 
	$_SESSION['random_key']= $_GET['t'];
	
	$immagineCaricata = $_GET["imgCaricata"];
	
	$estensione_file = tipo_file($immagineCaricata);
	
	$imgRidimensionata = $upload_path.large_image_prefix.$_GET['t'].".".$estensione_file;
	$imgThumb = $upload_path.thumb_image_prefix.$_GET['t'].".".$estensione_file;
	
	if (file_exists($immagineCaricata)) {
		unlink($immagineCaricata);
	}
	if (file_exists($imgRidimensionata)) {
		unlink($imgRidimensionata);
	}
	if (file_exists($imgThumb)) {
		unlink($imgThumb);
	}
	
	$_SESSION['random_key']= "";
}


if(!isset($immagineDaUsare) and !isset($immagineThumb) and !isset($_POST["imgBigSALTATA"]))
{
?>
<h2><u>Step 1</u>: Upload file</h2>
<ul style="list-style-type:decimal; margin:0px 0px 14px 0px;">
	<li>Cliccare su "sfoglia" e selezionare l'immagine dal proprio PC</li>
	<li>Cliccare su "Carica" per salvare l'immagine selezionata</li>
</ul>
<form name="photo" enctype="multipart/form-data" action="<?php echo $_SERVER["PHP_SELF"];?>" method="post">
Photo (jpg o gif) <input type="file" name="image" size="30" /> <input type="submit" name="upload" value="Carica" />
</form>

<?php
}

//Display error message if there are any
if(isset($error) && strlen($error)>0)
{
	echo "<ul style=\"margin:4px 0px 14px 0px;\"><li><strong>Errore!</strong></li><li>".$error."</li></ul>";
}
	
	if(isset($immagineDaUsare) and strlen($immagineDaUsare)>0 and (!isset($_POST["nStep"]) or $_POST["nStep"] <> "3"))  //si è caricata l'immagine, si deve creare la thumb
	{
		// controllo se l'immagine caricata è più piccola della thumb
		$width_caricata = getWidth($immagineDaUsare);
		$height_caricata = getHeight($immagineDaUsare);
		
		if($width_caricata<=$thumb_width && $height_caricata<=$thumb_height)
		{
			$x1_value=0;
			$y1_value=0;
			$x2_value=$x1_value+$width_caricata;
			$y2_value=$y1_value+$height_caricata;
			$w_value=$width_caricata;
			$h_value=$height_caricata;
			?>
			<h2><u>Step <?php print $nStep;?></u>: <?php print $creaImg;?></h2>
			<ul style="list-style-type:decimal; margin:0px 0px 14px 0px;">
				<li>L'immagine caricata è più piccola della thumb da creare.</li>
				<li>Cliccare sul pulsante "Salva immagine ridimensionata".</li>
			</ul>
			<?php
		}
		else
		{
			$x1_value="";
			$y1_value="";
			$x2_value="";
			$y2_value="";
			$w_value="";
			$h_value="";
		?>
		<h2><u>Step <?php print $nStep;?></u>: <?php print $creaImg;?></h2>
		<ul style="list-style-type:decimal; margin:0px 0px 14px 0px;">
			<li>Posizionarsi col cursore del mouse su un punto dell'immagine di sinistra.</li>
			<li>Tenendo premuto il pulsante sinistro del mouse, trascinare l'area di selezione.<br>L'immagine di destra presenter&agrave; il risultato del ridimensionamento.</li>
			<li>Una volta rilasciato il pulsante sinistro del mouse sar&agrave; possibile:
				<ul style="list-style-type:disc; margin:0px 0px 0px 0px;">
					<li>Spostare l'area di selezione: tenere premuto il pulsante sinistro del mouse per selezionare l'area e muovere il mouse.</li>
					<li>Ridimensionare l'area di selezione: posizionarsi col mouse su uno dei bordi dell'area di selezione, tenere premuto il pulsante sinistro del mouse e muovere il mouse.</li>
				</ul>
			</li>
			<li>Cliccare sul pulsante "Salva immagine ridimensionata".</li>
		</ul>
		
		<?php
		}
		
		//$larghezza_finestra = $thumb_width+getWidth($immagineDaUsare)+60
	?>
	<script language="javascript">
		  var myWidth = 0, myHeight = 0;
		  if( typeof( window.innerWidth ) == 'number' ) {
			//Non-IE
			myWidth = window.innerWidth;
			myHeight = window.innerHeight;
		  } else if( document.documentElement && ( document.documentElement.clientWidth || document.documentElement.clientHeight ) ) {
			//IE 6+ in 'standards compliant mode'
			myWidth = document.documentElement.clientWidth;
			myHeight = document.documentElement.clientHeight;
		  } else if( document.body && ( document.body.clientWidth || document.body.clientHeight ) ) {
			//IE 4 compatible
			myWidth = document.body.clientWidth;
			myHeight = document.body.clientHeight;
		  }
		
	</script>
	<div align="center" style=" margin:12px 0px 14px 0px;">
	<img src="<?php echo $immagineDaUsare;?>" style="float: left; margin-right: 10px;" id="thumbnail" alt="Create Thumbnail"  title="Create Thumbnail" />		
	<div style="float:left; position:relative; overflow:hidden; width:<?php echo $thumb_width;?>px; height:<?php echo $thumb_height;?>px;">
				<img src="<?php echo $immagineDaUsare;?>" style="position: relative;" alt="Thumbnail Preview" title="Thumbnail Preview"/>
	  </div>
			<div style="clear:both; padding:18px 0px 0px 0px; text-align:center;">		
			<form name="thumbnail" action="<?php echo $_SERVER["PHP_SELF"];?>" method="post">
				<input type="hidden" name="imgBig" id="imgBig" value="<?php print $immagineDaUsare; ?>"/>
				<input type="hidden" name="nStep" id="nStep" value="<?php print $nStep; ?>"/>
				<input type="hidden" name="x1" id="x1" value="<?php print $x1_value; ?>"/>
				<input type="hidden" name="y1" id="y1" value="<?php print $y1_value; ?>"/>
				<input type="hidden" name="x2" id="x2" value="<?php print $x2_value; ?>"/>
				<input type="hidden" name="y2" id="y2" value="<?php print $y2_value; ?>"/>
				<input type="hidden" name="w" id="w" value="<?php print $w_value; ?>"/>
				<input type="hidden" name="h" id="h" value="<?php print $h_value; ?>"/>
				<input style="font-size:16px;" type="submit" name="upload_thumbnail" value="Salva immagine ridimensionata" id="save_thumb" />
			</form>
			</div>
	</div>	
	<?php 
	} 
	
	
if(isset($_POST["nStep"]) && $_POST["nStep"] == 3 && (isset($_POST["imgBig"]) || isset($_POST["imgBigSALTATA"])))   
{
	$immagine_caricata = $_POST["imgBig"];
		
	$filename = dimminomefile($immagine_caricata);
	$tipo_file = tipo_file($immagine_caricata);
	
		if(!isset($_POST["imgBigSALTATA"]))
		{
			$numero_STEP=4;
			$immagineRid = $upload_dir . large_image_prefix . $_SESSION['random_key'] . "." . $tipo_file;
		}
		else
			$numero_STEP=3;
	
	
	$immagineThumb = $upload_dir . thumb_image_prefix . $_SESSION['random_key'] . "." . $tipo_file;
//	$immagineRid = $upload_dir . large_image_prefix . $_SESSION['random_key'] . "." . $tipo_file;
?>
	<h2><u>Step <?php print $numero_STEP; ?></u>: Conferma ridimensionamento</h2>
	<form method="post" action="upload_image_save.php?nome_sessione=<?php print $_SESSION['random_key']; ?>">
	<table cellpadding="0" cellspacing="0">
		<tr>
			<td valign="top" align="left" height="35"><b>Immagini ridimensionate</b>: </td>
		</tr>
		<tr>
			<td valign="top" align="left"><img src="<?php print $immagineThumb?>" /></td>
		</tr>
		<tr>
		  <td valign="top" align="left">&nbsp;</td>
	  </tr>
		<tr>
		  <td valign="top" align="left"><?php if(isset($immagineRid)) { ?><img src="<?php print $immagineRid?>" /><?php } ?></td>
	  </tr>
		<tr>
			<td align="center" valign="bottom" height="35"><input style="font-size:16px;" type="submit" name="conferma" value="Conferma ridimensionamento"/></td>
		</tr>
	</table>
	</form>
	
<?php
	echo "<p><a href=\"".$_SERVER["PHP_SELF"]."?a=delete&t=".$_SESSION['random_key']."&imgCaricata=".$immagine_caricata."\"><b>Cancella le immagini</b></a></p>";
	//echo "<p><a href=\"".$_SERVER["PHP_SELF"]."\">Upload another</a></p>";
	//Clear the time stamp session
	$_SESSION['random_key']= "";
}

?>
<!-- Copyright (c) 2008 http://www.webmotionuk.com -->
</div>
</body>
</html>
