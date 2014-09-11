<?php
require_once("../init.php");


//recupero la funzione da richiamare
$funzione = $_REQUEST["funzione"];
$funzione();



function ajax_categoria()
{
	$macro = $_REQUEST["macro"];
	$suffisso = $_REQUEST["suffisso"];

	//query sulle categorie
	$objCat = new Categorie();
	$arrCat = $objCat->getAll(" and cat_fkclasse=".$macro." order by cat_sorting");
	$num = count($arrCat);		

	if($num == 0)
	{
		print "- - <input type='hidden' name='categoria".$suffisso."' value=''>";
	}
	else
	{
		print "<select name='categoria".$suffisso."' id='categoria".$suffisso."' class='select' onChange=\"ajaxSottocat('','".$suffisso."');  ajaxProdotto('','".$suffisso."'); \"><option value=''></option>";


		foreach($arrCat as $arr)
		{
			print "<option value='".$arr["cat_id"]."'";

			if(isset($_REQUEST["evidenza"]) && $_REQUEST["evidenza"]==$arr["cat_id"])
			{ print " selected"; }

			print ">" . $arr["cat_categoria"] . "</option>";

		}

		print "</select>";
	}

}

function ajax_sottocat()
{
	$macro = $_REQUEST["macro"];
	$suffisso = $_REQUEST["suffisso"];

	//query sulle sottocat
	
	$sottoCat = new Sottocategorie();
	$arrCat = $sottoCat->getAll(" and sc_fkcat=".$macro." order by sc_sorting");
	$num = count($arrCat);					
	
	if($num == 0)
	{
		print "- - <input type='hidden' name='sottocat".$suffisso."' id='sottocat".$suffisso."' value='0'> <script>ajaxProdotto('','".$suffisso."')</script>";
	}
	else
	{
		print "<select name='sottocat' id='sottocat".$suffisso."' class='select' ><option value=''></option>";
	
		foreach($arrCat as $arr)
		{
			print "<option value='".$arr["sc_id"]."'";
			
			if(isset($_REQUEST["evidenza"]) && $_REQUEST["evidenza"]==$arr["sc_id"])
			{ print " selected"; }
			
			print ">" . $arr["sc_sottocat"] . "</option>";
		}
		
		print "</select>";
	}
}


function ajax_prodotto() {

	$macro = $_REQUEST["macro"];
	$suffisso = $_REQUEST["suffisso"];
	$campo = $_REQUEST["campo"];

	//query sui prodotti
	$objPr = new Prodotti();
	$arrPr = $objPr->getAll($campo."=".$macro);

	if(count($arrPr) == 0)
	{
		print "- - <input type='hidden' name='prod".$suffisso."' id='prod".$suffisso."' value=''>";
	}
	else
	{
		print "<select name='prod".$suffisso."' id='prod".$suffisso."' class='select' onChange=\"ajaxColori('','".$suffisso."')\"><option value=''></option>";	

		foreach($arrPr as $prodotto)
		{
			print "<option value='".$prodotto["pr_id"]."'";

			if(isset($_REQUEST["evidenza"]) && $_REQUEST["evidenza"]==$prodotto["pr_id"])
			{ print " selected"; }

			print ">" . $prodotto["pr_titolo"] . "</option>";
		}

		print "</select>";

	}

}


function pubbProduct_tf(){

	$idP = $_POST["id"];
	$pubb = $_POST["pubb"];
	$campo = $_POST["campo"];
	
	$objProdotto = new Prodotti();
	$objProdotto->set_campo_byid($idP, $campo, $pubb);
	
}



function register_do(){
	
	$nome = $_POST["nome"];
	$cognome = $_POST["cognome"];
	$email = $_POST["email"];
	$telefono = $_POST["telefono"];
	$password = $_POST["password"];
	$sessionid = $_POST["sessionid"];	
	$reseller = $_POST["reseller"];	
	$vatnumber = $_POST["vatnumber"];	
	$azienda = $_POST["azienda"];	
	$sito = $_POST["sito"];	
	$newsletter = $_POST["newsletter"];	
	$dati_ind = $_POST["dati_ind"];	
	$lang = $_POST["lang"];
	
	
	if($lang=="eng")
	{ 
		$sLingua = "";	
		require("../config/lingue/inglese.php");
	}
	elseif($lang=="ted")
	{
		$sLingua = "_ted"; 
		require("../config/lingue/tedesco.php");
	}					
	else
	{
		$sLingua = "_ita"; 
		require("../config/lingue/italiano.php");
	}	

	$clienti = new Clienti();
	
	//controllo che la mail sia univoca
	$email = strtolower($email);
	
	$arrCl = $clienti->getAll(" and LOWER(cl_email)='".trim($email)."'");
	$num = count($arrCl);
	if($num > 0)
	{ print 0;}
	else
	{
		$idCl = $clienti->add($nome, $cognome, $email, $telefono, $password, $reseller, $vatnumber, $azienda, $sito,  $newsletter, $lang);
		
		//se ci sono, aggiungo anche i dati dell'indirizzo
		if($dati_ind=="t")
		{
			$via = $_POST["via"];
			$citta = $_POST["citta"];
			$cap = $_POST["cap"];
			$provincia = $_POST["provincia"];
			$fkprovincia = $_POST["fkprovincia"];
			$fknazione = $_POST["nazione"];
			
			$objNaz = new Nazioni();
			$arrNaz = $objNaz->getById($fknazione);
			
			if($fkprovincia<>"")
			{
				$objProv = new Province();
				$arrProv = $objProv->getById($fkprovincia);
				$provincia = $arrProv["provincia"];	
			}
			
			$objClIn = new Clienti_indirizzi();
			$objClIn->add($idCl, $nome, $cognome, $via, $citta, $cap, $fkprovincia, $provincia, $fknazione, $arrNaz["naz_nazione".$sLingua]);	
		}
		
		
		//se richiesta per rivenditore, invio mail anche a EMAIL_REGISTRAZIONE
		if($reseller == "t")
		{
			$testo = "L'utente ".$nome." " .$cognome." (".$email.") ha richiesto di essere registrato sul sito come Rivenditore.<br />Si ricorda che &egrave; necessario effettuare l'autorizzazione dall'area amministrativa. <br /><br /><a href='".LINK_ADMIN."'>link area amministrativa</a>";
			$soggetto = "Richiesta di registrazione come Rivenditore";			
		}
		else
		{
			$testo = "L'utente ".$nome." " .$cognome." (".$email.") ha richiesto di essere registrato sul sito come cliente privato.<br />";
			
			//creo la sessione SOLO se non si è fatta richiesta di essere rivenditori
			$_SESSION["idCliente"]=$idCl;
			
			//aggiorno il carrello
			$objCar = new Carrello();
			$objCar->updateUtente($sessionid, $_SESSION["idCliente"]);
			$objCar->updateSession($sessionid, $_SESSION["idCliente"]);
			
			$soggetto = "Richiesta di registrazione come cliente privato";
		}
		
		
		
		//invio mail di avviso
		$bodyMsg = file_get_contents('../tpl_mail/mail_avvisi.html');
		$bodyMsg = str_replace("XX_URL_XX", LINK_ROOT, $bodyMsg);
		$bodyMsg = str_replace("XX_TITOLO_XX", $soggetto, $bodyMsg);
		$bodyMsg = str_replace("XX_TESTO_XX", $testo, $bodyMsg);
		
		$mailer2 = new PHPMailer();
		$mailer2->IsSMTP();
		$mailer2->Port       = 25;
		$mailer2->Host       = "localhost";
		$mailer2->IsMail(); 
		$mailer2->AddReplyTo($mail);
		$mailer2->From       = EMAIL_REGISTRAZIONE;
		$mailer2->FromName   = NOME_MITTENTE_GENERALE;
		$mailer2->AddAddress(EMAIL_REGISTRAZIONE);
		//$mailer2->AddAddress("cvisca@webworking.it");
		$mailer2->Subject  = $soggetto;
		$mailer2->AltBody    = "To view the message, please use an HTML compatible email viewer!"; // optional, comment out and test
		$mailer2->WordWrap   = 80;
		$mailer2->MsgHTML($bodyMsg);
		$mailer2->IsHTML(true);
		$mailer2->Send();
		unset($mailer2);		
		
		
		//invio mail al cliente
		$txt_rivenditori = "";
		if($reseller == "t")
		{
			$txt_rivenditori = "<p>".$lang_avvisoretailer."</p>";	
		}		
		
		$bodyMsg = file_get_contents('../tpl_mail/'.$lang.'/registrazione_ok.html');
		$bodyMsg = str_replace("XX_URL_XX", LINK_ROOT, $bodyMsg);
		$bodyMsg = str_replace("XX_NOME_XX", $nome." ".$cognome, $bodyMsg);
		$bodyMsg = str_replace("XX_EMAIL_XX", $email, $bodyMsg);
		$bodyMsg = str_replace("XX_PWD_XX", $password, $bodyMsg);
		$bodyMsg = str_replace("XX_RIVENDITORI_XX", $txt_rivenditori, $bodyMsg);

		$mailer2 = new PHPMailer();
		$mailer2->IsSMTP();
		$mailer2->Port       = 25;
		$mailer2->Host       = "localhost";
		$mailer2->CharSet = "UTF-8";
		$mailer2->IsMail(); 
		$mailer2->AddReplyTo(EMAIL_SITO);
		$mailer2->From       = EMAIL_SITO;
		$mailer2->FromName   = NOME_MITTENTE_GENERALE;
		$mailer2->AddAddress(trim($email));
		//$mailer2->Subject  = "Ceramic Passion - ".$lang_conferma_iscrizione;
		$mailer2->Subject  =  "=?UTF-8?B?".base64_encode("Ceramic Passion - ".$lang_conferma_iscrizione)."?=";  
		$mailer2->AltBody    = "To view the message, please use an HTML compatible email viewer!"; // optional, comment out and test
		$mailer2->WordWrap   = 80;
		$mailer2->MsgHTML($bodyMsg);
		$mailer2->IsHTML(true);
		$mailer2->Send();
		unset($mailer2);
		
		
		print 1;
	}
}

function login(){
	$email = $_POST["username"];
	$password = $_POST["password"];
	$sessionid = $_POST["sessionid"];	

	if(trim($email)<>"" and $password<>"")
	{
		$clienti = new Clienti();
		$arrCl = $clienti->getAll(" and cl_email='".$email."' and cl_password='".md5(sha1(trim($password)))."'");
		
		if(count($arrCl)==0)
		{ print 0; }
		else
		{ 
			$_SESSION["idCliente"] = $arrCl[0]["cl_id"];
			
			//se è un rivenditore, salvo il gruppo 
			if($arrCl[0]["cl_fkrivenditore"]<>0)
			{
				$_SESSION["rivenditore"] = $arrCl[0]["cl_fkrivenditore"];
			}
			
			//aggiorno il carrello
			$objCar = new Carrello();
			$objCar->updateUtente($sessionid, $_SESSION["idCliente"]);
			$objCar->updateSession($sessionid, $_SESSION["idCliente"]);

			print 1; 
		}
	}
	else
	{ print 0; }
}


function send_to_friend() {
	
	$db = new Db();	
	
	$idPr = $db->str($_POST["idPr"]);
	$nome = $db->str($_POST["nome"]);
	$mail = $db->str($_POST["mail"]);
	$nome_amico = $db->str($_POST["nome_amico"]);
	$mail_amico = $db->str($_POST["mail_amico"]); 
	$messaggio = $db->str($_POST["messaggio"]);  
	$lang = $_POST["lang"];
	
	
	if($lang=="eng")
	{ 
		$suffissoL = "";	
		require("../config/lingue/inglese.php");
	}
	elseif($lang=="ted")
	{
		$suffissoL = "_ted"; 
		require("../config/lingue/tedesco.php");
	}					
	else
	{
		$suffissoL = "_ita"; 
		require("../config/lingue/italiano.php");
	}	
	
	
	//recupero la foto
	$img = "";
	
	$immagini = new File("pr_prodotti","img","anteprima");
	$idTbl = $immagini->get_id_tabella();

	$arrFoto = $immagini->get_file($idPr);
	$numTot = count($arrFoto);			 

	if($numTot <> 0)
	{
		$cartellaUp = $immagini->get_cartella();
		$arrayFoto = explode(":",$arrFoto[0]["file"]);
		$nomeFileImg = dimminomefile($arrayFoto[1]);
		$tipoFile = tipo_file($arrayFoto[1]);

		$nomeThumb = $nomeFileImg . "_thumb." . $tipoFile;
		$img_thumb = UPLOAD_FILE."/".$cartellaUp."/".$nomeThumb;
		
		if(is_file($img_thumb))
		{ $img .= "<img src='".LINK_ROOT.$img_thumb."' width='180'>"; }
	}
	
	
	//dett prodotto
	$objProdotto = new Prodotti();
	$arrPr = $objProdotto->getById($idPr);
	
	$dettPr = "";
	
	if($arrPr["pr_titolo"]<>"")
	{ $dettPr .= "<strong>".$arrPr["pr_titolo".$suffissoL]."</strong><br />"; }
	
	$dettPr .= $arrPr["pr_desc".$suffissoL];
	
	//mando mail
	$bodyMsg = file_get_contents('../tpl_mail/'.$lang.'/send_friend.html');
	$bodyMsg = str_replace("XX_URL_XX", LINK_ROOT, $bodyMsg);
	$bodyMsg = str_replace("XX_NOMEAMICO_XX", $nome_amico, $bodyMsg);
	$bodyMsg = str_replace("XX_NOME_XX", $nome, $bodyMsg);
	$bodyMsg = str_replace("XX_MAIL_XX", $mail, $bodyMsg);
	$bodyMsg = str_replace("XX_MESSAGGIO_XX", $messaggio, $bodyMsg);
	$bodyMsg = str_replace("XX_IMG_XX", $img, $bodyMsg);
	$bodyMsg = str_replace("XX_PRODOTTO_XX", $dettPr, $bodyMsg);
	$bodyMsg = str_replace("XX_LINK_XX", LINK_ROOT."product_dett.php?idPr=".$idPr, $bodyMsg);
	
	$mailer2 = new PHPMailer();
	$mailer2->IsSMTP();
	$mailer2->Port       = 25;
	$mailer2->Host       = "localhost";
	$mailer2->CharSet = "UTF-8";
	$mailer2->IsMail(); 
	$mailer2->AddReplyTo($mail);
	$mailer2->From       = $mail;
	$mailer2->FromName   = $nome;
	$mailer2->AddAddress(trim($mail_amico));
	//$mailer2->Subject  = $nome." ".utf8_decode($lang_consigliaprodotto); 
	$mailer2->Subject  =  "=?UTF-8?B?".base64_encode($nome." ".$lang_consigliaprodotto)."?=";  
	$mailer2->AltBody    = "To view the message, please use an HTML compatible email viewer!"; // optional, comment out and test
	$mailer2->WordWrap   = 80;
	$mailer2->MsgHTML($bodyMsg);
	$mailer2->IsHTML(true);
	if(!$mailer2->Send()){
			$error = $mailer2->ErrorInfo."\n";
			print 0;
	}	
	else
	{ print 1; }
	unset($mailer2);
	
}



function modifica_sorting(){
	
	$id = $_REQUEST["id"];
	$azione = $_REQUEST["azione"];
	$campoId = $_REQUEST["campoId"];
	$tbl = $_REQUEST["tabella"];
	$campoSorting = $_REQUEST["campoSor"];
	$condizioni = $_REQUEST["condizioni"];
	
	$sort = new Sorting($tbl, $campoSorting, $condizioni);
	$sort->cambia_ordinamento_campi($id, $azione, $campoId);
}


function delete_detail() {
	
	$idFile = $_POST["id"];
	$tbl = $_POST["tbl"];
	$tipo = $_POST["tipo"];
	$campo = $_POST["campo"];
	
	$rif_file = new File($tbl, $tipo, $campo);	
	$rif_file->delete_singlerecord($idFile);
}





function set_stato_ordine()
{
	$id = $_GET["idOrdine"];
	$stato = $_GET["stato"];
	
	$objOrd = new Ordini();
	$objOrd->set_campo_byid($id, "or_stato", $stato);
	print "ok";
}


function coupon_check()
{
	$codice = $_POST["codice"];
	
	$objCoupon = new Coupon();
	$sconto = $objCoupon->check_coupon($codice);
	
	print $sconto;
}


function saveIndirizzo()
{
	$idCliente = $_POST["idCl"];
	$nome = $_POST["nome"];
	$cognome = $_POST["cognome"];
	$via = $_POST["via"];
	$citta = $_POST["citta"];
	$cap = $_POST["cap"];
	$provincia = $_POST["provincia"];
	$fkprovincia = $_POST["fkprovincia"];
	$fknazione = $_POST["nazione"];
	$suffissoL = $_POST["lingua"];
	$telefono = $_POST["telefono"];
	
	//devo recuperare il nome esteso del paese 
	$objSped = new Nazioni();
	$naz = $objSped->getById($fknazione);
	$nazione = $naz["naz_nazione".$suffissoL];
	
	if($fkprovincia<>"")
	{
		$objProv = new Province();
		$arrProv = $objProv->getById($fkprovincia);
		$provincia = $arrProv["provincia"];	
	}	

	$objIndirizzi = new Clienti_indirizzi();
	$objIndirizzi->add($idCliente, $nome, $cognome, $telefono, $via, $citta, $cap, $fkprovincia, $provincia, $fknazione, $nazione);
	
	print "ok";
}


function removeIndirizzo()
{
	$idInd = $_POST["idInd"];	
	$idCl = $_POST["idCl"];
	
	$objClInd = new Clienti_indirizzi();
	$itemClInd = $objClInd->getById($idInd);
	if($itemClInd["cli_fkcliente"]==$idCl) //check su corrispondenza utente
	{ $objClInd->delete($idInd); }
	
}




/*
function updateCart()
{
	$idC = $_POST["idC"];
	$qta = $_POST["qta"];
	
	$objCarrello = new Carrello();
	$objCarrello->updateQta($idC, $qta);
}

function removeCart()
{
	$idC = $_POST["idC"];
	
	$objCarrello = new Carrello();
	$objCarrello->delete($idC);
}
*/

function upload()
{
	$uploaddir = '../upload/tmp/'; 
	$file = $uploaddir . basename($_FILES['uploadfile']['name']); 
	 
	if (move_uploaded_file($_FILES['uploadfile']['tmp_name'], $file)) { 
	  //inserisco il timestamp all'immagine
	  $filename = dimminomefile(basename($_FILES['uploadfile']['name']));
	  $tipo_file = tipo_file(basename($_FILES['uploadfile']['name']));
	  
	  $file_rinominato = $filename."_".strtotime(date('Y-m-d H:i:s')).".".$tipo_file;
	  rename($file, $uploaddir.$file_rinominato);
	  
	  echo "success:".$file_rinominato; 
	} else {
		echo "error";
	}
	
}

?>