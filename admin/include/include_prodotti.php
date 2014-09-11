<?php
$objListini = new Listini();
$arrListini = $objListini->getAll(" order by list_titolo");

$objPrezzi = new Listini_prezzi();


//controllo se si sta facendo un inserimento
if(isset($_POST["save"]) and $_POST["save"]=="si")
{
	$fk_cm = $_POST["fkclasse"];
	$fk_cat = $_POST["categoria"];
	$fk_sottocat = $_POST["sottocat"];
	
	$code = $_POST["code"];
	$prezzounita = $_POST["prezzounita"];
	$prezzounita_eur = $_POST["prezzounita_eur"];
	$prezzooff = $_POST["prezzooff"];
	$prezzooff_eur = $_POST["prezzooff_eur"];
	$numpezzi = $_POST["numpezzi"];
	$dimensione = $_POST["dimensione"];
	$peso = $_POST["peso"];
	$magazzino = $_POST["magazzino"];
	$magazzino_eng = $_POST["magazzino_eng"];
	$pubblicato_eur = "f";
	$pubblicato_uk = "f";
	$titolo = $_POST["titolo"];
	$desc = $_POST["descrizione"];
	$titolo_ita = $_POST["titolo_ita"];
	$desc_ita = $_POST["descrizione_ita"];
	$titolo_ted = $_POST["titolo_ted"];
	$desc_ted = $_POST["descrizione_ted"];
			
	$nuovo = "";
	if(isset($_POST["nuovo"]) and $_POST["nuovo"] == "t")
	{ $nuovo = "t"; }		
			
	if(isset($_POST["pubblicato_eur"]) and $_POST["pubblicato_eur"]=="t")
	{ $pubblicato_eur = "t"; }		
	if(isset($_POST["pubblicato_uk"]) and $_POST["pubblicato_uk"]=="t")
	{ $pubblicato_uk = "t"; }		
			
	$object = new Prodotti();
	
	if(isset($_POST["insert"]) and $_POST["insert"] == "si")
	{
		$object->add($fk_cm, $fk_cat, $fk_sottocat, $code, $titolo, $titolo_ita, $titolo_ted, $desc, $desc_ita, $desc_ted, $prezzounita, $prezzounita_eur, $numpezzi, $prezzooff, $prezzooff_eur, $dimensione, $peso, $magazzino, $magazzino_eng, $pubblicato_eur, $pubblicato_uk, $nuovo);
		//recupero l'ultimo id inserito
		$db = new Db();
		$id = $db->get_last_id();
	}
	elseif(isset($_POST["update"]) and $_POST["update"] == "si") 
	{
		$id = aggiusta_post($_GET["id"]);
		$object->update($id, $fk_cm, $fk_cat, $fk_sottocat, $code, $titolo, $titolo_ita, $titolo_ted, $desc, $desc_ita, $desc_ted, $prezzounita, $prezzounita_eur, $numpezzi, $prezzooff, $prezzooff_eur, $dimensione, $peso, $magazzino, $magazzino_eng, $pubblicato_eur, $pubblicato_uk, $nuovo);
		
		//upload dei file
		update_file("pr_prodotti", "prodotti_dettaglio.php?errore=file&id=".$id, $id);
	}
	
	
	//gestione listini: cancello e reinserisco
	$objPrezzi->delete_byproduct($id);
	
	foreach($arrListini as $list)
	{
		$sterline = $_POST["prezzo_".$list["list_id"]];
		$euro = $_POST["prezzo_eur_".$list["list_id"]];
		
		$sterline_off = $_POST["prezzo_off_".$list["list_id"]];
		$euro_off = $_POST["prezzo_eur_off_".$list["list_id"]];
		
		if(($sterline<>"" and $sterline<>0) or ($euro<>"" and $euro<>0))
		{ $objPrezzi->add($list["list_id"], $id, $sterline, $euro, $sterline_off, $euro_off);  }
	}
	
		
	
	print "<script language='Javascript'>window.location.replace('prodotti_dettaglio.php?id=".$id."&ins=si');</script>";
	
}
	
	$fkclasse="";
	$code = "";
	$prezzounita = "";
	$prezzooff = "";
	$prezzounita_eur = "";
	$prezzooff_eur = "";
	$sterline_off = "";
	$euro_off = "";
	$numpezzi = "";
	$dimensione = "";
	$peso = "";
	$magazzino = "";
	$magazzino_eng = "";
	$pubblicato_eur = "";
	$pubblicato_uk = "";
	$nuovo = "";
	$titolo = "";
	$descrizione = "";
	$titolo_ita = "";
	$descrizione_ita = "";
	$titolo_ted = "";
	$descrizione_ted = "";

	if(isset($_GET["id"]) and $_GET["id"] <> "")
	{		
		$id = aggiusta_post($_GET["id"]);
			
		$fkclasse=$array["pr_fkcm"];
		
		$code = $array["pr_code"];
		$prezzounita = $array["pr_prezzounita"];
		$prezzooff = $array["pr_prezzo_offerta"];
		$prezzounita_eur = $array["pr_prezzounita_eur"];
		$prezzooff_eur = $array["pr_prezzo_offerta_eur"];
		$numpezzi = $array["pr_numeropezzi"];
		$dimensione = $array["pr_dimensione"];
		$peso = $array["pr_peso"];
		$magazzino = $array["pr_magazzino"];
		$magazzino_eng = $array["pr_magazzino_eng"];
		$pubblicato_eur = $array["pr_pubblica_eur"];
		$pubblicato_uk = $array["pr_pubblica"];
		$nuovo = $array["pr_novita"];
		$titolo = $array["pr_titolo"];
		$descrizione = $array["pr_desc"];
		$titolo_ita = $array["pr_titolo_ita"];
		$descrizione_ita = $array["pr_desc_ita"];
		$titolo_ted = $array["pr_titolo_ted"];
		$descrizione_ted = $array["pr_desc_ted"];
	}
	
?>			 
		<div id="tabs">
			<ul>
            	<li><a href="#tabs-1">Generali</a></li>
                <li><a href="#tabs-2">Listini</a></li>
				<li><a href="#tabs-3"><img src="img/eng.jpg" width="28" height="13"> Inglese</a></li>
                <li><a href="#tabs-4"><img src="img/ita.jpg" width="28" height="13"> Italiano</a></li>
				<li><a href="#tabs-5"><img src="img/ted.jpg" width="28" height="13"> Tedesco</a></li>
				<?php
				if(isset($_GET["id"]) and $_GET["id"] <> "")
				{
				?>
				<li><a href="#tabs-6">Immagine</a></li>
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
                  <td width="180" class="backscuro">Categoria*</td>
                  <td width="655" class="backchiaro" id="td_cat"><p class="notaPreAjax">Seleziona la classe merceologica</p><input type='hidden' name='categoria' value='' /></td>
                </tr>
                <tr>
                  <td width="180" class="backscuro">Sottocategoria*</td>
                  <td width="655" class="backchiaro" id="td_sottocat"><p class="notaPreAjax">Seleziona la categoria</p><input type='hidden' name='sottocat' value='' /></td>
                </tr>   
				<tr>
                  <td class="backscuro" width="180">Code*</td>
                  <td class="backchiaro"><input type="text" class="input" name="code" value="<?php print $code?>" /></td>
			    </tr>
				<tr>
                  <td class="backscuro" width="180">Numero pezzi*</td>
                  <td class="backchiaro"><input type="text" class="input" name="numpezzi" value="<?php print $numpezzi?>" /></td>
			    </tr>
				<tr>
                  <td class="backscuro" width="180">Dimensione</td>
                  <td class="backchiaro"><input type="text" class="input" name="dimensione" value="<?php print $dimensione?>" /></td>
			    </tr>
				<tr>
                  <td class="backscuro" width="180">Peso</td>
                  <td class="backchiaro"><input type="text" class="input" name="peso" value="<?php print $peso?>" /></td>
			    </tr>
				<tr>
                  <td class="backscuro" width="180">Magazzino*</td>
                  <td class="backchiaro"><input type="text" class="input" name="magazzino" value="<?php print $magazzino?>" /></td>
			    </tr>
				<tr>
                  <td class="backscuro" width="180">Magazzino UK*</td>
                  <td class="backchiaro"><input type="text" class="input" name="magazzino_eng" value="<?php print $magazzino_eng?>" /></td>
			    </tr>
				<tr>
                  <td class="backscuro" width="180">Pubblicato EUR</td>
                  <td class="backchiaro"><input type="checkbox" name="pubblicato_eur" value="t" <?php if($pubblicato_eur == "t") print " checked";?> /></td>
			    </tr>
				<tr>
                  <td class="backscuro" width="180">Pubblicato UK</td>
                  <td class="backchiaro"><input type="checkbox" name="pubblicato_uk" value="t"  <?php if($pubblicato_uk == "t") print " checked";?> /></td>
			    </tr>                
				<tr>
                  <td class="backscuro" width="180">Nuovo prodotto</td>
                  <td class="backchiaro"><input type="checkbox" name="nuovo" value="t" <?php if($nuovo == "t") print " checked";?> /></td>
			    </tr>
             </table>
		</div>
        
        
        <div id="tabs-2">
        	<table width="100%" border="0" cellspacing="2" cellpadding="2" class="testo">
            
				<tr>
				  <td colspan="2" class="backscuro" align="center"><strong>LISTINO B2C</strong></td>
			  </tr>
				<tr>
                  <td class="backscuro" width="180">Prezzo &pound;*</td>
                  <td class="backchiaro"><input type="text" class="input" name="prezzounita" value="<?php print $prezzounita?>" /></td>
			    </tr>
				<tr>
                  <td class="backscuro" width="180">Prezzo offerta &pound;</td>
                  <td class="backchiaro"><input type="text" class="input" name="prezzooff" value="<?php print $prezzooff?>" /></td>
			    </tr>
				<tr>
				  <td class="backscuro">Prezzo &euro;*</td>
				  <td class="backchiaro"><input type="text" class="input" name="prezzounita_eur" value="<?php print $prezzounita_eur?>" /></td>
			    </tr>
				<tr>
				  <td class="backscuro">Prezzo offerta  &euro;</td>
				  <td class="backchiaro"><input type="text" class="input" name="prezzooff_eur" value="<?php print $prezzooff_eur?>" /></td>
			    </tr>
                <?php
                //inserisco la gestione degli altri listini

				foreach($arrListini as $listino)
				{
					$sterline = "";
					$euro = "";
					
					if(isset($_GET["id"]) and $_GET["id"] <> "")
					{
						$arrPr = $objPrezzi->getAll(" and lp_fklistino = ".$listino["list_id"]." and lp_fkprodotto = ".$_GET["id"]);
						if(count($arrPr) > 0)
						{
							$sterline = $arrPr[0]["lp_prezzo"];	
							$euro = $arrPr[0]["lp_prezzo_eur"];
							
							$sterline_off = $arrPr[0]["lp_prezzo_offerta"];	
							$euro_off = $arrPr[0]["lp_prezzo_offerta_eur"];
						}
					}
					
				?>
                    <tr>
                      <td colspan="2" class="backscuro" align="center"><strong><?php print $listino["list_titolo"]?></strong></td>
                    </tr>
                    <tr>
                      <td class="backscuro" width="180">Prezzo &pound;</td>
                      <td class="backchiaro"><input type="text" class="input" name="prezzo_<?php print $listino["list_id"]?>" value="<?php print $sterline?>" /></td>
                    </tr>
                    <tr>
                      <td class="backscuro" width="180">Prezzo offerta &pound;</td>
                      <td class="backchiaro"><input type="text" class="input" name="prezzo_off_<?php print $listino["list_id"]?>" value="<?php print $sterline_off?>" /></td>
                    </tr>        
                   <tr>
                      <td class="backscuro">Prezzo &euro;</td>
                      <td class="backchiaro"><input type="text" class="input" name="prezzo_eur_<?php print $listino["list_id"]?>" value="<?php print $euro?>" /></td>
                    </tr>                    <tr>
                      <td class="backscuro">Prezzo offerta &euro;</td>
                      <td class="backchiaro"><input type="text" class="input" name="prezzo_eur_off_<?php print $listino["list_id"]?>" value="<?php print $euro_off?>" /></td>
                    </tr>
                <?php	
				}
				?>
                
            </table>
        </div>
        		 
        <div id="tabs-3">
			  <table width="100%" border="0" cellspacing="2" cellpadding="2" class="testo">
				<tr>
                  <td class="backscuro" width="180">Titolo*</td>
                  <td class="backchiaro"><input type="text" class="input" name="titolo" value="<?php print $titolo?>" /></td>
			    </tr>
				<tr>
                  <td class="backscuro" width="180">Descrizione</td>
                  <td class="backchiaro"><textarea name="descrizione" class="input" rows="4" cols="40"><?php print html_entity_decode(str_replace("<br />", "", $descrizione))?></textarea></td>
			    </tr>
             </table>
		</div>
		 <div id="tabs-4">
			  <table width="100%" border="0" cellspacing="2" cellpadding="2" class="testo">
				<tr>
                  <td class="backscuro" width="180">Titolo</td>
                  <td class="backchiaro"><input type="text" class="input" name="titolo_ita" value="<?php print $titolo_ita?>" /></td>
			    </tr>
				<tr>
                  <td class="backscuro" width="180">Descrizione</td>
                  <td class="backchiaro"><textarea name="descrizione_ita" class="input" rows="4" cols="40"><?php print html_entity_decode(str_replace("<br />", "", $descrizione_ita))?></textarea></td>
			    </tr>
             </table>
		</div>
		
		 <div id="tabs-5">
			  <table width="100%" border="0" cellspacing="2" cellpadding="2" class="testo">
				<tr>
                  <td class="backscuro" width="180">Titolo</td>
                  <td class="backchiaro"><input type="text" class="input" name="titolo_ted" value="<?php print $titolo_ted?>" /></td>
			    </tr>
				<tr>
                  <td class="backscuro" width="180">Descrizione</td>
                  <td class="backchiaro"><textarea name="descrizione_ted" class="input" rows="4" cols="40"><?php print html_entity_decode(str_replace("<br />", "", $descrizione_ted))?></textarea></td>
			    </tr>
             </table>
		 </div>
		<?php
		if(isset($_GET["id"]) and $_GET["id"] <> "")
		{	
		?>
		 <div id="tabs-6">
            <table width="100%" border="0" cellspacing="2" cellpadding="2" class="testo tblimg">
                <?php
				$campiTbl = dimmi_campi_tabella("pr_prodotti");
				$arrayCampi = explode(":", $campiTbl);

				foreach($arrayCampi as $campoTbl)
				{				
					$idT = dimmi_id_tabella("pr_prodotti", $campoTbl);
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
                  <td height="30" colspan="2" align="center"><input type="button" value="Salva il prodotto" class="button" onClick="controlla_modulo();" /></td>
                </tr>
	   </table>
