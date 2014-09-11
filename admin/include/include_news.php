<?php
//controllo se si sta facendo un inserimento
if(isset($_POST["save"]) and $_POST["save"]=="si")
{
	$link = $_POST["link"];
	$titolo = $_POST["titolo"];
	$abstract = $_POST["abstract"];
	$descrizione = $_POST["descrizione"];
	$titolo_ita = $_POST["titolo_ita"];
	$abstract_ita = $_POST["abstract_ita"];
	$descrizione_ita = $_POST["descrizione_ita"];
	$titolo_ted = $_POST["titolo_ted"];
	$abstract_ted = $_POST["abstract_ted"];
	$descrizione_ted = $_POST["descrizione_ted"];
	
	if($_POST["date"] <> "")
	{ 
		list($gg, $mm, $aa) = explode("/", $_POST["date"]);
		$data = $aa."-".$mm."-".$gg;
	}
	
	if(isset($_POST["home"]) and $_POST["home"]=="t")
	{ $home = "t"; }
	else
	{ $home = "f"; }
		
	$news = new News();
	
	if(isset($_POST["insert"]) and $_POST["insert"] == "si")
	{
		$news->add($data, $link, $titolo, $abstract, $descrizione, $titolo_ita, $abstract_ita, $descrizione_ita, $titolo_ted, $abstract_ted, $descrizione_ted, $home);
		//recupero l'ultimo id inserito
		$db = new Db();
		$id = $db->get_last_id();
	}
	elseif(isset($_POST["update"]) and $_POST["update"] == "si") 
	{
		$id = aggiusta_post($_GET["id"]);
		$news->update($id, $data, $link, $titolo, $abstract, $descrizione, $titolo_ita, $abstract_ita, $descrizione_ita, $titolo_ted, $abstract_ted, $descrizione_ted, $home);
		
		//upload dei file
		update_file("news", "news_dettaglio.php?errore=file&id=".$id, $id);
	}

	
	print "<script language='Javascript'>window.location.replace('news_dettaglio.php?id=".$id."&ins=si');</script>";
	
}
	
	$data = date("d")."/".date("m")."/".date("Y");
	$link = "";
	$titolo = "";
	$abstract = "";
	$descrizione = "";
	$titolo_ita = "";
	$abstract_ita = "";
	$descrizione_ita = "";
	$titolo_ted = "";
	$abstract_ted = "";
	$descrizione_ted = "";
	$home = "";
	
	if(isset($_GET["id"]) and $_GET["id"] <> "")
	{		
		$id = aggiusta_post($_GET["id"]);
	
		list($aa, $mm, $gg) = explode("-", $array["nw_data"]);
		$data = $gg."/".$mm."/".$aa;
		$link = $array["nw_link"];
		$titolo = $array["nw_titolo"];
		$abstract = $array["nw_abstract"];
		$descrizione = $array["nw_descrizione"];
		$titolo_ita = $array["nw_titolo_ita"];
		$abstract_ita = $array["nw_abstract_ita"];
		$descrizione_ita = $array["nw_descrizione_ita"];
		$titolo_ted = $array["nw_titolo_ted"];;
		$abstract_ted =  $array["nw_abstract_ted"];
		$descrizione_ted = $array["nw_descrizione_ted"];
		$home = $array["nw_home"];
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
                  <td width="131" class="backscuro">Data*</td>
                  <td width="655" class="backchiaro"><input type="text" id="date" class="input" name="date" value="<?php print $data?>" /></td>
                </tr>
				<tr>
                  <td width="131" class="backscuro">Link**</td>
                  <td width="655" class="backchiaro"><input type="text" name="link" class="input" size="50" value="<?php print $link;?>"></td>
                </tr>
				<tr>
                  <td width="131" class="backscuro">Home</td>
                  <td width="655" class="backchiaro"><input type="checkbox" name="home" class="input" value="t" <?php if($home=="t") print " checked";?>></td>
                </tr>
                
				<?php
                if(isset($_GET["id"]) and $_GET["id"] <> "")
                {	
					$campiTbl = dimmi_campi_tabella("news");
					$arrayCampi = explode(":", $campiTbl);
	
					foreach($arrayCampi as $campoTbl)
					{				
						$idT = dimmi_id_tabella("news", $campoTbl);
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
				<tr>
                  <td class="backscuro">Abstract</td>
                  <td class="backchiaro vedi_ris"><p>[Vista Sito]</p><textarea name="abstract" class="testo_box_little" ><?php print html_entity_decode(str_replace("<br />", "", $abstract))?></textarea></td>
			    </tr>
				<tr>
					<td  class="backscuro"></td>
					<td class="backchiaro">
					<?php $nome_form = "form"; $nome_campo = "abstract"; include("include_editor.php");?>					</td>
				</tr>
				<tr>
                  <td class="backscuro">Descrizione</td>
                  <td class="backchiaro vedi_ris"><p>[Vista Sito]</p><textarea name="descrizione" class="testo_box"><?php print html_entity_decode(str_replace("<br />", "", $descrizione))?></textarea></td>
			    </tr>
				<tr>
					<td  class="backscuro"></td>
					<td class="backchiaro">
					<?php $nome_form = "form"; $nome_campo = "descrizione"; include("include_editor.php");?>					</td>
				</tr>
             </table>
		</div>
		 <div id="tabs-3">
			  <table width="100%" border="0" cellspacing="2" cellpadding="2" class="testo">
				<tr>
                  <td width="131" class="backscuro">Titolo</td>
                  <td width="655" class="backchiaro"><input type="text" name="titolo_ita" class="input" size="50" value="<?php print $titolo_ita;?>"></td>
                </tr>
				<tr>
                  <td class="backscuro">Abstract</td>
                  <td class="backchiaro vedi_ris"><p>[Vista Sito]</p><textarea name="abstract_ita" class="testo_box_little" ><?php print html_entity_decode(str_replace("<br />", "", $abstract_ita))?></textarea></td>
			    </tr>
				<tr>
					<td  class="backscuro"></td>
					<td class="backchiaro">
					<?php $nome_form = "form"; $nome_campo = "abstract_ita"; include("include_editor.php");?>					</td>
				</tr>
				<tr>
                  <td class="backscuro">Descrizione</td>
                  <td class="backchiaro vedi_ris"><p>[Vista Sito]</p><textarea name="descrizione_ita" class="testo_box"><?php print html_entity_decode(str_replace("<br />", "", $descrizione_ita))?></textarea></td>
			    </tr>
				<tr>
					<td  class="backscuro"></td>
					<td class="backchiaro">
					<?php $nome_form = "form"; $nome_campo = "descrizione_ita"; include("include_editor.php");?>					</td>
				</tr>
             </table>
		</div>
		
		 <div id="tabs-4">
			  <table width="100%" border="0" cellspacing="2" cellpadding="2" class="testo">
				<tr>
                  <td width="131" class="backscuro">Titolo</td>
                  <td width="655" class="backchiaro"><input type="text" name="titolo_ted" class="input" size="50" value="<?php print $titolo_ted;?>"></td>
                </tr>
				<tr>
                  <td class="backscuro">Abstract</td>
                  <td class="backchiaro vedi_ris"><p>[Vista Sito]</p><textarea name="abstract_ted" class="testo_box_little" ><?php print html_entity_decode(str_replace("<br />", "", $abstract_ted))?></textarea></td>
			    </tr>
				<tr>
					<td  class="backscuro"></td>
					<td class="backchiaro">
					<?php $nome_form = "form"; $nome_campo = "abstract_ted"; include("include_editor.php");?>					</td>
				</tr>
				<tr>
                  <td class="backscuro">Descrizione</td>
                  <td class="backchiaro vedi_ris"><p>[Vista Sito]</p><textarea name="descrizione_ted" class="testo_box"><?php print html_entity_decode(str_replace("<br />", "", $descrizione_ted))?></textarea></td>
			    </tr>
				<tr>
					<td  class="backscuro"></td>
					<td class="backchiaro">
					<?php $nome_form = "form"; $nome_campo = "descrizione_ted"; include("include_editor.php");?>					</td>
				</tr>
             </table>
		 </div>
		
	 
	 </div>
		
			
	  <table width="100%" border="0" cellspacing="2" cellpadding="2" class="testo">
			<tr>
			  <td colspan="2"><font size="1">*campi obbligatori<br /><br />
              **compilare se si vuole puntare ad un link diretto. 
              </font></td>
			</tr>
            <tr>
              <td height="30" colspan="2" align="center"><input type="button" value="Salva la news" class="button" onClick="controlla_modulo();" /></td>
            </tr>
	   </table>
<script language="javascript">
<!--
	vistaCodice(); 
//-->
</script>