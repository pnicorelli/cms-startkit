<?php
require_once("initback.php");

$suffixpage = "blog";
$id = $ww->getInt("id", 0);


$item = new dboBlog($id);


if( isset( $item->item[$item->tableid] ) ){
		$id = $item->item[$item->tableid];
}

$update = $ww->get("update", "");
$updated = false;

$data = date("Y-m-d");
if($update == "update"){

		
		if($_POST["data"] <> "")
		{ 
			list($gg, $mm, $aa) = explode("/", $ww->get("data"));
			$data = $aa."-".$mm."-".$gg;
		}
		$item->item["tipologia"] = $ww->get("tipologia");
		$item->item["autore"] = $ww->get("autore");
		$item->item["titolo"] = $ww->get("titolo");
		$item->item["abstract"] = $ww->get("abstract");
		$item->item["testo"] = $ww->get("testo");
		$item->item["data"] = $data;

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
<meta http-equiv="Content-Type" content="text/html; charset=<?php echo CHARSET; ?>">
<?php
require_once("header_admin.php");
?>
<script src="../js/ckeditor/ckeditor.js"></script>
<script src="../js/ckeditor/adapters/jquery.js"></script>
<script language="javascript">
//salvo in un'array i campi del modulo su cui devo effettuare dei controlli al momento del submit
//se la funzione javascipt non è associata a un campo al posto del nome e della sua descrizione metto "-"

var arcampicontrollo= new Array();
arcampicontrollo[0]="titolo:Titolo:obbligatorio"; 

function controlla_modulo(){

	ritorno = controllaeinviamodulo('form',arcampicontrollo);

	if(ritorno == true)
		document.form.submit();
}

$(document).ready( function(){
		$( "#tabs" ).tabs();
		
		$.datepicker.setDefaults( $.datepicker.regional[ "it" ] );
		$( "#data" ).datepicker(  );	
	
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
		
        <div id="tabs">
			<ul>
            	<li><a href="#tabs-1">Generali</a></li>
                <li><a href="#tabs-2"><img src="img/ita.jpg"> Italiano</a></li>
                <li><a href="#tabs-3"><img src="img/eng.jpg"> Inglese</a></li>
			</ul>	
		
        
   		<div id="tabs-1">
			  <table width="100%" border="0" cellspacing="2" cellpadding="2" class="testo tblimg">
              <tr>
				<td class="backscuro" width="130">Data</td>
				<td class="backchiaro"><input type="text" class="input" name="data" id="data" value="<?php print $data ?>" />
				</td>
			  </tr>
			  <tr>
				<td class="backscuro">Autore</td>
				<td class="backchiaro"><input type="text" name="autore" id="autore" value="<?php echo $item->str("autore"); ?>">
				</td>
			  </tr>  
             
             <?php  ?> 
                <tr>
				<td colspan="2" >
					
				<table width="100%" class="listafile" cellpadding="2" cellspacing="2">
					<tr>
					<td width="130"></td>
					<td ></td>
					</tr>
                    <tr height="30"><td colspan="2" class="backscuro" align="center"><strong>GESTIONE FOTO</strong></td></tr>
					<?php
					$objFile = new GestioneFile("blog","file","file");
					if( $id>0 ) {
					
						print $objFile->campi_dett($id);
					}
					else
					{
						print $objFile->campi_vuoti();
					}
					?>
				</table>
				</td>
				</tr>  
                             
			<?php if( $id>0 ) { ?>

			  <tr>
				<td colspan="2" >
					
				<table width="100%" class="listafoto" cellpadding="2" cellspacing="2">
					<tr>
					<td width="130"></td>
					<td ></td>
					</tr>
                    <tr height="30"><td colspan="2" class="backscuro" align="center"><strong>GESTIONE FOTO</strong></td></tr>
					<?php
					$objFile = new GestioneFile("blog","img","foto");
					print $objFile->campi_dett($id, "listafoto");
					?>
				</table>
				</td>
				</tr><?php

				}
					?>
              
                       
              </table>
           </div>
           
           <div id="tabs-2">
			  <table width="100%" border="0" cellspacing="2" cellpadding="2" class="testo tblimg">
			  <tr>
				<td class="backscuro" width="130">Titolo</td>
				<td class="backchiaro"><input type="text" name="titolo" id="titolo" value="<?php echo $item->str("titolo"); ?>">
				</td>
			  </tr >
			  <tr>
				<td class="backscuro" width="130">Abstract</td>
				<td class="backchiaro"><input type="text" name="abstract" id="abstract" value="<?php echo $item->str("abstract"); ?>">
				</td>
			  </tr >             
              <tr>
				<td class="backscuro" width="130">Testo</td>
				<td class="backchiaro"><textarea name="testo" id="testo" ><?php echo $item->str("testo"); ?></textarea>
				</td>
			  </tr>
	
		  	 </table>
            </div>
		 
           <div id="tabs-3">
			  <table width="100%" border="0" cellspacing="2" cellpadding="2" class="testo tblimg">
         
			  <tr>
				<td class="backscuro" width="130">Titolo</td>
				<td class="backchiaro"><input type="text" name="titolo_eng" id="titolo_eng" value="<?php echo $item->str("titolo_eng"); ?>">
				</td>
			  </tr >
			  <tr>
				<td class="backscuro" width="130">Abstract</td>
				<td class="backchiaro"><input type="text" name="abstract_eng" id="abstract_eng" value="<?php echo $item->str("abstract_eng"); ?>">
				</td>
			  </tr >             
              <tr>
				<td class="backscuro" width="130">Testo</td>
				<td class="backchiaro"><textarea name="testo_eng" id="testo_eng" ><?php echo $item->str("testo_eng"); ?></textarea>
				</td>
			  </tr>
         	  </table>
           </div>
         
        </div>
           
			 <table width="100%" border="0" cellspacing="2" cellpadding="2" class="testo tblimg">
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
				<td align="center"><input type="submit" value="salva" class="button"></td>
			  </tr>
			  
			  </table>
            </form>
            << <a href="<?php print $suffixpage?>_elenco.php">vai all'elenco</a>
      	    <br />      	    <br />
	</div>
	
	<div style="clear:both; height:5px;">
	&nbsp;
	</div>

</div>
</div>
</body>
</html>