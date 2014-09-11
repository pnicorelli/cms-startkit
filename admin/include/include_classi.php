<?php
//controllo se si sta facendo un inserimento
if(isset($_POST["save"]) and $_POST["save"]=="si")
{
	$nome = $_POST["nome"];
	$nome_ita = $_POST["nome_ita"];
	$nome_ted = $_POST["nome_ted"];
			
	$object = new Classi();
	
	if(isset($_POST["insert"]) and $_POST["insert"] == "si")
	{
		$object->add($nome, $nome_ita, $nome_ted);
		//recupero l'ultimo id inserito
		$db = new Db();
		$id = $db->get_last_id();
	}
	elseif(isset($_POST["update"]) and $_POST["update"] == "si") 
	{
		$id = aggiusta_post($_GET["id"]);
		$object->update($id, $nome, $nome_ita, $nome_ted);
		
		//upload dei file
		update_file("pr_classe_merceologica", "classi_dettaglio.php?errore=file&id=".$id, $id);
	}
		
		
	print "<script language='Javascript'>window.location.replace('classi_dettaglio.php?id=".$id."&ins=si');</script>";
	
}
	
	$nome = "";
	$nome_ita = "";
	$nome_ted = "";
	
	if(isset($_GET["id"]) and $_GET["id"] <> "")
	{		
		$id = aggiusta_post($_GET["id"]);
	
		$nome = $array["cm_nome"];
		$nome_ita = $array["cm_nome_ita"];
		$nome_ted = $array["cm_nome_ted"];
	}
	
?>			 
			
		<div id="tabs">
			<ul>
				<li><a href="#tabs-1"><img src="img/eng.jpg" width="28" height="13"> Inglese</a></li>
                <li><a href="#tabs-2"><img src="img/ita.jpg" width="28" height="13"> Italiano</a></li>
				<li><a href="#tabs-3"><img src="img/ted.jpg" width="28" height="13"> Tedesco</a></li>
				<?php
				if(isset($_GET["id"]) and $_GET["id"] <> "")
				{
				?>
				<li><a href="#tabs-4">Immagine</a></li>
				<?php
				}
				?>
			</ul>	
		

		 <div id="tabs-1">
			  <table width="100%" border="0" cellspacing="2" cellpadding="2" class="testo">
				<tr>
                  <td class="backscuro">Classe*</td>
                  <td class="backchiaro"><input type="text" class="input" name="nome" value="<?php print $nome?>" /></td>
			    </tr>
             </table>
		</div>
		 <div id="tabs-2">
			  <table width="100%" border="0" cellspacing="2" cellpadding="2" class="testo">
				<tr>
                  <td class="backscuro">Classe</td>
                  <td class="backchiaro"><input type="text" class="input" name="nome_ita" value="<?php print $nome_ita?>" /></td>
			    </tr>
             </table>
		</div>
		
		 <div id="tabs-3">
			  <table width="100%" border="0" cellspacing="2" cellpadding="2" class="testo">
				<tr>
                  <td class="backscuro">Classe</td>
                  <td class="backchiaro"><input type="text" class="input" name="nome_ted" value="<?php print $nome_ted?>" /></td>
			    </tr>
             </table>
		 </div>
		<?php
		if(isset($_GET["id"]) and $_GET["id"] <> "")
		{	
		?>
		 <div id="tabs-4">
            <table width="100%" border="0" cellspacing="2" cellpadding="2" class="testo tblimg">
                <?php
				$campiTbl = dimmi_campi_tabella("pr_classe_merceologica");
				$arrayCampi = explode(":", $campiTbl);

				foreach($arrayCampi as $campoTbl)
				{				
					$idT = dimmi_id_tabella("pr_classe_merceologica", $campoTbl);
					$cartellaUploadFile = dimmi_cartella_upload($idT);
					$percorsoFile = "../".UPLOAD_FILE."/".$cartellaUploadFile."/";
					print scrivi_campi_dett($idT, $id, $percorsoFile, "tblimg");
				}
				?>
			</table>        

		 </div>
		<?php
		}
		?>
	 
	 
	 </div>
		
			
	  <table width="100%" border="0" cellspacing="2" cellpadding="2" class="testo">
			<tr>
			  <td height="30" colspan="2"><font size="1">*campi obbligatori</font></td>
			</tr>
                <tr>
                  <td height="30" colspan="2" align="center"><input type="button" value="Salva la classe" class="button" onClick="controlla_modulo();" /></td>
                </tr>
	   </table>
