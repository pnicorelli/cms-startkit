<?php
require_once("initback.php");

$suffixpage = "about";
$id = $ww->getInt("id", 0);


$item = new dboAbout($id);

//$ww->dump($item);

if( isset( $item->item[$item->tableid] ) ){
		$id = $item->item[$item->tableid];
}

$update = $ww->get("update", "");
$updated = false;

if($update == "update"){

		$item->item["titolo"] = $ww->get("titolo");
		$item->item["abstract"] = $ww->get("abstract");
		$item->item["testo"] = $ww->get("testo");
		$item->item["titolo_eng"] = $ww->get("titolo_eng");
		$item->item["abstract_eng"] = $ww->get("abstract_eng");
		$item->item["testo_eng"] = $ww->get("testo_eng");

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

		
			<form method="post" name="form">
			  <input type="hidden" id="update" name="update" value="update">
			  <input type="hidden" id="id" name="id" value="<?php echo $id;?>">
		
                <div id="tabs">
                    <ul>
                        <li><a href="#tabs-1"><img src="img/ita.jpg"> Italiano</a></li>
                        <li><a href="#tabs-2"><img src="img/eng.jpg"> Inglese</a></li>
                    </ul>	
                
                
                <div id="tabs-1">
                      <table width="100%" border="0" cellspacing="2" cellpadding="2" class="testo tblimg">
                      <tr>
                        <td class="backscuro" width="130">Titolo</td>
                        <td class="backchiaro"><input type="text" class="input" name="titolo" id="titolo" value="<?php echo $item->str("titolo"); ?>" />
                        </td>
                      </tr>
                      <tr>
                        <td class="backscuro" width="130">Abstract</td>
                        <td class="backchiaro"><textarea name="abstract" id="abstract" ><?php echo $item->str("abstract"); ?></textarea>
                        </td>
                      </tr>   
                      <tr>
                        <td class="backscuro" width="130">Testo</td>
                        <td class="backchiaro"><textarea name="testo" id="testo" ><?php echo $item->str("testo"); ?></textarea>
                        </td>
                      </tr>                       
                    </table>
                  </div>
                 
                 
                <div id="tabs-2">
                     <table width="100%" border="0" cellspacing="2" cellpadding="2" class="testo tblimg">
                      <tr>
                        <td class="backscuro" width="130">Titolo</td>
                        <td class="backchiaro"><input type="text" class="input" name="titolo_eng" id="titolo_eng" value="<?php echo $item->str("titolo_eng"); ?>" />
                        </td>
                      </tr>
                      <tr>
                        <td class="backscuro" width="130">Abstract</td>
                        <td class="backchiaro"><textarea name="abstract_eng" id="abstract_eng" ><?php echo $item->str("abstract_eng"); ?></textarea>
                        </td>
                      </tr>   
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
      	    <br />      	   
	</div>
	
	<div style="clear:both; height:5px;">
	&nbsp;
	</div>

</div>
</div>
</body>
</html>