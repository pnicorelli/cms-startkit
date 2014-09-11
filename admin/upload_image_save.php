<?php 
include("initback.php");


$link_id = mysql_connect(DB_HOST,DB_LOGIN,DB_PASSW);
//seleziono il database
mysql_select_db(DB_SCHEMA) or die ("Non riesco a selezionare il database");


if(!isset($_GET["nome_sessione"]))
{
	header("Location: login.php");
	exit();
}

$nome_sessione = $_GET["nome_sessione"];
$chiave_record = $_SESSION["chiave_record"];
$idTabella = $_SESSION["idTabella"];
$nome_file_originale = $_SESSION["nome_file_originale"];


//DEFINIZIONE CARTELLE / VALORI 
$cartella_appoggio = CARTELLA_APPOGGIO_UPLOAD;

//recupero la cartella per l'upload
$cartella_upload = dimmi_cartella_upload($idTabella);

//e setto il percorso 
$percorso_new = "../".UPLOAD_FILE."/".$cartella_upload."/";
crea_percorso($percorso_new);

$tipo_file = tipo_file($nome_file_originale);

//RECUPERO E SPOSTO LE IMMAGINI
//la thumb
$thumb_image_name = thumb_image_prefix.$nome_sessione.".".$tipo_file; 
$thumb_image_file = CARTELLA_APPOGGIO_UPLOAD.$thumb_image_name;

$thumb_image_name_nuovo = md5_file($thumb_image_file)."_thumb.".$tipo_file;
$redim_image_name_nuovo = md5_file($thumb_image_file).".".$tipo_file;

copy($thumb_image_file , $percorso_new.$thumb_image_name_nuovo);


//e l'img a dimensione fissa
$redim_image_name = large_image_prefix.$nome_sessione.".".$tipo_file;
if(is_file(CARTELLA_APPOGGIO_UPLOAD.$redim_image_name))
{
	$redim_image_file = CARTELLA_APPOGGIO_UPLOAD.$redim_image_name;
	copy($redim_image_file , $percorso_new.$redim_image_name_nuovo);
}

//cancello le img dalla cartella temporanea
$large_image_name = prefisso_img_originale.$nome_sessione.".".$tipo_file;
$large_image_file=CARTELLA_APPOGGIO_UPLOAD.$large_image_name;

$mantieniOriginale =  dimmi_valore("gestione_tabelle", "mantieni_orig", "idGT=".$idTabella);

if($mantieniOriginale==1)
{
	$large_image_name_nuovo = md5_file($thumb_image_file)."_orig.".$tipo_file;
	copy($large_image_file , $percorso_new.$large_image_name_nuovo);
}

@unlink($large_image_file);
@unlink($redim_image_file);
@unlink($thumb_image_file);

$nomeFile = $nome_file_originale . ":" . $redim_image_name_nuovo;


//inserisco nel db
$condizione="fk_tabella=".$idTabella." and fk_record=".$chiave_record." and tipoF = 'img'";
$sorting = (restituisci_max_sorting('file', 'sorting', $condizione)+1);

$insert = "insert into file (file, fk_tabella, fk_record, tipoF, sorting) values ('".$nomeFile."',".$idTabella.", ".$chiave_record.", 'img', ".$sorting.")";
if(!mysql_query($insert))
	exit("Errore query: ".$insert);

?>
<script type="text/javascript" src="../js/jquery-1.7.1.min.js"></script>
<script language="javascript" type="text/javascript">
<!--
	//var tblElenco = window.opener.jQuery("#tblElenco");
	//tblElenco.load(opener.document.location.href+" #tblElenco>*","");
	
	var tblElenco = window.opener.jQuery(".<?php print $_SESSION["classTbl"]?>");
	tblElenco.load(opener.document.location.href+" .<?php print $_SESSION["classTbl"]?>>*","");
	
	//opener.document.location.reload();
	window.close();
//-->
</script>