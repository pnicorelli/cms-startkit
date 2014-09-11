<?php
//controllo se si sta facendo un inserimento
if(isset($_POST["save"]) and $_POST["save"]=="si")
{
	$fk_cat = $_POST["categoria"];
	$sottocategoria = $_POST["sottocategoria"];
	$sottocategoria_ita = $_POST["sottocategoria_ita"];
	$sottocategoria_ted = $_POST["sottocategoria_ted"];
	$pubblicato_eur = "f";
	$pubblicato_uk = "f";

	if(isset($_POST["pubblicato_eur"]) and $_POST["pubblicato_eur"]=="t")
	{ $pubblicato_eur = "t"; }		
	if(isset($_POST["pubblicato_uk"]) and $_POST["pubblicato_uk"]=="t")
	{ $pubblicato_uk = "t"; }		


	$object = new Sottocategorie();
	
	if(isset($_POST["insert"]) and $_POST["insert"] == "si")
	{
		$object->add($fk_cat, $sottocategoria, $sottocategoria_ita, $sottocategoria_ted, $pubblicato_eur, $pubblicato_uk);
		//recupero l'ultimo id inserito
		$db = new Db();
		$id = $db->get_last_id();
	}
	elseif(isset($_POST["update"]) and $_POST["update"] == "si") 
	{
		$id = aggiusta_post($_GET["id"]);
		$object->update($id, $fk_cat, $sottocategoria, $sottocategoria_ita, $sottocategoria_ted, $pubblicato_eur, $pubblicato_uk);
		
		//upload dei file
		update_file("pr_sottocategoria", "sottocategorie_dettaglio.php?errore=file&id=".$id, $id);
	}
		
		
	print "<script language='Javascript'>window.location.replace('sottocategorie_dettaglio.php?id=".$id."&ins=si');</script>";
	
}
	
	$fkclasse="";
	$sottocategoria = "";
	$sottocategoria_ita = "";
	$sottocategoria_ted = "";
	$pubblicato_eur = "";
	$pubblicato_uk = "";

	if(isset($_GET["id"]) and $_GET["id"] <> "")
	{		
		$id = aggiusta_post($_GET["id"]);
		
		$objCat = new Categorie();
		$arrCat = $objCat->getById($array["sc_fkcat"]);
	
		$fkclasse=$arrCat["cat_fkclasse"];
		$sottocategoria = $array["sc_sottocat"];
		$sottocategoria_ita = $array["sc_sottocat_ita"];
		$sottocategoria_ted = $array["sc_sottocat_ted"];
		$pubblicato_eur = $array["sc_pubblica_eur"];
		$pubblicato_uk = $array["sc_pubblica"];
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
                  <select name="fkclasse" id="marchio" class="select" onChange="ajaxCategorie('','');"><option value="">--</option>
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
                  <td width="131" class="backscuro">Categoria*</td>
                  <td width="655" class="backchiaro" id="td_cat"><p class="notaPreAjax">Seleziona la classe merceologica</p><input type='hidden' name='sottocat' value='' /></td>
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
                  <td class="backscuro" width="180">Sottocategoria*</td>
                  <td class="backchiaro"><input type="text" class="input" name="sottocategoria" value="<?php print $sottocategoria?>" /></td>
			    </tr>
             </table>
		</div>
		 <div id="tabs-3">
			  <table width="100%" border="0" cellspacing="2" cellpadding="2" class="testo">
				<tr>
                  <td class="backscuro" width="180">Sottocategoria</td>
                  <td class="backchiaro"><input type="text" class="input" name="sottocategoria_ita" value="<?php print $sottocategoria_ita?>" /></td>
			    </tr>
             </table>
		</div>
		
		 <div id="tabs-4">
			  <table width="100%" border="0" cellspacing="2" cellpadding="2" class="testo">
				<tr>
                  <td class="backscuro" width="180">Sottocategoria</td>
                  <td class="backchiaro"><input type="text" class="input" name="sottocategoria_ted" value="<?php print $sottocategoria_ted?>" /></td>
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
				$campiTbl = dimmi_campi_tabella("pr_sottocategoria");
				$arrayCampi = explode(":", $campiTbl);

				foreach($arrayCampi as $campoTbl)
				{				
					$idT = dimmi_id_tabella("pr_sottocategoria", $campoTbl);
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
                  <td height="30" colspan="2" align="center"><input type="button" value="Salva la sottocategoria" class="button" onClick="controlla_modulo();" /></td>
                </tr>
	   </table>
