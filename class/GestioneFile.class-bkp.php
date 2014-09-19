<?php

class GestioneFile{
	
	private $conn;
	
	private $tabella;
	private $idTabella;
	private $tipo;
	private $campo;
	public $folder_upload=UPLOAD_FILE;
	
	public function File($nomeTbl, $tipo, $campo){
		$this->conn = new Db();
		$this->tabella = $nomeTbl;
		$this->tipo = $tipo;
		$this->campo = $campo;
		$this->idTabella = $this->get_id_tabella();
	}
	
	
	public function getById($id){
		return $this->conn->getById($id, "file", "idF");
	}
	
	public function get_id_tabella()
	{
		$query = sprintf("select idGT from gestione_tabelle where tabella = '%s' and campo = '%s' and tipo = '%s'", $this->tabella, $this->campo, $this->tipo);
		$this->conn->query($query);
		$row = $this->conn->fetch();
		return $row["idGT"];
	}
	
	private function get_nfile(){
		$query = sprintf("select n_file from gestione_tabelle where idGT = %d", $this->idTabella);
		$this->conn->query($query);
		$row = $this->conn->fetch();
		
		return $row["n_file"];
	}
	
	private function get_etichetta(){
		$query = sprintf("select etichetta from gestione_tabelle where idGT = %d", $this->idTabella);
		$this->conn->query($query);
		$row = $this->conn->fetch();
		
		return $row["etichetta"];
	}
	
	public function get_cartella(){
		$query = sprintf("select cartella_upload from gestione_tabelle where idGT = %d", $this->idTabella);
		$this->conn->query($query);
		$row = $this->conn->fetch();
		
		return $row["cartella_upload"];
	}
	
	private function get_tipo_ridim(){
		$query = sprintf("select tipo_ridim from gestione_tabelle where idGT = %d", $this->idTabella);
		$this->conn->query($query);
		$row = $this->conn->fetch();
		
		return $row["tipo_ridim"];
	}
	
	
	private function get_dimensioni(){
		$query = sprintf("select width_big, height_big, width_thumb, height_thumb from gestione_tabelle where idGT = %d", $this->idTabella);
		$this->conn->query($query);
		$row = $this->conn->fetch();
		
		$arrayDim = array($row["width_big"], $row["height_big"], $row["width_thumb"], $row["height_thumb"]);
		return $arrayDim;
	}
	
	
	public function campi_vuoti(){
		$quantiFile = $this->get_nfile();
		$etichettaTbl = $this->get_etichetta();
		$tipoRidim = $this->get_tipo_ridim();
		
		($this->tipo == "file") ? $nomeFile = "file" : $nomeFile = "immagine";
		
		$etichetta = $this->campo."_et_".$nomeFile;
		$nomeFile = $this->campo."_".$nomeFile;
		
		
		$return_tbl = "";
		
		if($tipoRidim == 0 or $tipoRidim == 3)
		{
			for($i=1; $i<=$quantiFile; $i++)
			{
				$return_tbl .= "<tr><td class=\"backscuro\">".$etichettaTbl. " " . $i . "</td><td class=\"backchiaro\">etichetta: <input type='text' name='".$etichetta."_".$i."' class='input'> &nbsp;&nbsp;&nbsp;file: <input type='file' name='".$nomeFile."_".$i."' class='input'></td></tr>";
			}
		}
		
		return $return_tbl;
	}


	public function get_file($idRecord){
		//recupero i file già inseriti
		$queryFile = sprintf("select idF, file, titoloF, sorting from file where fk_tabella = %d and fk_record = %d order by sorting", $this->idTabella, $idRecord);
		$this->conn->query($queryFile);
		
		$arrFile = array();
		
		while($arr = $this->conn->fetch())
		{ $arrFile[] = array("idF"=>$arr["idF"], "file"=>$arr["file"], "etichetta"=>$arr["titoloF"], "sorting"=>$arr["sorting"]); }

		return $arrFile;
	}

	
	
	public function get_anteprimafoto($idFoto)
	{
		$cartellaUp = $this->get_cartella();
		$percorso = "../".UPLOAD_FILE."/".$cartellaUp;
	
		$queryF = "select * from file where idF=".$idFoto;
		$this->conn->query($queryF);
		
		if($this->conn->rowCount()>0)
		{
			$arr = $this->conn->fetch();
			$arrayFoto = explode(":",$arr["file"]);
			//return "<img src='".$percorso."/".$arrayFoto[1]."' width='50'>";
			return "<img src='../funzioni/ridimensiona_img.php?nome_file=".$percorso."/".$arrayFoto[1]."&baseThumb=70&hThumb=70' border='0' width=70>";
		}
		else
		{
			return "";
		}
	}


	public function campi_dett($idRecord){
	
		$quantiFile = $this->get_nfile();
		$etichettaTbl = $this->get_etichetta();
		$cartella_upload = $this->get_cartella();
		$tipoRidim = $this->get_tipo_ridim();
		
		($this->tipo == "file") ? $nomeFile = "file" : $nomeFile = "immagine";
		
		
		$etichetta = $this->campo."_et_".$nomeFile;
		$nomeFile = $this->campo."_".$nomeFile;
		
		//recupero i file già inseriti
		$arrFile = $this->get_file($idRecord);
			
		$return_tbl = "";
		for($i=1; $i<=$quantiFile; $i++)
		{
			(isset($arrFile[($i-1)])) ? $valueEtichetta = $arrFile[$i-1]["etichetta"] : $valueEtichetta="";
			$cartellaUp = $this->get_cartella();
		
			if($this->tipo=="file")
			{
			
				$return_tbl .= "<tr><td class=\"backscuro\">".$etichettaTbl." " . $i ."</td><td class=\"backchiaro\">etichetta: <input type='text' name='".$etichetta."_".$i."' class='input' value='".$valueEtichetta."'> &nbsp;&nbsp;&nbsp;file: <input type='file' name='".$nomeFile."_".$i."' class='input'><br /><font size=1>file attuale: ";
				
				if(isset($arrFile[($i-1)]) and $arrFile[$i-1] <> "")
				{
					$array_file = explode(":",$arrFile[$i-1]["file"]);
				
					$return_tbl .= "<a href='../".$this->folder_upload."/".$cartellaUp."/".$array_file[1] ."' target='_blank'>" . $array_file[0] . "</a><br />";
					$return_tbl .= "<input type='checkbox' value='si' name='".$this->campo."canc_".$i."'> cancella il file attuale";
					$return_tbl .= "<input type='hidden' name='".$this->campo."idFoto_".$i."' value='".$arrFile[$i-1]["idF"]."'>";
				}
				else
					$return_tbl .= " nessuno";
			
				$return_tbl .= "</font></td></tr>";
			}
			else  //immagine
			{
				$percorsoFile = "../".$this->folder_upload."/".$cartella_upload."/";
			
				$return_tbl .= "<tr><td class=\"backscuro\">".$etichettaTbl." " . $i . "</td><td class=\"backchiaro\">";
				
				
				if($tipoRidim == 0 or $tipoRidim == 3)
				{
					$return_tbl .=  "etichetta: <input type='text' name='".$etichetta."_".$i."' class='input' value='".$valueEtichetta."'> &nbsp;&nbsp;&nbsp;file: <input type='file' name='".$nomeFile."_".$i."' class='input'><br /><font size=1>".$nomeFile." attuale: ";
				
					if(isset($arrFile[($i-1)]) and $arrFile[$i-1] <> "")
					{
						$array_file = explode(":",$arrFile[$i-1]["file"]);
					
						$return_tbl .= "<a href='../".$this->folder_upload."/".$cartellaUp."/".$array_file[1] ."' target='_blank'>" . $array_file[0] . "</a><br />";
						$return_tbl .= "<input type='checkbox' value='si' name='".$this->campo."cancFoto_".$i."'> cancella la foto attuale";
						$return_tbl .= "<input type='hidden' name='".$this->campo."idFoto_".$i."' value='".$arrFile[$i-1]["idF"]."'>";
					}
					else
						$return_tbl .= " nessuna";
				}
				else
				{
					if(isset($arrFile[($i-1)]) and $arrFile[$i-1] <> "")
					{
						$arr_img = explode(":", $arrFile[($i-1)]["file"]);
						$nomeFileImg = dimminomefile($arr_img[1]);
						$tipoFile = tipo_file($arr_img[1]);
						$nomeThumb = $nomeFileImg . "_thumb." . $tipoFile;
					
						$return_tbl .= "<a href='".$percorsoFile.$arr_img[1]."' target='_blank'><img src='".$percorsoFile.$nomeThumb."' border=0></a>";
						$return_tbl .= "<br /><input type='checkbox' value='si' name='".$this->campo."cancFoto_".$i."'> cancella la foto attuale";
						$return_tbl .= "<input type='hidden' name='".$this->campo."idFoto_".$i."' value='".$arrFile[$i-1]["idF"]."'>";
					}
					else
					{
						$return_tbl .= "<a href=\"#\" onClick=\"apri_upload(".$idRecord.", ".$this->idTabella.");\">carica un'immagine</a>";
					}
				}
				
				print "</td></tr>";
			}
			
		}
	
		return $return_tbl;
	}
	
	
	public function addFile($file, $etichetta, $id){
		$result = false;
		$etichetta = $this->conn->str($etichetta);
		
		$sort = new Sorting("file", "sorting", "fk_tabella=".$this->idTabella." and fk_record=".$id);
		$sorting = ($sort->restituisci_max_sorting() + 1);
		
		$query = "insert into file (file, fk_tabella, fk_record, tipoF, titoloF, sorting) values ('".$file."', ".$this->idTabella.", ".$id.", '".$this->tipo."', '".$etichetta."', ".$sorting.")";
		print $query."<br /><br />";
		if($this->conn->query($query))
		{ $result = true; }
		
		return $result;
	}
	
	
	
	public function uploadFile($idR, $campoFile="file_", $campoEtichetta="et_file_", $campoCanc="canc_")
	{	
		$nFile = $this->get_nfile();
		
		if($nFile > 0)
		{
			//recupero i file già inseriti
			$query = sprintf("select * from file where fk_tabella = %d and fk_record = %d order by sorting", $this->idTabella, $idR);
			$this->conn->query($query);
			
			$arrayFile = array();
			while($rowFile = $this->conn->fetch())
			{
				$arrayFile[] = array("idF"=>$rowFile["idF"], "file"=>$rowFile["file"], "etichetta"=>$rowFile["titoloF"]);
			}
		
			$arr_file = array();
			$arr_etichette = array();
		
			$dir_dest = "../".$this->folder_upload . "/" . $this->get_cartella();
			
			for($i=1; $i<=$nFile; $i++)
			{
				$nomeCampoFile = $this->campo."_".$campoFile.$i;
				$nomeCampoEtichetta = $this->campo."_".$campoEtichetta.$i;
				$nomeCampoCanc = $this->campo.$campoCanc.$i;
													
				if(isset($_FILES[$nomeCampoFile]) and $_FILES[$nomeCampoFile]["size"]>0)
				{						
					if(!$handle = new Upload($_FILES[$nomeCampoFile]))
					{  exit ('error : ' . $handle->error); }								
				
					$handle->no_script = false;
					$handle->file_overwrite = true;
					$handle->Process($dir_dest);
					
					if($handle->processed)
					{
						$destname = $handle->file_dst_name;
						$filenamemd5 = md5_file($dir_dest."/".$destname);
						
						//controllo se è una immagine e se devo fare ridimensionamento
						if($this->tipo == "img")
						{
							if($handle->file_is_image)
							{							
								$tipoRid = $this->get_tipo_ridim();
								
								if($tipoRid == 3)
								{
									$handle_img = new Upload($dir_dest."/".$destname);
									$ext = $handle_img->file_src_name_ext; //estensione del file
								
									//recupero le dimensioni da fissare
									$arrayDim = $this->get_dimensioni();
								
									if($handle_img->image_src_x > $handle_img->image_src_y)  //devo fissare la base
									{
										$widthB = $arrayDim[0];
										$widthT = $arrayDim[2];
										
										if($handle_img->image_src_x > $widthB)
										{
											$handle_img->image_resize = true;
											$handle_img->image_ratio_y = true;
											$handle_img->image_x = $widthB;
											$handle_img->file_overwrite = true;
											
											$handle_img->Process($dir_dest);										
										}
										
										$handle_img->file_new_name_body = $filenamemd5;
										$handle_img->file_overwrite = true;
										$handle_img->Process($dir_dest);
										
										if($widthT > 0)
										{
											if($handle_img->image_src_x > $widthT)
											{										
												$handle_img->image_resize = true;
												$handle_img->image_ratio_y = true;
												$handle_img->image_x = $widthT;
											}
											
											$handle_img->file_new_name_body = $filenamemd5."_thumb";
											$handle_img->file_overwrite = true;
											$handle_img->Process($dir_dest);
										}
										
									}
									else  //devo fissare l'altezza
									{
										$heightB = $arrayDim[1];
										$heightT = $arrayDim[3];
										
										if($handle_img->image_src_y > $heightB)
										{
											$handle_img->image_resize = true;
											$handle_img->image_ratio_x = true;
											$handle_img->image_y = $heightB;
											$handle_img->file_overwrite = true;
											
											$handle_img->Process($dir_dest);
										}
										
										$handle_img->file_new_name_body = $filenamemd5;
										$handle_img->file_overwrite = true;
										$handle_img->Process($dir_dest);
										
										if($heightT>0)
										{
											if($handle_img->image_src_y > $heightT)
											{
												$handle_img->image_resize = true;
												$handle_img->image_ratio_x = true;
												$handle_img->image_y = $heightT;
											}
											
											$handle_img->file_new_name_body = $filenamemd5."_thumb";
											$handle_img->file_overwrite = true;
											$handle_img->Process($dir_dest);
										}
									}
									
									$handle_img -> Clean();
								}
							}
							else
							{ exit("error: il file caricato non è una immagine"); }
						}
						else  // sto caricando un file, non una immagine
						{ 
							$ext = $handle->file_src_name_ext;
							
							$handle->file_new_name_body = $filenamemd5;
							$handle->no_script = false;
							$handle->file_overwrite = true;
							$handle->Process($dir_dest);
						}

						//cancello il file caricato con il nome originale e faccio pulizia
						@unlink($dir_dest."/".$destname);
						$handle->Clean();
						
						
						$arr_file[$i] = $_FILES[$nomeCampoFile]["name"].":".$filenamemd5.".".$ext;
						$arr_etichette[$i] = $this->conn->str($_POST[$nomeCampoEtichetta]);
						
						//controllo se devo cancellare il file vecchio	
						if(isset($arrayFile[$i-1]) and $arrayFile[$i-1]["file"] <> "")
						{
							$expFile = explode(":", $arrayFile[$i-1]["file"]);
							
							if($expFile[1] <> $filenamemd5.".".$ext)
							{ $this->delete_file($expFile[1], $arrayFile[$i-1]["idF"]); }
						}
						
					/*	$etichetta = $_POST[$nomeCampoEtichetta];
						$this -> addFile($filenamemd5.".".$ext.":".$_FILES[$nomeCampoFile]["name"], $etichetta, $idR);
					*/
					}
					else
					{ exit ('error : ' . $handle->error); }
				
				}
				else  //non c'è il file da caricare. Devo controllare se si deve cancellare quello vecchio
				{
					if(isset($_POST[$nomeCampoCanc]) and $_POST[$nomeCampoCanc] == 'si')
					{
						if(trim($arrayFile[$i-1]["file"] <> ""))
						{ 
							$expFile = explode(":", $arrayFile[$i-1]["file"]);
							$this->delete_file($expFile[1], $arrayFile[$i-1]["idF"]); 
						}
					
						$arr_file[$i] = "";
						$arr_etichette[$i] = "";
					}
					else
					{
						if(isset($arrayFile[$i-1]))
						{
							$arr_file[$i] = $arrayFile[$i-1]["file"];
							$arr_etichette[$i] = $this->conn->str($_POST[$nomeCampoEtichetta]);
						}
						else
						{
							$arr_file[$i] = "";
							$arr_etichette[$i] = "";
						}
					}
				}
			}
			
			//inserisco i dati nella tbl file. Prima cancello gli eventuali precedenti
			$queryDel = sprintf("delete from file where fk_tabella = %d and fk_record = %d", $this->idTabella, $idR);
			$this->conn->query($queryDel);
			
			//var_dump($arr_file);
			
			$cont = 1;
			foreach($arr_file as $key=>$value)
			{
				if(trim($value != ""))
				{
					//inserisco nel db
					$queryIns = sprintf("insert into file  (file, fk_tabella, fk_record, titoloF, sorting) value('%s', %d, %d, '%s', %d)", $value, $this->idTabella, $idR, $arr_etichette[$key], $cont);		
					$this->conn->query($queryIns);
					$cont++;
				}
			}
		}
	}



	
	
	public function delete_recordfile($id){
		$return = false;
	
		$queryFile = sprintf("select * from file where fk_tabella = %d and fk_record = %d", $this->idTabella, $id);
		$this->conn->query($queryFile);
		$data = array();
		while($row_file = $this->conn->fetch())
		{ 
			$data[] = $row_file; 
		}		
				
		foreach($data as $arrFile)
		{
			$expFile = explode(":", $arrFile["file"]);
			$this->delete_file($expFile[1], $arrFile["idF"]); 
		}
		
		//cancello dalla tbl file
		$queryDel = sprintf("delete from file where fk_tabella = %d and fk_record = %d", $this->idTabella, $id);
		if($this->conn->query($queryDel))
		{ $return = true; }
		
		return $return;
	}
	

	public function delete_singlerecord($idFile){
		$return = false;
		
		//$queryF = "select * from file where idF = ".$idFile;
		//$this->conn->query($queryF);
		//$data = $this->conn->fetch();
		
		$arrRecord = $this->getById($idFile);		
		$expFile = explode(":", $arrRecord["file"]);
		
		$this->delete_file($expFile[1], $idFile);
		
		//aggiornamento sorting altri record
		$queryUp = "update file set sorting = (sorting-1) where sorting>".$arrRecord["sorting"] . " and fk_tabella = ".$arrRecord["fk_tabella"] . " and fk_record = ".$arrRecord["fk_record"];
		$this->conn->query($queryUp);
		
		
		//cancello dalla tbl file
		$queryDel = "delete from file where idF=".$idFile;
		if($this->conn->query($queryDel))
		{ $return = true; }
		
		return $return;
	}


	private function delete_file($md5file, $idFile){
		$query = "select * from file where file LIKE '%".$md5file."%' and fk_tabella = " . $this->idTabella . " and idF <> " . $idFile;
		$this->conn->query($query);
		$row = $this->conn->fetch();
		
		if($this->conn->rowCount() == 0)
		{
			//cancello il file
			$percorso = "../".$this->folder_upload."/".$this->get_cartella()."/".$md5file;
			 
			//devo controllare ed eventualmente cancellare anche la thumb e l'originale
			$nomefile = $this->get_nomefile($md5file);
			$path_parts = pathinfo($percorso);
		
			$percorsoThumb = "../".$this->folder_upload."/".$this->get_cartella()."/".$nomefile."_thumb.".$path_parts["extension"];
			$percorsoOrig =  "../".$this->folder_upload."/".$this->get_cartella()."/".$nomefile."_orig.".$path_parts["extension"];
			
			
			if(is_file($percorso))
			{ unlink($percorso);  }
			
			if(file_exists($percorsoThumb))
			{ unlink($percorsoThumb); } 
			
			if(file_exists($percorsoOrig))
			{ unlink($percorsoOrig); }
			
		}
		
		return true;
	}
	
	
	//funzione che restituisce il nome di un file, senza l'estensione
	public function get_nomefile($file){
		$file_name= basename($file);  //restituisce il nome del file
		
		$lunghezzastringa= strlen ($file_name);
		$posizionedelpunto = strpos($file_name,".");
		$nomefile=substr($file_name,0,$posizionedelpunto);
		
		return $nomefile;
	}
	
	
	public function get_tipofile($userfile_name){
		$file_name= basename($userfile_name);  //restituisce il nome del file
		
		$lunghezzastringa= strlen ($file_name);
		$posizionedelpunto = strpos($file_name,".");
		$nomefile=substr($file_name,0,$posizionedelpunto);
		$ncaratteri = ($lunghezzastringa - 1 - $posizionedelpunto);
		$tipofile=substr($file_name,$posizionedelpunto + 1,$ncaratteri);  //tipo del file caricato
		
		$tipofile = strtolower($tipofile);
		
		return $tipofile;
	}

	
	
	//funzione che cancella i file vecchi nella cartella temporanea
	public function cancella_file_old($directory, $data_limite)
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
			$pos_underscore = strrpos($file,"_")+1;
			$pos_punto = strrpos($file,".");
			
			$data_file = substr($file, $pos_underscore, ($pos_punto-$pos_underscore));
			if($data_file<=$data_limite)
			{
				unlink($directory.$file);
			}
		}
	}
		
	
	
	
	
	
	public function icona_file($filename)
	{
		$tipofile = $this->get_tipofile($filename);
		
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
	
}

?>