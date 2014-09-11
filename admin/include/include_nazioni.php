<?php
//controllo se si sta facendo un inserimento
if(isset($_POST["save"]) and $_POST["save"]=="si")
{
	$nazione = $_POST["nazione"];
	$nazione_ita = $_POST["nazione_ita"];
	$nazione_ted = $_POST["nazione_ted"];
			
	$objNaz = new Nazioni();
	
	if(isset($_POST["insert"]) and $_POST["insert"] == "si")
	{
		$objNaz->add($nazione, $nazione_ita, $nazione_ted);
		//recupero l'ultimo id inserito
		$db = new Db();
		$id = $db->get_last_id();
	}
	elseif(isset($_POST["update"]) and $_POST["update"] == "si") 
	{
		$id = aggiusta_post($_GET["id"]);
		$objNaz->update($id, $nazione, $nazione_ita, $nazione_ted);
	}
		
		
	print "<script language='Javascript'>window.location.replace('nazioni_dettaglio.php?id=".$id."&ins=si');</script>";
	
}
	
	$nazione = "";
	$nazione_ita = "";
	$nazione_ted = "";
	
	if(isset($_GET["id"]) and $_GET["id"] <> "")
	{		
		$id = aggiusta_post($_GET["id"]);
	
		$nazione = $array["naz_nazione"];
		$nazione_ita = $array["naz_nazione_ita"];
		$nazione_ted = $array["naz_nazione_ted"];
	}
	
?>			 
			
		<div id="tabs">
			<ul>
				<li><a href="#tabs-1"><img src="img/eng.jpg" width="28" height="13"> Inglese</a></li>
                <li><a href="#tabs-2"><img src="img/ita.jpg" width="28" height="13"> Italiano</a></li>
				<li><a href="#tabs-3"><img src="img/ted.jpg" width="28" height="13"> Tedesco</a></li>
			</ul>	
		

		 <div id="tabs-1">
			  <table width="100%" border="0" cellspacing="2" cellpadding="2" class="testo">
				<tr>
                  <td class="backscuro">Nazione*</td>
                  <td class="backchiaro"><input type="text" class="input" name="nazione" value="<?php print $nazione?>" /></td>
			    </tr>
             </table>
		</div>
		 <div id="tabs-2">
			  <table width="100%" border="0" cellspacing="2" cellpadding="2" class="testo">
				<tr>
                  <td class="backscuro">Nazione</td>
                  <td class="backchiaro"><input type="text" class="input" name="nazione_ita" value="<?php print $nazione_ita?>" /></td>
			    </tr>
             </table>
		</div>
		
		 <div id="tabs-3">
			  <table width="100%" border="0" cellspacing="2" cellpadding="2" class="testo">
				<tr>
                  <td class="backscuro">Nazione</td>
                  <td class="backchiaro"><input type="text" class="input" name="nazione_ted" value="<?php print $nazione_ted?>" /></td>
			    </tr>
             </table>
		 </div>
		
	 
	 </div>
		
			
	  <table width="100%" border="0" cellspacing="2" cellpadding="2" class="testo">
			<tr>
			  <td height="30" colspan="2"><font size="1">*campi obbligatori</font></td>
			</tr>
                <tr>
                  <td height="30" colspan="2" align="center"><input type="button" value="Salva la nazione" class="button" onClick="controlla_modulo();" /></td>
                </tr>
	   </table>
