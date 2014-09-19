<?php
require_once("initback.php");

$suffixpage = "tag";
$id = $ww->getInt("id", 0);


$item = new dboTag($id);

//$ww->dump($item);

if( isset( $item->item[$item->tableid] ) ){
		$id = $item->item[$item->tableid];
}

$update = $ww->get("update", "");
$updated = false;

if($update == "update"){

		$valBox = false;
		if(isset($_POST["box"]) and $ww->get("box")=="t")
		{ $valBox = true; }

		$item->item["tag"] = $ww->get("tag");
		$item->item["tag_eng"] = $ww->get("tag_eng");
		$item->item["importanza"] = $ww->get("importanza");
		$item->item["box"] = $valBox;

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
<script language="javascript">
//salvo in un'array i campi del modulo su cui devo effettuare dei controlli al momento del submit
//se la funzione javascipt non è associata a un campo al posto del nome e della sua descrizione metto "-"

var arcampicontrollo= new Array();
arcampicontrollo[0]="tag:Tag:obbligatorio"; 

function controlla_modulo(){

	ritorno = controllaeinviamodulo('form',arcampicontrollo);

	if(ritorno == true)
		document.form.submit();
}

$(document).ready( function(){
		$( "#tabs" ).tabs();
		
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
            	<li><a href="#tabs-3">Generali</a></li>
			</ul>	
		
        
   		<div id="tabs-1">
			  <table width="100%" border="0" cellspacing="2" cellpadding="2" class="testo tblimg">
              <tr>
				<td class="backscuro" width="130">Tag</td>
				<td class="backchiaro"><input type="text" class="input" name="tag" id="tag" value="<?php echo $item->str("tag"); ?>" />
				</td>
			  </tr>
              </table>
          </div>
         
         
   		<div id="tabs-2">
			 <table width="100%" border="0" cellspacing="2" cellpadding="2" class="testo tblimg">
              <tr>
				<td class="backscuro" width="130">Tag</td>
				<td class="backchiaro"><input type="text" class="input" name="tag_eng" id="tag_eng" value="<?php echo $item->str("tag_eng"); ?>" />
				</td>
			  </tr>
              </table>
         </div>
           
   		<div id="tabs-3">
			 <table width="100%" border="0" cellspacing="2" cellpadding="2" class="testo tblimg">
              <tr>
				<td class="backscuro" width="130">Importanza</td>
				<td class="backchiaro">
                	<select name="importanza" class="select">
                    	<?php
                        for($i=1; $i<=4; $i++)
						{ 
							print "<option ";
							if($item->getItem("importanza")==$i)
							{ print "selected"; }
							print ">$i</option>";
						}
						?>
                    </select>
				</td>
			  </tr>
              <tr>
				<td class="backscuro" width="130">Inserisci in box</td>
				<td class="backchiaro"><input type="checkbox" class="input" name="box" id="box" value="t" <?php if($item->getItem("box") == 1) print " checked";?>  />
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
				<td align="center"><input type="button" value="salva" class="button"  onClick="controlla_modulo();" ></td>
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