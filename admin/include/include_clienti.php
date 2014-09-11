<?php
//controllo se si sta facendo un inserimento
if(isset($_POST["save"]) and $_POST["save"]=="si")
{
	$nome = $_POST["nome"];
	$cognome = $_POST["cognome"];
	$email = $_POST["email"];
	$partitaiva = $_POST["partitaiva"];
	$azienda = $_POST["azienda"];
	$sito = $_POST["sito"];
	$telefono = $_POST["telefono"];
	$rivenditore = $_POST["rivenditore"];
			
	$object = new Clienti();
	
	$id = aggiusta_post($_GET["id"]);
	
	//controllo che la mail sia univoca
	$arrCl = $object->getAll(" and cl_email='".trim(aggiusta_post($email))."' and cl_id<>".$id);			
	$numCl = count($arrCl);
	if($numCl <> 0)	
	{
		$strPar = "errore=mail";
	}
	else
	{
		$object->update($id, $nome, $cognome, $email, $partitaiva, $azienda, $sito, $telefono, $rivenditore);
		$strPar = "ins=si";
		
		//se è stato confermata la richiesta di un rivenditore, mando la mail
		 if($_SESSION["tipo"]==2)
		 {
			 if($array["cl_lingua"] == "ita")
			 { require(ROOT_APATH."config/lingue/italiano.php"); }
			 else
			 { require(ROOT_APATH."config/lingue/inglese.php"); }
			 
			 if($array["cl_fkrivenditore"]==0 and $rivenditore<>0)
			 {
				$bodyMsg = file_get_contents('../tpl_mail/'.$array["cl_lingua"].'/conferma_rivenditore.html');
				$bodyMsg = str_replace("XX_NOME_XX", $nome . " ".$cognome, $bodyMsg);
				$bodyMsg = str_replace("XX_URL_XX", LINK_ROOT, $bodyMsg);
		
				$mailer2 = new PHPMailer();
				$mailer2->IsSMTP();
				$mailer2->Port       = 25;
				$mailer2->Host       = "localhost";
				$mailer2->IsMail(); 
				$mailer2->AddReplyTo(EMAIL_MITTENTE_GENERALE);
				$mailer2->From       = EMAIL_MITTENTE_GENERALE;
				$mailer2->FromName   = NOME_MITTENTE_GENERALE;
				$mailer2->AddAddress(trim($email));
				$mailer2->Subject  = 'CeramicPassion - '.$lang_listinoretailer;
				$mailer2->AltBody    = "To view the message, please use an HTML compatible email viewer!"; // optional, comment out and test
				$mailer2->WordWrap   = 80;
				$mailer2->MsgHTML($bodyMsg);
				$mailer2->IsHTML(true);
				$mailer2->Send();
			
				unset($mailer2);
			 }
			
		 }
	}
		
	print "<script language='Javascript'>window.location.replace('clienti_dettaglio.php?id=".$id."&".$strPar."');</script>";
	
}
	
	$nome = "";
	$cognome = "";
	$email = "";
	$partitaiva = "";
	$azienda = "";
	$sito = "";
	$telefono = "";
	$rivenditore = "";
	
	if(isset($_GET["id"]) and $_GET["id"] <> "")
	{		
		$id = aggiusta_post($_GET["id"]);
	
		$nome = $array["cl_nome"];
		$cognome = $array["cl_cognome"];
		$email = $array["cl_email"];
		$partitaiva = $array["cl_partitaIva"];
		$azienda = $array["cl_azienda"];
		$sito = $array["cl_sito"];
		$telefono = $array["cl_telefono"];
		$rivenditore = $array["cl_fkrivenditore"];
	}
	
?>			 
			
		<div id="tabs">
			<ul>
				<li><a href="#tabs-1">Cliente</a></li>
			</ul>	
		

		 <div id="tabs-1">
			  <table width="100%" border="0" cellspacing="2" cellpadding="2" class="testo">
				<tr>
                  <td class="backscuro">Nome*</td>
                  <td class="backchiaro"><input type="text" class="input" name="nome" value="<?php print $nome?>" /></td>
			    </tr>
				<tr>
                  <td class="backscuro">Cognome*</td>
                  <td class="backchiaro"><input type="text" class="input" name="cognome" value="<?php print $cognome?>" /></td>
			    </tr>
				<tr>
                  <td class="backscuro">E-mail</td>
                  <td class="backchiaro"><input type="text" class="input" name="email" value="<?php print $email?>" /></td>
			    </tr>				<tr>
                  <td class="backscuro">Telefono</td>
                  <td class="backchiaro"><input type="text" class="input" name="telefono" value="<?php print $telefono?>" /></td>
			    </tr>
				<tr>
                  <td class="backscuro">Partita Iva</td>
                  <td class="backchiaro"><input type="text" class="input" name="partitaiva" value="<?php print $partitaiva?>" /></td>
			    </tr> 
				<tr>
                  <td class="backscuro">Azienda</td>
                  <td class="backchiaro"><input type="text" class="input" name="azienda" value="<?php print $azienda?>" /></td>
			    </tr> 
				<tr>
                  <td class="backscuro">Sito</td>
                  <td class="backchiaro"><input type="text" class="input" name="sito" value="<?php print $sito?>" /></td>
			    </tr> 
				<?php
                if($_SESSION["tipo"]==2)
				{
					?>
                    <tr>
                      <td class="backscuro">Gruppo Rivenditore</td>
                      <td class="backchiaro">
                      <?php
                      $objRiv = new Rivenditori();
					  $arrRiv = $objRiv->getAll(" order by riv_gruppo");
					  
					  if(count($arrRiv)==0)
					  { print "<a href='rivenditori_nuovo.php'>Inserire un gruppo per i rivenditori</a>"; }
					  else
					  {
						?>
                        <select name="rivenditore" class="input">
                        	<option value="0"></option>
                        	
                            <?php
                            foreach($arrRiv as $riv)
							{ 
								print "<option value='".$riv["riv_id"]."'";
								($riv["riv_id"]==$rivenditore) ? print " selected" : "";
								print ">".$riv["riv_gruppo"]."</option>"; 
							}
							?>
                        
                        </select>
                        <?php	  
					  }
					  ?>
                      
                      
                      </td>
                    </tr>
                    <?php
				}
				else
				{
					print "<input type='hidden' name='rivenditore' value='0'>";	
				}
				?>
             </table>
		</div>
		 
	 
	 </div>
		
			
	  <table width="100%" border="0" cellspacing="2" cellpadding="2" class="testo">
			<tr>
			  <td height="30" colspan="2"><font size="1">*campi obbligatori</font></td>
			</tr>
                <tr>
                  <td height="30" colspan="2" align="center"><input type="button" value="Salva il cliente" class="button" onClick="controlla_modulo();" /></td>
                </tr>
	   </table>
