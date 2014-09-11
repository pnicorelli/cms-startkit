<?php
//controllo se si sta facendo un inserimento
if(isset($_POST["save"]) and $_POST["save"]=="si")
{
	$titolo = $_POST["titolo"];
			
	$object = new Listini();
	
	if(isset($_POST["insert"]) and $_POST["insert"] == "si")
	{
		$object->add($titolo);
		//recupero l'ultimo id inserito
		$db = new Db();
		$id = $db->get_last_id();
	}
	elseif(isset($_POST["update"]) and $_POST["update"] == "si") 
	{
		$id = aggiusta_post($_GET["id"]);
		$object->update($id, $titolo);		
	}
		
		
	print "<script language='Javascript'>window.location.replace('listini_dettaglio.php?id=".$id."&ins=si');</script>";
	
}
	
	$titolo = "";
	
	if(isset($_GET["id"]) and $_GET["id"] <> "")
	{		
		$id = aggiusta_post($_GET["id"]);
	
		$titolo = $array["list_titolo"];
	}
	
?>			 
			
		<div id="tabs">
			<ul>
				<li><a href="#tabs-1">Listino</a></li>

			</ul>	
		

		 <div id="tabs-1">
			  <table width="100%" border="0" cellspacing="2" cellpadding="2" class="testo">
				<tr>
                  <td class="backscuro">Titolo*</td>
                  <td class="backchiaro"><input type="text" class="input" name="titolo" value="<?php print $titolo?>" /></td>
			    </tr>
             </table>
		</div>
		
	 
	 
	 </div>
		
			
	  <table width="100%" border="0" cellspacing="2" cellpadding="2" class="testo">
			<tr>
			  <td height="30" colspan="2"><font size="1">*campi obbligatori</font></td>
			</tr>
                <tr>
                  <td height="30" colspan="2" align="center"><input type="button" value="Salva il listino" class="button" onClick="controlla_modulo();" /></td>
                </tr>
	   </table>
