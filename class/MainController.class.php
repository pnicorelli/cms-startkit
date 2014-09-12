<?php


Class MainController{
	public $pageparams;
	public $siteoptions;
	public $page;
			
	function MainController($page="/"){
		$this->page = basename($page);
		$this->pageparams = "";
		$amp = "";
		foreach($_REQUEST as $key => $value){
			$value = $this->get($key);
			$this->pageparams .= "$amp$key=$value";
			$amp = "&amp;";
		}
	}

	/* Get a value from GET or POST */
	function get($field, $default = null){
		$return = "";
		$return = isset($_REQUEST[$field])?$_REQUEST[$field]:$default;
		$return = @htmlentities($return, ENT_QUOTES, CHARSET);
		return $return;
	}

	/* Get a integer from GET or POST */
	function getInt($field, $default = 0){
		$return = "";
		$return = isset($_REQUEST[$field])?$_REQUEST[$field]:$default;
		$return = intval($return);
		return $return;
	}
	
	/* Get a email from GET or POST */
	function getEmail($field, $default = ""){
		$return = isset($_REQUEST[$field])?$_REQUEST[$field]:$default;
		if(!filter_var($return, FILTER_VALIDATE_EMAIL)){
			$return = $default;
		}
		return $return;
	}
	
		
	function dump($obj){
		echo "<pre>";
		var_dump($obj);
		echo "</pre>";
	}
	
	function printError($error){
		?><div class="error"><?php echo $error; ?></div><?php
	}
	
	function error($error){
		if(is_array($error)){
				$error = json_encode($error);
		}
		$error = NOME_SITO." - ".date("Y-m-d_H:i:s")." - ".$error." ";
		file_put_contents('php://stderr', $error."\n");
	}
	
	
	function dateITA($date){
		if(is_null($date))
		{
			return "//";
		} else {
			$date_obj = new DateTime($date);
			return $date_obj->format('d/m/Y');
		}
	}
	
	function dateITAtoTIMESTAMP($date){
		if(is_null($date))
		{
			return "1970-01-01 00:00:00";
		} else {
			$date_obj = DateTime::createFromFormat('d/m/Y', $date);
			return $date_obj->format('Y-m-d H:i:s');
		}
	}

	
	function csvToArray($csv, $comma = ","){
		$csv = explode($comma, $csv);
		$csv = array_map("ltrim", $csv);
		$csv = array_map("rtrim", $csv);
		$csv = array_filter($csv, "strlen");
		$csv = array_values($csv);
		return $csv;
	}
	
	function str($text){
		return html_entity_decode( $text, ENT_QUOTES, CHARSET);
	}
}
?>
