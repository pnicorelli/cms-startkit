<?php
//controllo se si sta facendo un inserimento
if(isset($_POST["save"]) and $_POST["save"]=="si")
{
	$domanda = $_POST["domanda"];
	$risposta = $_POST["risposta"];
	$domanda_ita = $_POST["domanda_ita"];
	$risposta_ita = $_POST["risposta_ita"];
	$domanda_ted = $_POST["domanda_ted"];
	$risposta_ted = $_POST["risposta_ted"];
			
	$faq = new Faq();
	
	if(isset($_POST["insert"]) and $_POST["insert"] == "si")
	{
		$faq->add($domanda, $risposta, $domanda_ita, $risposta_ita, $domanda_ted, $risposta_ted);
		//recupero l'ultimo id inserito
		$db = new Db();
		$id = $db->get_last_id();
	}
	elseif(isset($_POST["update"]) and $_POST["update"] == "si") 
	{
		$id = aggiusta_post($_GET["id"]);
		$faq->update($id, $domanda, $risposta, $domanda_ita, $risposta_ita, $domanda_ted, $risposta_ted);
	}
		
		
	print "<script language='Javascript'>window.location.replace('faq_dettaglio.php?id=".$id."&ins=si');</script>";
	
}
	
	$domanda = "";
	$risposta = "";
	$domanda_ita = "";
	$risposta_ita = "";
	$domanda_ted = "";
	$risposta_ted = "";
	
	if(isset($_GET["id"]) and $_GET["id"] <> "")
	{		
		$id = aggiusta_post($_GET["id"]);
	
		$domanda = $array["faq_domanda"];
		$risposta = $array["faq_risposta"];
		$domanda_ita = $array["faq_domanda_ita"];
		$risposta_ita = $array["faq_risposta_ita"];
		$domanda_ted = $array["faq_domanda_ted"];
		$risposta_ted = $array["faq_risposta_ted"];
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
                  <td class="backscuro">Domanda*</td>
                  <td class="backchiaro vedi_ris"><p>[Vista Sito]</p><textarea name="domanda" class="testo_box_little" ><?php print html_entity_decode(str_replace("<br />", "", $domanda))?></textarea></td>
			    </tr>
				<tr>
					<td  class="backscuro"></td>
					<td class="backchiaro">
					<?php $nome_form = "form"; $nome_campo = "domanda"; include("include_editor.php");?>					</td>
				</tr>
				<tr>
                  <td class="backscuro">Risposta*</td>
                  <td class="backchiaro vedi_ris"><p>[Vista Sito]</p><textarea name="risposta" class="testo_box"><?php print html_entity_decode(str_replace("<br />", "", $risposta))?></textarea></td>
			    </tr>
				<tr>
					<td  class="backscuro"></td>
					<td class="backchiaro">
					<?php $nome_form = "form"; $nome_campo = "risposta"; include("include_editor.php");?>					</td>
				</tr>
             </table>
		</div>
		 <div id="tabs-2">
			  <table width="100%" border="0" cellspacing="2" cellpadding="2" class="testo">
				<tr>
                  <td class="backscuro">Domanda</td>
                  <td class="backchiaro vedi_ris"><p>[Vista Sito]</p><textarea name="domanda_ita" class="testo_box_little" ><?php print html_entity_decode(str_replace("<br />", "", $domanda_ita))?></textarea></td>
			    </tr>
				<tr>
					<td  class="backscuro"></td>
					<td class="backchiaro">
					<?php $nome_form = "form"; $nome_campo = "domanda_ita"; include("include_editor.php");?>					</td>
				</tr>
				<tr>
                  <td class="backscuro">Risposta</td>
                  <td class="backchiaro vedi_ris"><p>[Vista Sito]</p><textarea name="risposta_ita" class="testo_box"><?php print html_entity_decode(str_replace("<br />", "", $risposta_ita))?></textarea></td>
			    </tr>
				<tr>
					<td  class="backscuro"></td>
					<td class="backchiaro">
					<?php $nome_form = "form"; $nome_campo = "risposta_ita"; include("include_editor.php");?>					</td>
				</tr>
             </table>
		</div>
		
		 <div id="tabs-3">
			  <table width="100%" border="0" cellspacing="2" cellpadding="2" class="testo">
				<tr>
                  <td class="backscuro">Domanda</td>
                  <td class="backchiaro vedi_ris"><p>[Vista Sito]</p><textarea name="domanda_ted" class="testo_box_little" ><?php print html_entity_decode(str_replace("<br />", "", $domanda_ted))?></textarea></td>
			    </tr>
				<tr>
					<td  class="backscuro"></td>
					<td class="backchiaro">
					<?php $nome_form = "form"; $nome_campo = "domanda_ted"; include("include_editor.php");?>					</td>
				</tr>
				<tr>
                  <td class="backscuro">Risposta</td>
                  <td class="backchiaro vedi_ris"><p>[Vista Sito]</p><textarea name="risposta_ted" class="testo_box"><?php print html_entity_decode(str_replace("<br />", "", $risposta_ted))?></textarea></td>
			    </tr>
				<tr>
					<td  class="backscuro"></td>
					<td class="backchiaro">
					<?php $nome_form = "form"; $nome_campo = "risposta_ted"; include("include_editor.php");?>					</td>
				</tr>
             </table>
		 </div>
		
	 
	 </div>
		
			
	  <table width="100%" border="0" cellspacing="2" cellpadding="2" class="testo">
			<tr>
			  <td height="30" colspan="2"><font size="1">*campi obbligatori</font></td>
			</tr>
                <tr>
                  <td height="30" colspan="2" align="center"><input type="button" value="Salva la faq" class="button" onClick="controlla_modulo();" /></td>
                </tr>
	   </table>
<script language="javascript">
<!--
	vistaCodice(); 
//-->
</script>