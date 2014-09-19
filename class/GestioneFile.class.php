<?php

class GestioneFile extends DbObject {
	
	public $table = "gestione_tabelle";
	public $tableid	= "idGT";
	
	private $tabella;
	public $idTabella;
	private $campo;
	public $folder_upload=UPLOAD_FILE;
	
	public $objFile;
	
	public function __construct($nomeTbl, $campo){
		parent::__construct();
		 
		$this->objFile = new File();
		 
		$this->tabella = $nomeTbl;
		$this->campo = $campo;
		
		$this->idTabella = $this->getIdTabella();
		$this->getById($this->idTabella);
	}
	
	
	public function getIdTabella() {
		
		 $arrTbl = $this->getAll("and tabella='".$this->tabella."' and campo = '".$this->campo."'");
		 return $arrTbl[0]["idGT"];
	}
	
	
	
	public function campi_vuoti(){
		$quantiFile = $this->item["n_file"];
		$etichettaTbl = $this->item["etichetta"];
		
		$template = file_get_contents(ROOT_APATH."admin/template_file/new_".$this->item["upload_template"].".html");
		$template = str_replace("{{ETICHETTA}}", $etichettaTbl, $template);
		
		$strDetail = "";
		
		if($this->item["upload_template"] == "basic")
		{
			for($i=1; $i<=$quantiFile; $i++)
			{
				$strDetail .= $template;				
				$strDetail = str_replace("{{NAME_ETICHETTA}}", "et_".$this->idTabella."_".$i, $strDetail);
				$strDetail = str_replace("{{FILENAME}}", "file_".$this->idTabella."_".$i, $strDetail);				
			}
		}
		
		return $strDetail;
	}


	


	public function campi_dett($idRecord, $tmp=""){
	
		$quantiFile = $this->item["n_file"];
		$etichettaTbl = $this->item["etichetta"];
		$cartella_upload = $this->item["cartella_upload"];
		
		$percorso = LINK_ROOT.$this->folder_upload."/".$cartella_upload."/";
		
		$arrFile = $this->objFile->get_file($idRecord, $this->idTabella);
		
		$strDetail = "";
		
		$template = file_get_contents(ROOT_APATH."admin/template_file/detail_".$this->item["upload_template"].".html");
		
		$template = str_replace("{{ETICHETTA}}", $etichettaTbl, $template);
		
		
		for($i=1; $i<=$quantiFile; $i++)
		{
			$return_tbl = "";
			$strDetail .= $template;
			
			if($this->item["upload_template"] == "basic")
			{
			
				$strDetail = str_replace("{{NAME_ETICHETTA}}", "et_".$this->idTabella."_".$i, $strDetail);
				
				$strDetail = str_replace("{{FILENAME}}", "file_".$this->idTabella."_".$i, $strDetail);
								
				if(isset($arrFile[($i-1)]) and $arrFile[$i-1] <> "")
				{
					$strDetail = str_replace("{{VALUE_ETICHETTA}}", $arrFile[$i-1]["titoloF"], $strDetail);
					$array_file = explode(":",$arrFile[$i-1]["file"]);
					
					$return_tbl .= "<a href='".$percorso.$array_file[1] ."' target='_blank'>" . $array_file[0] . "</a><br />";
					$return_tbl .= "<input type='checkbox' value='si' name='canc_".$this->idTabella."_".$i."'> cancella il file attuale";
					$return_tbl .= "<input type='hidden' name='idF_".$this->idTabella."_".$i."' value='".$arrFile[$i-1]["idF"]."'>";
				}
				else
				{
					$strDetail = str_replace("{{VALUE_ETICHETTA}}", "", $strDetail);
					$return_tbl .= " nessuno";
				}
	
				$strDetail  = str_replace("{{FILE}}", $return_tbl, $strDetail);
			}
			elseif($this->item["upload_template"] == "crop")
			{
				
				if(isset($arrFile[($i-1)]) and $arrFile[$i-1] <> "")
				{
					$arr_img = explode(":", $arrFile[($i-1)]["file"]);
					$nomeFileImg = dimminomefile($arr_img[1]);
					$tipoFile = tipo_file($arr_img[1]);
					$nomeThumb = $nomeFileImg . "_thumb." . $tipoFile;
				
					$return_tbl .= "<a href='".$percorso.$arr_img[1]."' target='_blank'><img src='".$percorso.$nomeThumb."' border=0></a>";
					$return_tbl .= "<br /><input type='checkbox' value='si' name='canc_".$this->idTabella."_".$i."'> cancella la foto attuale";
					$return_tbl .= "<input type='hidden' name='idF_".$this->idTabella."_".$i."' value='".$arrFile[$i-1]["idF"]."'>";
				}
				else
				{
					$return_tbl .= "<a href=\"#\" onClick=\"apri_upload(".$idRecord.", '".$this->idTabella."-".$tmp."');\">carica un'immagine</a>";
				}		
				
				$strDetail  = str_replace("{{FILE}}", $return_tbl, $strDetail);	
			}
			
		}
		
		return $strDetail;
		
		
		
	}
	
		
}

?>