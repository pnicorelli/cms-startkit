<?php
//controllo se si sta facendo un inserimento
if(isset($_POST["save"]) and $_POST["save"]=="si")
{
	$fk_classe = $_POST["fkclasse"];
	$categoria = $_POST["categoria"];
	$categoria_ita = $_POST["categoria_ita"];
	$categoria_ted = $_POST["categoria_ted"];
	$pubblicato_eur = "f";
	$pubblicato_uk = "f";

	if(isset($_POST["pubblicato_eur"]) and $_POST["pubblicato_eur"]=="t")
	{ $pubblicato_eur = "t"; }		
	if(isset($_POST["pubblicato_uk"]) and $_POST["pubblicato_uk"]=="t")
	{ $pubblicato_uk = "t"; }		


	$object = new Categorie();
	
	if(isset($_POST["insert"]) and $_POST["insert"] == "si")
	{
		$object->add($fk_classe, $categoria, $categoria_ita, $categoria_ted, $pubblicato_eur, $pubblicato_uk);
		//recupero l'ultimo id inserito
		$db = new Db();
		$id = $db->get_last_id();
	}
	elseif(isset($_POST["update"]) and $_POST["update"] == "si") 
	{
		$id = aggiusta_post($_GET["id"]);
		$object->update($id, $fk_classe, $categoria, $categoria_ita, $categoria_ted, $pubblicato_eur, $pubblicato_uk);
		
		//upload dei file
		update_file("pr_categoria", "categorie_dettaglio.php?errore=file&id=".$id, $id);
	}
		
		
	print "<script language='Javascript'>window.location.replace('categorie_dettaglio.php?id=".$id."&ins=si');</script>";
	
}
	
	$fkclasse = "";
	$categoria = "";
	$categoria_ita = "";
	$categoria_ted = "";
	$pubblicato_eur = "";
	$pubblicato_uk = "";

	if(isset($_GET["id"]) and $_GET["id"] <> "")
	{		
		$id = aggiusta_post($_GET["id"]);
	
		$fkclasse = $array["cat_fkclasse"];
		$categoria = $array["cat_categoria"];
		$categoria_ita = $array["cat_categoria_ita"];
		$categoria_ted = $array["cat_categoria_ted"];
		$pubblicato_eur = $array["cat_pubblica_eur"];
		$pubblicato_uk = $array["cat_pubblica"];
	}
	
?>			 
		<div id="tabs">
			<ul>
            	<li><a href="#tabs-1">Generali</a></li>
				<li><a href="#tabs-2"><img src="img/eng.jpg" width="28" height="13"> Inglese</a></li>
                <li><a href="#tabs-3"><img src="img/ita.jpg" width="28" height="13"> Italiano</a></li>
				<li><a href="#tabs-4"><img src="img/ted.jpg" width="28" height="13"> Tedesco</a></li>
				<?php
				if(isset($_GET["id"]) and $_GET["id"] <> "")
				{
				?>
				<li><a href="#tabs-5">Immagine</a></li>
				<?php
				}
				?>
			</ul>	
		
        
   		<div id="tabs-1">
			  <table width="100%" border="0" cellspacing="2" cellpadding="2" class="testo">
				<tr>
                  <td class="backscuro" width="180">Classe merceologica*</td>
                  <td class="backchiaro">
                  <select name="fkclasse" class="select"><option value="">--</option>
                  <?php
                  $objCM = new Classi();
				  $arrCM = $objCM->getAll(" order by cm_sorting");
				  
				  foreach($arrCM as $itemCM)
				  {
					  	print "<option value='".$itemCM["cm_id"]."'";
						if($fkclasse==$itemCM["cm_id"])
						{ print " selected"; }
						print ">".$itemCM["cm_nome"]."</option>";
				  }
				  ?>
                  </select>
                  </td>
			    </tr>
				<tr>
                  <td class="backscuro" width="180">Pubblicato EUR</td>
                  <td class="backchiaro"><input type="checkbox" name="pubblicato_eur" value="t" <?php if($pubblicato_eur == "t") print " checked";?> /></td>
			    </tr>
				<tr>
                  <td class="backscuro" width="180">Pubblicato UK</td>
                  <td class="backchiaro"><input type="checkbox" name="pubblicato_uk" value="t"  <?php if($pubblicato_uk == "t") print " checked";?> /></td>
			    </tr>                
             </table>
		</div>		 
        <div id="tabs-2">
			  <table width="100%" border="0" cellspacing="2" cellpadding="2" class="testo">
				<tr>
                  <td class="backscuro" width="180">Categoria*</td>
                  <td class="backchiaro"><input type="text" class="input" name="categoria" value="<?php print $categoria?>" /></td>
			    </tr>
             </table>
		</div>
		 <div id="tabs-3">
			  <table width="100%" border="0" cellspacing="2" cellpadding="2" class="testo">
				<tr>
                  <td class="backscuro" width="180">Categoria</td>
                  <td class="backchiaro"><input type="text" class="input" name="categoria_ita" value="<?php print $categoria_ita?>" /></td>
			    </tr>
             </table>
		</div>
		
		 <div id="tabs-4">
			  <table width="100%" border="0" cellspacing="2" cellpadding="2" class="testo">
				<tr>
                  <td class="backscuro" width="180">Categoria</td>
                  <td class="backchiaro"><input type="text" class="input" name="categoria_ted" value="<?php print $categoria_ted?>" /></td>
			    </tr>
             </table>
		 </div>
		<?php
		if(isset($_GET["id"]) and $_GET["id"] <> "")
		{	
		?>
		 <div id="tabs-5">
            <table width="100%" border="0" cellspacing="2" cellpadding="2" class="testo tblimg">
                <?php
				$campiTbl = dimmi_campi_tabella("pr_categoria");
				$arrayCampi = explode(":", $campiTbl);

				foreach($arrayCampi as $campoTbl)
				{				
					$idT = dimmi_id_tabella("pr_categoria", $campoTbl);
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
                  <td height="30" colspan="2" align="center"><input type="button" value="Salva la categoria" class="button" onClick="controlla_modulo();" /></td>
                </tr>
	   </table>
