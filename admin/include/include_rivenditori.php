<?php
//controllo se si sta facendo un inserimento
if(isset($_POST["save"]) and $_POST["save"]=="si")
{
	$nome = $_POST["nome"];
	$fklistino = $_POST["listino"];		
			
	$object = new Rivenditori();
	
	if(isset($_POST["insert"]) and $_POST["insert"] == "si")
	{
		$object->add($nome, $fklistino);
		//recupero l'ultimo id inserito
		$db = new Db();
		$id = $db->get_last_id();
	}
	elseif(isset($_POST["update"]) and $_POST["update"] == "si") 
	{
		$id = aggiusta_post($_GET["id"]);
		$object->update($id, $nome, $fklistino);		
	}
		
		
	print "<script language='Javascript'>window.location.replace('rivenditori_dettaglio.php?id=".$id."&ins=si');</script>";
	
}
	
	$nome = "";
	$listino = "";
	
	if(isset($_GET["id"]) and $_GET["id"] <> "")
	{		
		$id = aggiusta_post($_GET["id"]);
	
		$nome = $array["riv_gruppo"];
		$listino = $array["riv_fklistino"];
	}
	
?>			 
			
		<div id="tabs">
			<ul>
				<li><a href="#tabs-1">Rivenditore</a></li>
			</ul>	
		

		 <div id="tabs-1">
			  <table width="100%" border="0" cellspacing="2" cellpadding="2" class="testo">
				<tr>
                  <td class="backscuro">Nome*</td>
                  <td class="backchiaro"><input type="text" class="input" name="nome" value="<?php print $nome?>" /></td>
			    </tr>
				<tr>
                  <td class="backscuro">Listino*</td>
                  <td class="backchiaro">
                  <?php
                  //recupero i listini
				  $objListini = new Listini();
				  $arrListini = $objListini->getAll(" order by list_titolo");
				  
				  if(count($arrListini)==0)
				  { print "<a href='listini_nuovo.php'>inserire un listino da associare al rivenditore</a>";}
				  else
				  {
					 print "<select name='listino'><option value=''></option>";	 
					 
					 foreach($arrListini as $list)
					 {
						print "<option value='".$list["list_id"]."'";
						($list["list_id"]==$listino) ? print " selected" : "";
						print ">".$list["list_titolo"]."</option>";	 
				     }
					 
					 print "</select>"; 
				  }
				  
				  ?>
                 </td>
			    </tr>
             </table>
		</div>
		
	 
	 
	 </div>
		
			
	  <table width="100%" border="0" cellspacing="2" cellpadding="2" class="testo">
			<tr>
			  <td height="30" colspan="2"><font size="1">*campi obbligatori</font></td>
			</tr>
                <tr>
                  <td height="30" colspan="2" align="center"><input type="button" value="Salva il rivenditore" class="button" onClick="controlla_modulo();" /></td>
                </tr>
	   </table>
