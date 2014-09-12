<?php
require_once("initback.php");

$suffixpage = "terapie";
$id = $ww->getInt("id", 0);


$item = new Terapie($id);


$update = $ww->get("update", "");
$updated = false;
if($update == "update"){


		$item->item["titolo"] = $ww->get("titolo");
		$item->item["descrizione"] = $ww->get("descrizione");
		update_file($item->table, $suffixpage."_item.php?errore=file&id=".$id, $id);
		$item->save();
		$id = $item->item[$item->tableid];
		$updated = true;
		
}


?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
"http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=<?php echo CHARSET; ?>">
<?php
require_once("header_admin.php");
?>
	<script src="../js/json2.js"></script>
	<script src="../js/ckeditor/ckeditor.js"></script>
	<script src="../js/ckeditor/adapters/jquery.js"></script>
<script language="javascript">
//salvo in un'array i campi del modulo su cui devo effettuare dei controlli al momento del submit
//se la funzione javascipt non è associata a un campo al posto del nome e della sua descrizione metto "-"

var arcampicontrollo= new Array();
//arcampicontrollo[0]="nome:Nome:obbligatorio"; 

function controlla_modulo(){

	ritorno = controllaeinviamodulo('form',arcampicontrollo);

	if(ritorno == true)
		document.form.submit();
}

$(document).ready( function(){
		$("textarea").ckeditor();
		$(".updated").show().delay(3000).fadeOut();
});


</script>
<style>

.updated{border-radius: 10px; width:50%; background: #EEEAEE; color: #000; font-size: 1.3em; padding: 10px; margin: 0 25%; text-align: center; font-weight: bold; text-shadow: 2px 2px 2px #fff; box-shadow:1px 1px 1px #000;}

</style>
</head>

<body>
<div id="contenitore_esterno">
<div id="contenitore">
	<!-- testata -->
	<div id="head"><?php include("testata.php"); ?></div>
	
	<!-- menu in alto -->
	<DIV ID=myMenuID>
	<?php include("menu/menu.php");?>
	</DIV>		

	<!-- contenuti della pagina -->
	<div id="content">
		<span class="titoli"><?php echo ucfirst($suffixpage);?></span>
		<br /><br />

			<form method="post" name="form" enctype="multipart/form-data">
			  <input type="hidden" id="update" name="update" value="update">
			  <input type="hidden" id="id" name="id" value="<?php echo $id;?>">
			 
			  
			  <table width="100%" border="0" cellspacing="2" cellpadding="2" class="testo tblimg">

			  <tr>
				<td>Titolo</td>
				<td>
					<input type="text" name="titolo" id="titolo" value="<?php echo $item->str("titolo"); ?>">
				</td>
			  </tr>
			  <tr>
				<td>Specializzazione</td>
				<td>
					<textarea name="descrizione" id="descrizione" ><?php echo $item->str("descrizione"); ?></textarea>
				</td>
			  </tr>

			<?php if( $id>0 ) { ?>

			  <tr>
				<td colspan="2" >
					<div class="listafoto-title">Foto </div>
				<table class="listafoto">
					<tr>
					<td width="15%">&nbsp;</td>
					<td width="85%">&nbsp;</td>
					</tr>
					<?php
			
					$idT = dimmi_id_tabella($item->table, $suffixpage);
					$cartellaUploadFile = dimmi_cartella_upload($idT);
					$percorsoFile = "../".UPLOAD_FILE."/".$cartellaUploadFile."/";
					print scrivi_campi_dett($idT, $id, $percorsoFile, "listafoto");
					?>
				</table>
				</td>
				</tr><?php

			}
					?>
		  	 
		  	 
			  <tr>
				<td colspan="2">			  
				<?php
				if($updated){
					?>
					<div class="updated">Record Salvato</div>
					<?php
				}
				?>			
				</td>
			  </tr>	
			                  
			  <tr>
				<td><input type="submit" value="salva"></td>
			  </tr>
			  
			  </table>
            </form>
			<< <a href="<?php echo $suffixpage;?>_elenco.php?sezione=<?php echo $sezione;?>">torna all'elenco </a>
      	    <br />
	</div>
	
	<div style="clear:both; height:5px;">
	&nbsp;
	</div>

</div>
</div>
</body>
</html>
