<?php

class MySql extends mysqli{
	private $host;
	private $username;
	private $password;
	private $database;
	private $query_result;
	
	public $last_query;
	public $page_dimension;
	public $fields;
	public $paging_totalrow;
	public $pages;
	public $hasNext;
	
	public function MySql(){
		$this->host = DB_HOST;
		$this->username = DB_LOGIN;
		$this->password = DB_PASSW;
		$this->database = DB_SCHEMA;
		$this->page_dimension = 50; //default value	
			
		parent::__construct($this->host, $this->username, $this->password, $this->database);
	}

	public function query($sql){
		$this->last_query = $sql;
		//DEBUG QUERY
		if( DEBUG_QUERY === true ){
			file_put_contents('php://stderr', $sql."\n");
		}
		$this->query_result = parent::query($sql);
		
		if(strtoupper(substr($sql, 0, 6)) == 'SELECT'){
			$this->row_affected = $this->rowCount();
			$num_fields = $this->field_count;
			for($index=0; $index<$num_fields; $index++){
				$field = $this->query_result->fetch_field_direct($index);
				
				$this->fields[$field->name] = $field->type;
			}
		}
		return $this->query_result;
	}

	public function query_paged($sql, $pageNumber){
		$this->last_query = $sql;
		$this->query($sql);
		$this->paging_totalrow = $this->rowCount();
		$this->hasNext = (intval(($this->row_affected-1) / $this->page_dimension)>$pageNumber)?true:false;
		$this->pages = intval($this->paging_totalrow / $this->page_dimension) + 1;
		$query = str_replace(";", "", $sql);
		$query = sprintf("%s LIMIT %d,%d;", $sql, $pageNumber*$this->page_dimension, $this->page_dimension);
		return $this->query($query);
	}
	
	function defaultValue($type){
		$ret = null;

		switch($type){
			case "int":
				$ret = 0;
				break;
			case "string":
			case "blob":
				$ret = "";
				break;
		}
		return $ret;
	}
	
	function escapeValue($type){
		switch($type){
			case "int":
				$ret = "";
			default:
				$ret = "'";
		}
		return $ret;
	}

	function escape($string){
		return parent::real_escape_string($string);
	}		

	public function rowCount(){
		return $this->affected_rows;
	}

	public function get_row(){
		return mysqli_fetch_row($this->query_result);
	}

	public function fetch(){
		if($this->query_result)
			return mysqli_fetch_assoc($this->query_result);
		else
			return array();
	}

	public function fetch_from_resource($query_result){
		if($query_result)
			return mysqli_fetch_assoc($query_result);
		else
			return array();
	}

	public function free(){
		return mysqli_free_result($this->query_result);
	}

	public function get_last_id(){
		return $this->insert_id;
	}
	
	//chiusura della connessione
	function Close(){
		mysqli_close();
	}		
}
