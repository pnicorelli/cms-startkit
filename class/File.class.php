<?php

class File extends DbObject {
	
	public $table = "file";
	public $tableid	= "idF";
	
	public $folder_upload=UPLOAD_FILE;
	
	public function __construct($id = null){		 
	   parent::__construct();
	   if(!is_null($id)) $this->getById($id);
	}
	
	


	public function get_file($idRecord, $idTbl){
		//recupero i file già inseriti
		$arrFile = $this->getAll("and fk_tabella='".$idTbl."' and fk_record = ".$idRecord." order by sorting");

		return $arrFile;
	}

	
	
	
	
	public function uploadFile($idRecord, $nomeTbl, $campo)
	{	
		$objGestioneFile = new GestioneFile($nomeTbl, $campo);
		$nFile = $objGestioneFile->item["n_file"];
		
		$dir_dest = ROOT_APATH.$this->folder_upload."/".$objGestioneFile->item["cartella_upload"];
		
		for($i=1; $i<=$nFile; $i++)
		{	
			$field = "file_".$objGestioneFile->idTabella."_".$i;
		
			
			if(isset($_FILES[$field]) and $_FILES[$field]["size"]>0)
			{	
				$foo = new Upload($_FILES[$field]);
				$foo->Process($dir_dest);

			
				if ($foo->processed) {
					echo 'original image copied';
				  } else {
					echo 'error : ' . $foo->error;
				  }			
  			}
			else
			{ print $_FILES[$field]["size"]; }
			
		}
		
	}



	public function get_anteprimafoto($idFoto)
	{
		$cartellaUp = $this->get_cartella();
		$percorso = LINK_ROOT.UPLOAD_FILE."/".$cartellaUp;
	
		$queryF = "select * from file where idF=".$idFoto;
		$this->conn->query($queryF);
		
		if($this->conn->rowCount()>0)
		{
			$arr = $this->conn->fetch();
			$arrayFoto = explode(":",$arr["file"]);
			return "<img src='".LINK_ROOT."funzioni/ridimensiona_img.php?nome_file=".$percorso."/".$arrayFoto[1]."&baseThumb=70&hThumb=70' border='0' width=70>";
		}
		else
		{
			return "";
		}
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