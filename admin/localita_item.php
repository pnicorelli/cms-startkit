<?php
require_once("initback.php");

$suffixpage = "localita";
$id = $ww->getInt("id", 0);


$item = new Localita($id);


$update = $ww->get("update", "");
$updated = false;
if($update == "update"){
		$item->item["nome"] = $ww->get("nome");
		$item->item["3bmeteo_id"] = $ww->get("3bmeteo_id");
		//update_file($item->table, $suffixpage."_item.php?errore=file&id=".$id, $id);
		$item->save();
		$id = $item->item[$item->tableid];
		$updated = true;
		
}


?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
"http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
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
arcampicontrollo[0]="nome:Nome:obbligatorio"; 

function controlla_modulo(){

	ritorno = controllaeinviamodulo('form',arcampicontrollo);

	if(ritorno == true)
		document.form.submit();
}

$(document).ready( function(){
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
		<span class="titoli">Localita - <?php echo ($id > 0)?"Dettaglio":"Nuovo ";?></span>
		<br /><br />

		
			<form method="post" name="form" enctype="multipart/form-data">
			  <input type="hidden" id="update" name="update" value="update">
			  <input type="hidden" id="id" name="id" value="<?php echo $id;?>">
			  
			  <table width="100%" border="0" cellspacing="2" cellpadding="2" class="testo tblimg">

			  <tr>
				<td>Nome</td>
				<td><input type="text" name="nome" id="nome" value="<?php echo $item->str("nome"); ?>">
				</td>
			  </tr>
			  <tr>
				<td>3bmeteo_id</td>
				<td><input type="text" name="3bmeteo_id" id="3bmeteo_id" value="<?php echo $item->str("3bmeteo_id"); ?>">
				</td>
			  </tr>

		  	 
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
			<< <a href="<?php echo $suffixpage;?>_elenco.php">vai all'elenco</a>
      	    <br />
	</div>
	
	<div style="clear:both; height:5px;">
	&nbsp;
	</div>

</div>
</div>
</body>
</html>
