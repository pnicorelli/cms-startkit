<?php
/******************************************************************************************************************************
*************************************************FUNZIONI SPECIFICHE **********************************************************
*******************************************************************************************************************************/


//funzione che genera una nuova pwd utente
function nuovapassword($cognome)
{
	$pass = md5($cognome . rand() . "ALDOIGJISGJSFSFJSOIGJF4543643tGgjaksjfl245436t3kl" . date("Y-m-t H:i:s"));
	$pass = substr($pass, 5, 10);
	
	return $pass;
}


/************************************************FINE SPECIFICHE***************************************************************/



/*- - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - */





/******************************************************************************************************************************
*************************************************GESTIONI FILE IN UPLOAD*******************************************************
*******************************************************************************************************************************/

function dimmi_etichetta_tabella($idTabella)
{
	$queryEt = "select etichetta from gestione_tabelle where idGT = " . $idTabella;
	$resEt = makequery($queryEt);
	$arrEt = makefetch($resEt);
	
	return $arrEt["etichetta"];
}

function dimmi_nome_campo($idTabella)
{
	$queryEt = "select campo from gestione_tabelle where idGT = " . $idTabella;
	$resEt = makequery($queryEt);
	$arrEt = makefetch($resEt);
	
	return $arrEt["campo"];
}

function dimmi_campi_tabella($tabella)
{
	$strCampi = "";

	$query = "select * from gestione_tabelle where tabella = '".$tabella."'";
	$res = makequery($query);
	while($arr = makefetch($res))
	{
		($strCampi == "") ? $strCampi = $arr["campo"] : $strCampi .= ":" . $arr["campo"];
	}
	
	return $strCampi;
}

function dimmi_tipo_tabella($idTabella)
{
	$queryTipo = "select tipo from gestione_tabelle where idGT = " . $idTabella;
	$resTipo = makequery($queryTipo);
	$arrTipo = makefetch($resTipo);
	
	return $arrTipo["tipo"];
}


function dimmi_dimensioni_img($idTabella)
{
	$queryDim = "select width_big, height_big, width_thumb, height_thumb from gestione_tabelle where idGT = " . $idTabella;
	$resDim = makequery($queryDim);
	$arrDim = makefetch($resDim);
	
	$arrayDimensioni = array($arrDim["width_big"], $arrDim["height_big"], $arrDim["width_thumb"], $arrDim["height_thumb"]);
	
	return $arrayDimensioni;
}




//funzione che scrive i campi di tipo file, vuoti
function scrivi_campi_vuoti($idTabella)
{
	$quantiFile = dimmi_quanti_file($idTabella);
	$etichettaTbl = dimmi_etichetta_tabella($idTabella);
	$nomeCampo = dimmi_nome_campo($idTabella);
	
	$tipo = dimmi_tipo_tabella($idTabella);
	$nomeFile = $nomeCampo;
	//($tipo == "file") ? $nomeFile = "file" : $nomeFile = "immagine";
	
	$return_tbl = "";
	
	if($tipo == "file")
	{
		for($i=1; $i<=$quantiFile; $i++)
		{
			$return_tbl .= "<tr><td class=\"backscuro\">".$etichettaTbl;
			
			($quantiFile > 1) ? $return_tbl .= " " . $i : "";
			
			$return_tbl .= "</td><td class=\"backchiaro\">etichetta: <input type='text' name='et_".$nomeFile."_".$i."' class='input'> &nbsp;&nbsp;&nbsp;file: <input type='file' name='".$nomeFile."_".$i."' class='input'></td></tr>";
		}
	}
	return $return_tbl;
}


//funzione che scrive i campi nel dettaglio di un record
function scrivi_campi_dett($idTabella, $idRecord, $percorso, $tmp)
{
	
	$tipo = dimmi_tipo_tabella($idTabella);
	$etichettaTbl = dimmi_etichetta_tabella($idTabella);
	$nomeCampo = dimmi_nome_campo($idTabella);
	
	//($tipo == "file") ? $nomeFile = "file" : $nomeFile = "immagine";
	$nomeFile = $nomeCampo;
	
	//recupero i file già inseriti
	$queryFile = "select idF, file, titoloF from file where fk_tabella = " . $idTabella . " and fk_record = " . $idRecord . " and tipoF = '".$tipo."' order by sorting";
	$resFile = makequery($queryFile);
	$arrFile = array();

	while($arr = makefetch($resFile))
	{

		$arrFile[] = array("idF"=>$arr["idF"], "file"=>$arr["file"], "etichetta"=>$arr["titoloF"]);
	}
	
	$quantiFile = dimmi_quanti_file($idTabella);

	$cartella_upload = dimmi_cartella_upload($idTabella);

	$return_tbl = "";
	for($i=1; $i<=$quantiFile; $i++)
	{
		if($tipo=="file")
		{
			(isset($arrFile[($i-1)])) ? $valueEtichetta = $arrFile[$i-1]["etichetta"] : $valueEtichetta="";
		
			$return_tbl .= "<tr><td class=\"backscuro\">".$etichettaTbl;
			
			($quantiFile > 1) ? $return_tbl .= " " . $i : "";
			
			$return_tbl .= "</td><td class=\"backchiaro\"><!-- etichetta: <input type='text' name='et_".$nomeFile."_".$i."' class='input' value='".$valueEtichetta."'> &nbsp;&nbsp;&nbsp;-->file: <input type='file' name='".$nomeFile."_".$i."' class='input'><br /><font size=1>file attuale: ";
			
			if(isset($arrFile[($i-1)]) and $arrFile[$i-1] <> "")
			{
				$return_tbl .= "<a href='".$percorso."/".$arrFile[$i-1]["file"] ."' target='_blank'>" . $arrFile[$i-1]["file"] . "</a><br />";
				$return_tbl .= "<input type='checkbox' value='si' name='canc_".$nomeCampo."_".$i."'> cancella il file attuale";
				$return_tbl .= "<input type='hidden' name='idFoto_".$nomeCampo."_".$i."' value='".$arrFile[$i-1]["idF"]."'>";
				if($nomeCampo=="video"){
						$return_tbl .= '<div id="video_preview"><h2>Preview</h2>';
						$return_tbl .= '<video  controls>';
						$return_tbl .= '  <source src="'.$percorso."/".$arrFile[$i-1]["file"] .'" type="video/mp4">';
						$return_tbl .= 'Your browser does not support the video tag.';
						$return_tbl .= '</video>';
						$return_tbl .= '</div>';
					
				}
			}
			else
				$return_tbl .= " nessuno";
		
			$return_tbl .= "</font></td></tr>";
		}
		else  //immagine
		{
			$percorsoFile = "../".UPLOAD_FILE."/".$cartella_upload."/";
		
			$return_tbl .= "<tr><td class=\"backscuro\">".$etichettaTbl;
			
			($quantiFile>1) ? $return_tbl.= " ".$i : "";
			
			$return_tbl .= "</td><td class=\"backchiaro\"><div class='tbl".$idTabella."'><font size=1>";
			
			if(isset($arrFile[($i-1)]) and $arrFile[$i-1] <> "")
			{
				$arr_img = explode(":", $arrFile[($i-1)]["file"]);
				$nomeFileImg = dimminomefile($arr_img[1]);
				$tipoFile = tipo_file($arr_img[1]);
				$nomeThumb = $nomeFileImg . "_thumb." . $tipoFile;
			
				//$return_tbl .= "<a href='".$percorsoFile.$arr_img[1]."' target='_blank'><img src='".$percorsoFile.$nomeThumb."' border=0></a>";
				$return_tbl .= "<img src='".$percorsoFile.$nomeThumb."' border=0>";
				$return_tbl .= "<br /><input type='checkbox' value='si' name='cancFoto_".$nomeCampo."_".$i."'> cancella la foto attuale";
				$return_tbl .= "<input type='hidden' name='idFoto_".$nomeCampo."_".$i."' value='".$arrFile[$i-1]["idF"]."'>";
			}
			else
			{
				$return_tbl .= "<a href=\"#\" onClick=\"apri_upload(".$idRecord.", '".$idTabella."-".$tmp."');\">carica un'immagine</a>";
			}
			
			print "</font></div></td></tr>";
		}
		
	}

	return $return_tbl;
}



//funzione che salva i file
function upload_file($tabella, $filePag, $idR)
{
	$campiTbl = dimmi_campi_tabella($tabella);
	$arrayCampi = explode(":", $campiTbl);

	foreach($arrayCampi as $campoTbl)
	{
		$idTabellaFile = dimmi_id_tabella($tabella, $campoTbl);
	
		//percorso per i file
		$cartella_upload = dimmi_cartella_upload($idTabellaFile);
		$percorso = "../".UPLOAD_FILE."/".$cartella_upload."/";
	
		//controllo l'upload dei documenti
		$quantiFile = dimmi_quanti_file($idTabellaFile);
	
		$cont = 0;
		for($i=1; $i<=$quantiFile; $i++)
		{	
			$nomeCampoFile = $campoTbl."_".$i;
			$nomeEtichettaFile = "et_".$campoTbl."_".$i;	
			
			if(isset($_FILES[$nomeCampoFile]) and $_FILES[$nomeCampoFile]["size"]>0)
			{
				if (!move_uploaded_file($_FILES[$nomeCampoFile]["tmp_name"],"$percorso/".$_FILES[$nomeCampoFile]["name"]))
				{
					header("Location: ".$filePag);
					exit();		
				}
				
				//inserisco nella tbl dei file
				$queryFile = "insert into file (file, fk_tabella, fk_record, tipoF, titoloF, sorting) values ('".$_FILES[$nomeCampoFile]["name"]."', ".$idTabellaFile.", ".$idR.", 'file', '".aggiusta_post($_POST[$nomeEtichettaFile])."', ".++$cont.")";
				makequery($queryFile);
			}
		}
	}

}


//funzione per la modifica dei file
function update_file($tabella, $filePag, $id)
{
	$campiTbl = dimmi_campi_tabella($tabella);
	$arrayCampi = explode(":", $campiTbl);
	
	foreach($arrayCampi as $campoTbl)
	{
		$idTabellaFile = dimmi_id_tabella($tabella, $campoTbl);
		$quantiFile = dimmi_quanti_file($idTabellaFile);
		$tipo = dimmi_tipo_tabella($idTabellaFile);
	
		//percorso per i file
		$cartella_upload = dimmi_cartella_upload($idTabellaFile);
		$percorso = "../".UPLOAD_FILE."/".$cartella_upload."/";
		
		if($tipo == "file")
		{
			//controllo l'upload dei documenti
			//recupero i file già inseriti
			$queryFile = "select * from file where fk_tabella = " . $idTabellaFile . " and fk_record = " . $id . " and tipoF = 'file' order by sorting";
			$resFile = makequery($queryFile);
			
			$arrayFile = array();
			while($arrF = makefetch($resFile))
			{
				$arrayFile[] = array("idF"=>$arrF["idF"], "file"=>$arrF["file"], "etichetta"=>$arrF["titoloF"]);	
			}
			
			$arr_file = array();
			$arr_etichette = array();
		
			$cont = 0;
			for($i=1; $i<=$quantiFile; $i++)
			{
				$nomeCampoFile = $campoTbl."_".$i;
				$nomeCampoCanc = "canc_".$campoTbl."_".$i;
				$nomeCampoEtichetta = "et_".$campoTbl."_".$i;
				
				if(isset($_FILES[$nomeCampoFile]) and $_FILES[$nomeCampoFile]["size"]>0)
				{
					if (!move_uploaded_file($_FILES[$nomeCampoFile]["tmp_name"],"$percorso/".$_FILES[$nomeCampoFile]["name"]))
					{
						header("Location: ".$filePag);
						exit();		
					}
					
					$arr_file[$i] = $_FILES[$nomeCampoFile]["name"];
					$arr_etichette[$i] = @aggiusta_post($_POST[$nomeCampoEtichetta]);
					
					//controllo se devo cancellare il file vecchio	
					if(isset($arrayFile[$i-1]) and $arrayFile[$i-1]["file"] <> "" and $arrayFile[$i-1]["file"] <> $_FILES[$nomeCampoFile]["name"])
					{
						elimina_unico($arrayFile[$i-1]["file"],$idTabellaFile,$percorso,$arrayFile[$i-1]["idF"],"file");
					}
				
				}
				else //non c'è il file da caricare, controllo se si è scelto di cancellare il file vecchio
				{
					if(isset($_POST[$nomeCampoCanc]) and $_POST[$nomeCampoCanc] == 'si')
					{
						if(trim($arrayFile[$i-1]["file"] <> ""))
						{
							elimina_unico($arrayFile[$i-1]["file"],$idTabellaFile,$percorso,$arrayFile[$i-1]["idF"],"file");				
						}
					
						$arr_file[$i] = "";
						$arr_etichette[$i] = "";
					}
					else
					{
						if(isset($arrayFile[$i-1]))
						{
							$arr_file[$i] = $arrayFile[$i-1]["file"];
							$arr_etichette[$i] = aggiusta_post($_POST[$nomeCampoEtichetta]);
						}
						else
						{
							$arr_file[$i] = "";
							$arr_etichette[$i] = "";
						}
					}
				}
				
			}
		
			//****gestione tabella gestisci upload****
			//prima cancello i dati attualmente inseriti e poi inserisco i nuovi
			$query_del = "delete from file where fk_tabella = " . $idTabellaFile . " and tipoF = 'file' and fk_record = " . $id;
			makequery($query_del);
			
			$cont = 1;
			
			foreach($arr_file as $key=>$value)
			{
				if(trim($value != ""))
				{
					//inserisco nel db
					$query_ins = "insert into file (file, fk_tabella, fk_record, tipoF, titoloF, sorting) value('".$value."', ".$idTabellaFile.", ".$id.", 'file', '".$arr_etichette[$key]."', ".$cont.")";
					makequery($query_ins);
					$cont++;
				}
			}
			//****fine gestione tabella gestisci upload****
		
		}
		else
		{
			for($k=1; $k<=$quantiFile; $k++)
			{
				$nomeCampoCanc = "cancFoto_".$campoTbl."_".$k;
				$nomeCampoIdImg = "idFoto_".$campoTbl."_".$k;
							
				if(isset($_POST[$nomeCampoIdImg]) and $_POST[$nomeCampoIdImg] <> "") 
				{
					if(isset($_POST[$nomeCampoCanc]) and $_POST[$nomeCampoCanc] == "si")
					{
						//recupero prima l'img, poi controllo se posso cancellarla dal file system. E infine cancello dal db
						$queryImg = "select * from file where idF = " . $_POST[$nomeCampoIdImg];
						$resImg = makequery($queryImg);
						$arrImg = makefetch($resImg);
						
						//aggiorno il sorting degli altri file			
						$querySorting = "update file set sorting = (sorting-1) where fk_tabella=".$idTabellaFile." and fk_record=".$id." and tipoF='img' and sorting > " . $arrImg["sorting"];
						makequery($querySorting);
						
						
						$arrayImmagine = explode(":", $arrImg["file"]);
						
						elimina_unicoMd5($arrayImmagine[1],$idTabellaFile,$percorso,$_POST[$nomeCampoIdImg],"img");
						 
						$queryDel = "delete from file where idF = " . $_POST[$nomeCampoIdImg];
						makequery($queryDel);
					} 
				}
			}
		}
	}
}


//funzione che mi dice quanti campi di tipo file possono esserci
function dimmi_quanti_file($idTabella)
{
	$queryN = "select n_file from gestione_tabelle where idGT = " . $idTabella;
	$resN = makequery($queryN);
	$arrN = makefetch($resN);
	
	return $arrN["n_file"];
}


//funzione che, dato il nome di una tbl, restituisce il corrispettivo id in gestione tabelle
function dimmi_id_tabella($nomeTabella, $campo)
{
	$queryTbl = "select idGT from gestione_tabelle where tabella = '" .$nomeTabella."' and campo = '".$campo."'";
	$resTbl = makequery($queryTbl);
	$arrTbl = makefetch($resTbl);
	
	return $arrTbl["idGT"];
}


function visibile_default($idTabella)
{
	return dimmi_valore("gestione_tabelle", "visibile_default", "idGT=".$idTabella);
}


function elimina_unico($file,$idTabella,$percorso,$idFile,$tipo)
{
	$query_c = "select * from file where file = '".$file."' and fk_tabella = ".$idTabella." and tipoF = '".$tipo."' and idF <> ".$idFile;	
	$res_c = makequery($query_c)or die("query funzione cancellazione univoca fallita");
	$num_c = mysqli_num_rows($res_c);
	
	if($num_c == "0")  //posso cancellare
	{
		$filename = $percorso."/".$file;
		if(file_exists($filename))
			unlink($filename);
	}
}


function elimina_unicoMd5($file,$idTabella,$percorso,$idFile,$tipo)
{
	$query_c = "select * from file where file LIKE '%".$file."%' and fk_tabella = ".$idTabella." and tipoF = '".$tipo."' and idF <> ".$idFile;	
	$res_c = makequery($query_c)or die("query funzione cancellazione univoca fallita");
	$num_c = mysqli_num_rows($res_c);
		
	if($num_c == "0")  //posso cancellare
	{
		$filename = $percorso."/".$file;
		
		if(file_exists($filename))
			unlink($filename);
			
		//Devo controllare se c'è la thumb, e nel caso cancellarla
		$nomeFile = dimminomefile($file);
		$tipofile = tipo_file($file);
		$fileThumb = $percorso."/".$nomeFile . "_thumb." . $tipofile;
		
		if(file_exists($fileThumb))
			unlink($fileThumb);
			
		//Devo controllare se c'è l'originale, e nel caso cancellarla
		$fileOrig = $percorso."/".$nomeFile."_orig.".$tipofile;
		
		if(file_exists($fileOrig))
			unlink($fileOrig);
	}
}

//funzione che restituisce la cartella per l'upload, a seconda della tbl
function dimmi_cartella_upload($idTabella)
{
	$query = "select cartella_upload from gestione_tabelle where idGT = " . $idTabella;
	$res = makequery($query);
	$arr = makefetch($res);
	
	//print $arr["cartella_upload"];
	return $arr["cartella_upload"];
}

//funzione che cancella i file vecchi nella cartella temporanea
function cancella_file_old($directory, $data_limite)
{
	$dirs= array();
	$files = array();
	
	if ($handle = opendir($directory))
	{
		while ($file = readdir($handle))
		{
			if (is_dir("./{$directory}/{$file}"))
			{
				if ($file != "." & $file != "..")
					$dirs[] = $file;
			}
			else
			{
				if ($file != "." & $file != "..")
					$files[] = $file;
			}
		}
	}
	closedir($handle);

	while(list($key, $file) = each($files))
	{
		$pos_underscore = strpos($file,"_")+1;
		$pos_punto = strpos($file,".");
		
		$data_file = substr($file, $pos_underscore, ($pos_punto-$pos_underscore));
		if($data_file<=$data_limite)
		{
			unlink($directory.$file);
		}
	}
}


function cancella_file_tabella($nomeTbl, $id)
{
	$campiTbl = dimmi_campi_tabella($nomeTbl);
	$arrayCampi = explode(":", $campiTbl);
	
	foreach($arrayCampi as $campoTbl)
	{
		$idTabellaFile = dimmi_id_tabella($nomeTbl, $campoTbl);
		$cartella_upload = dimmi_cartella_upload($idTabellaFile);
	
		$percorsoFile = "../".UPLOAD_FILE."/".$cartella_upload."/";
	
		//si devono controllare i file, se sono da cancellare dal file system oppure no
		$queryFile = "select * from file where (fk_tabella = " . $idTabellaFile . ") and fk_record = " . $id;
		$resFile = makequery($queryFile);
		
		while($arrFile = makefetch($resFile))
		{
			if($arrFile["tipoF"] == "file")
			{
				elimina_unico($arrFile["file"],$idTabellaFile,$percorsoFile,$arrFile["idF"],$arrFile["tipoF"]);
			}
			else
			{
				$arrayFile = explode(":", $arrFile["file"]);
				elimina_unicoMd5($arrayFile[1],$idTabellaFile,$percorsoFile,$arrFile["idF"],$arrFile["tipoF"]);
			}
		}
		
		//faccio pulizia nella tbl file
		$queryDel = "delete from file where (fk_tabella = " . $idTabellaFile . ") and fk_record = " . $id;
		makequery($queryDel);

	}
}




/*************************************************FINE GESTIONI FILE IN UPLOAD*************************************************/




/*- - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - */




/******************************************************************************************************************************
*************************************************FUNZIONI PER LE IMMAGINI******************************************************
*******************************************************************************************************************************/

//funzione che ridimensiona una immagine
function resizeImage($image,$width,$height,$scale) {


	$newImageWidth = ceil($width * $scale);
	$newImageHeight = ceil($height * $scale);

	$newImage = imagecreatetruecolor($newImageWidth,$newImageHeight);
	
	$tipo_file = tipo_file($image);
	
	if($tipo_file=="jpg")
		$source = imagecreatefromjpeg($image);
	else if($tipo_file=="gif")
		$source = imagecreatefromgif($image);
	else if($tipo_file=="png")
		$source = imagecreatefrompng($image);
		
		
	/*MODIFICHE PER GESTIRE LA TRASPARENZA*/
	imagealphablending($newImage, false);
	
	// get and reallocate transparency-color
	$transindex = imagecolortransparent($source);
	if($transindex >= 0) {
	  $transcol = imagecolorsforindex($source, $transindex);
	  $transindex = imagecolorallocatealpha($newImage, $transcol['red'], $transcol['green'], $transcol['blue'], 127);
	  imagefill($newImage, 0, 0, $transindex);
	}		
	
	imagecopyresampled($newImage,$source,0,0,0,0,$newImageWidth,$newImageHeight,$width,$height);

	// restore transparency
	if($transindex >= 0) {
	  imagecolortransparent($newImage, $transindex);
	  for($y=0; $y<$newImageHeight; ++$y)
	  {
		for($x=0; $x<$newImageWidth; ++$x)
		{
		  if(((imagecolorat($newImage, $x, $y)>>24) & 0x7F) >= 100) 
		  {	imagesetpixel($newImage, $x, $y, $transindex); }
		}
	  }
    }
	
	if($tipo_file=="jpg")
		imagejpeg($newImage,$image,100);
	else if($tipo_file=="gif")
	{
		imagetruecolortopalette($newImage, true, 255);
		imagesavealpha($newImage, false);
		imagegif($newImage, $image); 	
	}
	else if($tipo_file=="png")
		imagepng($newImage,$image,0);

	chmod($image, 0777);
	return $image;
}


//funzione che crea una thumb
function resizeThumbnailImage($thumb_image_name, $image, $width, $height, $start_width, $start_height, $scale){
	$newImageWidth = ceil($width * $scale);
	$newImageHeight = ceil($height * $scale);
	$newImage = imagecreatetruecolor($newImageWidth,$newImageHeight);

	$tipo_file = tipo_file($thumb_image_name);
	
	if($tipo_file=="jpg")
		$source = imagecreatefromjpeg($image);
	else if($tipo_file=="gif")
		$source = imagecreatefromgif($image);
	else if($tipo_file=="png")
		$source = imagecreatefrompng($image);
	
		
	/*MODIFICHE PER GESTIRE LA TRASPARENZA*/
	imagealphablending($newImage, false);
	
	// get and reallocate transparency-color
	$transindex = imagecolortransparent($source);
	if($transindex >= 0) {
	  $transcol = imagecolorsforindex($source, $transindex);
	  $transindex = imagecolorallocatealpha($newImage, $transcol['red'], $transcol['green'], $transcol['blue'], 127);
	  imagefill($newImage, 0, 0, $transindex);
	}		
	
	imagecopyresampled($newImage,$source,0,0,$start_width,$start_height,$newImageWidth,$newImageHeight,$width,$height);
	
	// restore transparency
	if($transindex >= 0) {
	  imagecolortransparent($newImage, $transindex);
	  for($y=0; $y<$newImageHeight; ++$y)
	  {
		for($x=0; $x<$newImageWidth; ++$x)
		{
		  if(((imagecolorat($newImage, $x, $y)>>24) & 0x7F) >= 100) 
		  {	imagesetpixel($newImage, $x, $y, $transindex); }
		}
	  }
    }
	

	if($tipo_file=="jpg")
		imagejpeg($newImage,$thumb_image_name,100);
	else if($tipo_file=="gif")
	{
		imagetruecolortopalette($newImage, true, 255);
		imagesavealpha($newImage, false);
		imagegif($newImage, $thumb_image_name); 	
	}
	else if($tipo_file=="png")
		imagepng($newImage,$thumb_image_name,0);

	chmod($thumb_image_name, 0777);
	return $thumb_image_name;
}


//funzione che salva un file in upload facendo l'md5
function salvafile($nomecampofile,$percorso,$pagina,$arrayEstensioni="")
{
	$userfile_name = $_FILES[$nomecampofile]["name"];
	$file_name= basename($userfile_name);		
	$lunghezzastringa= strlen ($file_name);
	$posizionedelpunto = strpos($file_name,".");
	$nomefile=substr($file_name,0,$posizionedelpunto);
	$ncaratteri = ($lunghezzastringa - 1 - $posizionedelpunto);
	$tipofile=substr($file_name,$posizionedelpunto + 1,$ncaratteri);
	
	$attimo = date("YmdHis");
	$filename = $nomefile . $attimo . "." . $tipofile;
	
	if (!move_uploaded_file($_FILES[$nomecampofile]["tmp_name"],"$percorso/$filename"))
	{ 
		header("Location: $pagina");
		exit();
	}
	
	
	$tipofile = strtolower($tipofile);
	
	//devo controllare l'estensione
	if($arrayEstensioni<>"" && !in_array(trim($tipofile),$arrayEstensioni))
	{
		//cancello l'immagine appena uploadata
		@unlink("$percorso/$filename");
		//e reinderizzo alla pagina, passando parametro di errore
		header("Location: $pagina&errore=estensione");		
		exit();
	}
							
	$file = file("$percorso/$filename");
	$md5file = md5_file("$percorso/$filename");
	
	if(file_exists("$percorso/$md5file.$tipofile"))
	{
		unlink("$percorso/$filename");
	}
	else
	{
		rename("$percorso/$filename","$percorso/$md5file.$tipofile");
	}
	$immagine= $_FILES[$nomecampofile]["name"] . ":" . $md5file . "." . $tipofile;
	return $immagine;
}



//funzione che restituisce l'estensione di un file
function tipo_file($userfile_name)
{
	$file_name= basename($userfile_name);  //restituisce il nome del file
	
	$lunghezzastringa= strlen ($file_name);
	$posizionedelpunto = strpos($file_name,".");
	$nomefile=substr($file_name,0,$posizionedelpunto);
	$ncaratteri = ($lunghezzastringa - 1 - $posizionedelpunto);
	$tipofile=substr($file_name,$posizionedelpunto + 1,$ncaratteri);  //tipo del file caricato
	
	$tipofile = strtolower($tipofile);
	
	return $tipofile;
}


//funzione che restituisce il nome di un file, senza l'estensione
function dimminomefile($file)
{
	$file_name= basename($file);  //restituisce il nome del file
	
	$lunghezzastringa= strlen ($file_name);
	$posizionedelpunto = strpos($file_name,".");
	$nomefile=substr($file_name,0,$posizionedelpunto);
	
	return $nomefile;
}


//funzione che restituisce l'altezza di una immagine
function getHeight($image) {
	$sizes = getimagesize($image);
	$height = $sizes[1];
	return $height;
}

//funzione che restituisce la larghezza di una immagine
function getWidth($image) {
	$sizes = getimagesize($image);
	$width = $sizes[0];
	return $width;
}


/************************************************FINE FUNZIONI PER LE IMMAGINI*************************************************/



/*- - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - */



/******************************************************************************************************************************
*************************************************FUNZIONI GENERICHE************************************************************
*******************************************************************************************************************************/

/*funzione che restituisce l'icona relativa ad un tipo di file, in base all'estensione del file*/
function icona_file($filename)
{
	$tipofile = tipo_file($filename);
	
	switch(strtolower($tipofile))
	{
		case "doc":
			$icona = "doc.jpg";
			break;
		case "pdf":
			$icona = "pdf.jpg";
			break;
		case "txt":
			$icona = "txt.jpg";
			break;
		case "xls":
			$icona = "xls.jpg";
			break;
		case "zip":
			$icona = "zip.jpg";
			break;
		case "jpg":
			$icona = "img.jpg";
			break;
		case "gif":
			$icona = "img.jpg";
			break;
		case "swf":
			$icona = "filmato.jpg";
			break;
		case "flv":
			$icona = "filmato.jpg";
			break;
		case "mp4":
			$icona = "filmato.jpg";
			break;
		default:
			$icona = "ic_clips.jpg";
			break;
	}

	return $icona;

}


//Funzione che restituisce la porzione $dim di $testo, controllando che non sia troncata una parola a metà
function taglia_testo($testo, $dim, $boolTag)
{
	($boolTag)? $testo = $testo : $testo = strip_tags($testo);

	if(strlen($testo) > $dim)  //serve accorciare
	{
		$testoTagliato = substr($testo,0,$dim); 

		//recupero l'ultimo spazio
		$posSpazio = strrpos($testoTagliato, " ");
		($posSpazio) ? $testo = substr($testoTagliato,0,$posSpazio) :  $testo = $testoTagliato;
		
		$testo .= "...";
	}
	
	return $testo;
}


//funzione che mi restituisce un valore da una tabella
function dimmi_valore($tabella, $campo, $condizioni)
{
	$query = "select " . $campo . " from " . $tabella . " where " . $condizioni;
	//print $query;
	$res = makequery($query);
	$arr = makefetch($res);
	
	return $arr[$campo];
}

//funzione che cancella dei record da una tabella
function cancella_record($tabella, $campo_chiave, $id)
{
	$cancella = "delete from " . $tabella . " where " . $campo_chiave . " = " . $id;
	makequery($cancella);
}


//funzione che, da una data in formato aaaa-mm-gg, restituisce una data nel formato italiano
function data_ita($data)
{
	$gg = substr($data, 8, 2);
	$mm = substr($data, 5, 2);
	$aa = substr($data, 0, 4);
	
	return $gg . "/" . $mm . "/" . $aa;
}


function data_ita_conora($data)
{
	$gg = substr($data, 8, 2);
	$mm = substr($data, 5, 2);
	$aa = substr($data, 0, 4);
	
	$hh = substr($data, 11, 2);
	$min = substr($data, 14, 2);
	
	return $gg . "/" . $mm . "/" . $aa . " " . $hh . ":" . $min;
}

//funzione che scrive nei campi hidden tutti i valori inseriti nella form
function scriviHidden()
{
	foreach($_POST as $key => $value) 
	{
		echo "<input type='hidden' name='".$key."' value='".$value."'>";
	}
}


// funzione per la sostituzione delgi apici e delle lettere particolari in codice HTML
function aggiusta_post($stringa)
{
	if(isset($stringa) && $stringa!="")
	{
		//$stringa=str_replace("'", "&#39;",$stringa);		
		$stringa=htmlentities($stringa,ENT_QUOTES);
		$stringa=nl2br($stringa);
	}
	else
		$stringa="";
			
	return $stringa;
}

//funzione che crea una cartella
function crea_percorso($path)
{
	if(!$path{strlen($path-1)} == "/")
		$path .= "/";
		
	$arr_path = explode("/",$path);
	for($i=0; $i<sizeof($arr_path); $i++)
	{
		if($arr_path[$i]!=".." && $arr_path[$i]!="")
		{
			$path_crea="";
			for($y=0; $y<=$i; $y++)
			{
				$path_crea .= $arr_path[$y]."/";
			}
			if (!is_dir($path_crea))
				@mkdir($path_crea, 0777); // creo la directory		
		}
	}
}

//funzione che controlla se si può cancellare e cancella un file dal server
function cancella_file($tabella, $campo_img, $campo_id, $id_record, $md5file, $percorso_file)
{
	$query_controllo = "select " . $campo_img . " from " . $tabella . " where " . $campo_img . " LIKE '%" .$md5file. "%' and ".$campo_id." <> " . $id_record;
	$res_controllo = makequery($query_controllo);
	$num_controllo = mysqli_num_rows($res_controllo);
	
	if($num_controllo == 0)  //posso cancellare
	{
		$filename = $percorso_file . "/" . $md5file;
	
		if(file_exists($filename))
		{
			unlink($filename);
		}
	
		//Devo controllare se c'è la thumb, e nel caso la cancella
		//nome dell'img md5, senza estensione 
		$nome_file = dimminomefile($percorso_file."/".$md5file);
		
		$nome_file_thumb = $nome_file."_thumb.jpg";
		
		if(file_exists($percorso_file . "/" . $nome_file_thumb))
		{
			unlink($percorso_file . "/" . $nome_file_thumb);
		}
	}
	
}

/* funzione che restituisce il massimo valore di sorting */
function restituisci_max_sorting($nome_tabella, $nome_campo, $condizioni) {
	$query = "SELECT MAX(".$nome_campo.") FROM ".$nome_tabella." WHERE ".$condizioni;
	
	$res = makequery($query);
	$risp = makefetch($res);

	return($risp[0]);
}


// parametri= [id del record selezionato], [azione: "up" o "down"], [nome tabella], [nome campo chiave], [nome campo sorting], [condizioni di ricerca del massimo valore di sorting nella tabella]
function cambia_ordinamento_campi($chiave_record_sel, $azione, $nome_tabella, $nome_campo_chiave, $nome_campo_sorting, $condizione)
{
	$id = $chiave_record_sel;

	$fai_update=true;
	
	// Recupero il max valore di sorting
	$max_sorting = restituisci_max_sorting($nome_tabella, $nome_campo_sorting, $condizione);
	
	//recupero la posizione in elenco
	$querySort = "select * from ".$nome_tabella." where ".$nome_campo_chiave." = " . $id;
	$resSort = makequery($querySort);
	$array = makefetch($resSort);
	if ($azione == "up")
	{
		$sort = $array[$nome_campo_sorting] - 1;
		if($sort==0)
		{
			$sort = 1;
			$fai_update=false;
		}
		$sortaltro = $array[$nome_campo_sorting];
		$pos = $array[$nome_campo_sorting] - 1;
		$updatedue = "update ".$nome_tabella." set ".$nome_campo_sorting." = " . $sortaltro . " where ".$nome_campo_sorting." = " . $pos. " and ".$condizione;
	}
	else
	{
		$sort = $array[$nome_campo_sorting] + 1;
		if($sort > $max_sorting)
		{
			$sort = $max_sorting;
			$fai_update=false;
		}
		$sortaltro = $array[$nome_campo_sorting];
		$pos = $array[$nome_campo_sorting] + 1;
		$updatedue = "update ".$nome_tabella." set ".$nome_campo_sorting." = " . $sortaltro . " where ".$nome_campo_sorting." = " . $pos. " and ".$condizione;
	}
	
	//faccio l'update dei due record
	$updateuno = "update ".$nome_tabella." set ".$nome_campo_sorting." = " . $sort . " where ".$nome_campo_chiave." = " . $id;

	if($fai_update)
	{
		makequery($updatedue);
		makequery($updateuno);
	}
}


//funzione che restituisce un elenco di record, formattato per l'area admin.
function elenco_record_admin($query_fissa, $campoId, $arrEtichette, $arrCampi, $arrFunzioni, $pagDettaglio, $record_per_pagina, $nome_sessione, $str_condizioni, $sorting, $campo_sorting, $criteri_per_tornare_indietro = '', $cbElenco=true)
{
	//Recupero i dati
	$res = makequery($query_fissa);
	$num = mysqli_num_rows($res);
			
	if($num == 0)
	{
		return "0";
	}
	else
	{
		$strRecord = "";
			
		//*******Codice per la paginazione********************
		if(!isset($_SESSION[$nome_sessione]) or isset($_GET["primoaccesso"]))
		 {
			$_SESSION[$nome_sessione]=0;
		 }
	
		if(isset($_GET["go"]))
		{
			if($_GET["go"]=="inizio")
			{
				$_SESSION[$nome_sessione]=0;
			}
			elseif($_GET["go"]=="indietro")
			{
				$_SESSION[$nome_sessione]=$_SESSION[$nome_sessione]-$record_per_pagina;
				if($_SESSION[$nome_sessione]<0)
				{
					$_SESSION[$nome_sessione]=0;
				}
			}
			elseif($_GET["go"]=="avanti")
			{
				if($_SESSION[$nome_sessione]+$record_per_pagina>=$num)
					$_SESSION[$nome_sessione]=$_SESSION[$nome_sessione];
				else
					$_SESSION[$nome_sessione]=$_SESSION[$nome_sessione]+$record_per_pagina;
			}
			elseif($_GET["go"]=="fine")
			{
				$_SESSION[$nome_sessione]=$num-$record_per_pagina;
			}
		 }
		 //***********fine codice per paginazione**********************+
	
	
		  //fisso il numero di elementi per pagina
		  $limit_str = " limit " .$_SESSION[$nome_sessione]. ", " . $record_per_pagina;
			
		  //faccio la query con paginazione			
		  $sqlpaginazione = $query_fissa . $limit_str;
		  $dati = makequery($sqlpaginazione);
		
		  $strRecord .= "<table width=\"100%\" border=\"0\" cellspacing=\"2\" cellpadding=\"2\">";
		  $strRecord .= "<tr class=\"colore_testata\">";
			 
		
		  if($sorting)
		  	$strRecord .= "<td class=\"testo\" align=\"center\" width=\"7%\" colspan=\"2\"><strong>ORDINA</strong></td>";
		  $strRecord .= "<td width=\"10%\">&nbsp;</td>";
		
		  foreach($arrEtichette as $etichetta)
		  {	 
			 $strRecord .= "<td class=\"testo\" align=\"center\"><strong>".$etichetta."</strong></td>";
		  }	
			
		  $strRecord .=	"</tr>";


		  $sfondo = "colore_uno";
		  
			while($arr_mod = makefetch($dati))				  
			{
				$tmp = date("YdmHis") . rand();
			
				if($sfondo == "colore_uno")
				{
					$sfondo = "colore_due";
				}
				else
				{
					$sfondo = "colore_uno";
				}
				
				$strRecord .= "<tr class='".$sfondo."'>";
				
				if($sorting)
				{
					if($num>1)
					{
						$strRecord .= "<td width='14' align='center'>";
						if($arr_mod[$campo_sorting]>1)
							$strRecord .= "<a href='".$_SERVER['PHP_SELF']."?id=".$arr_mod[$campoId]."&azione=up&tmp=".$tmp.$str_condizioni."'><img src='img/su.gif' border='0'></a>";
						$strRecord .= "</td>";
			
						$strRecord .= "<td width='14' align='center'>";
						if($arr_mod[$campo_sorting]<$num)
							$strRecord .= "<a href='".$_SERVER['PHP_SELF']."?id=".$arr_mod[$campoId]."&azione=down&tmp=".$tmp.$str_condizioni."'><img src='img/giu.gif' border='0'></a>";
						$strRecord .= "</td>";
					}
					else
					{
						$strRecord .= "<td>&nbsp;</td><td>&nbsp;</td>";
					}
				}
				
				
				$strRecord .= "<td align='center'>";
				
				($cbElenco) ? $strRecord .= "<input type='checkbox' name='id[]' value='" . $arr_mod[$campoId] . "'>&nbsp;" : "";
				
				//devo controllare se nel link ci sono già dei parametri
				(strrchr($pagDettaglio,"?")) ? $paginaLink = $pagDettaglio."&id=".$arr_mod[$campoId] : $paginaLink = $pagDettaglio."?id=".$arr_mod[$campoId];
				
				$paginaLink .= $criteri_per_tornare_indietro;
				
				$strRecord .= "<a href='".$paginaLink."'><img src='img/modifica.gif' border=0 title='dettaglio'></a></td>";
				
				$i=0;
				foreach($arrCampi as $key=>$campo)
				{
					$funz = $arrFunzioni[$i];
					if($arrFunzioni[$i]<>"")
					{
						$strRecord .= "<td class='testo'>" . " " .  $funz(html_entity_decode($arr_mod[$campo])) . "</td>";
					}
					else
					{
						$strRecord .= "<td class='testo'>" . " " . html_entity_decode($arr_mod[$campo]) . "</td>";
					}
					$i++;
				}
				
				$strRecord .= "</tr>";
				
			 }
			
			$strRecord .= "</table> <br />";
				
				 
			$strRecord .= "<div align=\"center\"> <font size=\"1\">";
		  
			// print dei record visualizzati
			if($num > 0)
			{
				$ultimorecordvisualizzato=$_SESSION[$nome_sessione]+$record_per_pagina;
				if($ultimorecordvisualizzato>=$num)
				{
					$ultimorecordvisualizzato=$num;
				}
					
				if($num==0)
				{
					$recordiniziale=0;
				}
				else
				{
					$recordiniziale=$_SESSION[$nome_sessione]+1;
				}
				$strRecord .= "<BR>Risultati " .$recordiniziale. " - " .$ultimorecordvisualizzato. " di " .$num. "<BR><BR>";
			}
		
			$strRecord .= "</font> <font size=\"1\">";
			
			if($num > $record_per_pagina)
			{	    
				if($recordiniziale==1 or $recordiniziale==0)
				{
					$strRecord .= "<<< Inizio&nbsp;&nbsp;&nbsp;&nbsp;<< Indietro&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
				}
				else
				{
					$strRecord .= "<a href=\"".$_SERVER['PHP_SELF']."?go=inizio".$str_condizioni."\"><u><<< Inizio</u></a>&nbsp;&nbsp;&nbsp;&nbsp;<a href=\"".$_SERVER['PHP_SELF']."?go=indietro".$str_condizioni."\"><u><< Indietro</u></a>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
				}
				
				if($ultimorecordvisualizzato==$num)
				{
					$strRecord .= "Avanti >>&nbsp;&nbsp;&nbsp;&nbsp;Fine>>>";
				}
				else
				{
					$strRecord .= "<a href=\"".$_SERVER['PHP_SELF']."?go=avanti".$str_condizioni."\"><u>Avanti >></u></a>&nbsp;&nbsp;&nbsp;&nbsp;<a href=\"".$_SERVER['PHP_SELF']."?go=fine".$str_condizioni."\"><u>Fine >>></u></a>";
				}
			}
		
	 	 $strRecord .= "</font></div>";

		 return $strRecord;
	}
} 


function pubblica_tf($tf)
{	
	if($tf=="t")
		$img = "<div align='center'><img src='img/success.png' alt='pubblicato' title='pubblicato'></div>";
	else
		$img = "<div align='center'><img src='img/error.png' alt='non pubblicato' title='non pubblicato'></div>";
		
	return $img;
}


//funzione che controlla non ci siano tentativi di spam tramite i form
function preprocessHeaderField($value)
{
  $spamming = "no";
  $valore = $value;
  
  //Remove line feeds
  if(is_numeric(strpos($value,"\\r")))
  {
	$spamming = "si";
  }
  if(is_numeric(strpos($value,"\\n")))
  {
	$spamming = "si";
  }
	$ret = str_replace("\\r", "", $value);
	$ret = str_replace("\\n", "", $ret);
  //Remove injected headers
  $find = array("/bcc\:/i",
				"/Content\-Type\:/i",
				"/Mime\-Type\:/i",
				"/cc\:/i",
				"/to\:/i");
	$lunprima = strlen ($ret);
	
	$ret = preg_replace($find, "", $ret);
	
	$lundopo = strlen ($ret);
	
	if($lunprima>$lundopo)
	{
		$spamming = "si";
	}

	if($spamming=="si")
	{
		 $headers  = "MIME-Version: 1.0\n";
		 $headers .= "Content-type: text/html; charset=iso-8859-1\n";		 
		 $headers .= "From: cvisca@webworking.it <cvisca@webworking.it>\nReply-To: cvisca@webworking.it\n";
		 
		 $subject = "Spamming su MS";
		 $messaggio = $valore;
		 mail ("cvisca@webworking.it", $subject, $messaggio, $headers);
	}
	
  return $spamming;
}


function makequery($sql){
	$db = new Db();
	return $db->query($sql);
}


function makefetch(&$res){
	$db = new Db();
	return $db->fetch_from_resource($res);
}

?>
