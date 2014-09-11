<?php
//controllo se si sta facendo un inserimento
if(isset($_POST["save"]) and $_POST["save"]=="si")
{
	$titolo = $_POST["titolo"];
	$titolo_ita = $_POST["titolo_ita"];
	$titolo_ted = $_POST["titolo_ted"];
	
	if(isset($_POST["home"]) and $_POST["home"]=="t")
	{ $home = "t"; }
	else
	{ $home = "f"; }
		
	if(isset($_POST["prodotti"]) and $_POST["prodotti"]=="t")
	{ $prodotti = "t"; }
	else
	{ $prodotti = "f"; }		
		
	$foto = new Foto();
	
	if(isset($_POST["insert"]) and $_POST["insert"] == "si")
	{
		$foto->add($titolo, $titolo_ita, $titolo_ted, $home, $prodotti);
		//recupero l'ultimo id inserito
		$db = new Db();
		$id = $db->get_last_id();
	}
	elseif(isset($_POST["update"]) and $_POST["update"] == "si") 
	{
		$id = aggiusta_post($_GET["id"]);
		$foto->update($id, $titolo, $titolo_ita, $titolo_ted, $home, $prodotti);
		
		//upload dei file
		update_file("foto", "foto_dettaglio.php?errore=file&id=".$id, $id);
	}

	
	print "<script language='Javascript'>window.location.replace('foto_dettaglio.php?id=".$id."&ins=si');</script>";
	
}
	
	$titolo = "";
	$titolo_ita = "";
	$titolo_ted = "";
	$home = "";
	$prodotti = "";
	
	if(isset($_GET["id"]) and $_GET["id"] <> "")
	{		
		$id = aggiusta_post($_GET["id"]);
	
		$titolo = $array["ft_titolo"];
		$titolo_ita = $array["ft_titolo_ita"];
		$titolo_ted = $array["ft_titolo_ted"];;
		$home = $array["ft_home"];
		$prodotti = $array["ft_prodotti"];
	}
	
	
?>			 
			
		<div id="tabs">
			<ul>
				<li><a href="#tabs-1">Generali</a></li>
				<li><a href="#tabs-2"><img src="img/eng.jpg" width="28" height="13"> Inglese</a></li>
                <li><a href="#tabs-3"><img src="img/ita.jpg" width="28" height="13"> Italiano</a></li>
				<li><a href="#tabs-4"><img src="img/ted.jpg" width="28" height="13"> Tedesco</a></li>
			</ul>	
		
		
		 <div id="tabs-1">
			  <table width="100%" border="0" cellspacing="2" cellpadding="2" class="testo tblimg">
				<tr>
                  <td width="131" class="backscuro">Home</td>
                  <td width="655" class="backchiaro"><input type="checkbox" name="home" class="input" value="t" <?php if($home=="t") print " checked";?>></td>
                </tr>
				<tr>
                  <td width="131" class="backscuro">Prodotti</td>
                  <td width="655" class="backchiaro"><input type="checkbox" name="prodotti" class="input" value="t" <?php if($prodotti=="t") print " checked";?>></td>
                </tr>
				<?php
                if(isset($_GET["id"]) and $_GET["id"] <> "")
                {	
					$campiTbl = dimmi_campi_tabella("foto");
					$arrayCampi = explode(":", $campiTbl);
	
					foreach($arrayCampi as $campoTbl)
					{				
						$idT = dimmi_id_tabella("foto", $campoTbl);
						$cartellaUploadFile = dimmi_cartella_upload($idT);
						$percorsoFile = "../".UPLOAD_FILE."/".$cartellaUploadFile."/";
						print scrivi_campi_dett($idT, $id, $percorsoFile, "tblimg");
					}
                }
                ?>                
			</table>
		 </div>
		 <div id="tabs-2">
			  <table width="100%" border="0" cellspacing="2" cellpadding="2" class="testo">
				<tr>
                  <td width="131" class="backscuro">Titolo*</td>
                  <td width="655" class="backchiaro"><input type="text" name="titolo" class="input" size="50" value="<?php print $titolo;?>"></td>
                </tr>
             </table>
		</div>
		 <div id="tabs-3">
			  <table width="100%" border="0" cellspacing="2" cellpadding="2" class="testo">
				<tr>
                  <td width="131" class="backscuro">Titolo</td>
                  <td width="655" class="backchiaro"><input type="text" name="titolo_ita" class="input" size="50" value="<?php print $titolo_ita;?>"></td>
                </tr>
             </table>
		</div>
		
		 <div id="tabs-4">
			  <table width="100%" border="0" cellspacing="2" cellpadding="2" class="testo">
				<tr>
                  <td width="131" class="backscuro">Titolo</td>
                  <td width="655" class="backchiaro"><input type="text" name="titolo_ted" class="input" size="50" value="<?php print $titolo_ted;?>"></td>
                </tr>
             </table>
		 </div>
		
	 
	 </div>
		
			
	  <table width="100%" border="0" cellspacing="2" cellpadding="2" class="testo">
			<tr>
			  <td colspan="2"><font size="1">*campi obbligatori<br />
              </font></td>
			</tr>
            <tr>
              <td height="30" colspan="2" align="center"><input type="button" value="Salva la foto" class="button" onClick="controlla_modulo();" /></td>
            </tr>
	   </table>
