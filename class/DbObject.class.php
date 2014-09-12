<?php

Class DbObject{
	
	public $data;
	public $item;
	public $fields;	
	
	public $conn;
	
	public $table	= "";
	public $tableid	= "id";
	
	public $pages;
	public $pagesize;
	
	function __construct(){
		$this->table = DB_PREFIX.$this->table;
		$this->conn = new Db();
		$this->data = array();
		$this->pages = 0;
		$this->setDbStructure();
		$this->setItemDefault();
		
	}
	
	function setDbStructure(){
		$query = "SELECT * FROM ".$this->table." WHERE 1=2 ";
		if($this->conn->query($query))
			$this->fields = $this->conn->fields;		
		else
			$this->fields = array();
	}
	
	function setItemDefault(){
		$this->item = array();
		foreach($this->fields as $name => $type){
				$this->item[$name] = $this->conn->defaultValue($type);
		}		
	}

	function exist($field, $id){
		$result = false;
		$query = "SELECT * FROM ".$this->table." WHERE $field = '$id';";
		
		$this->conn->query($query);
		if($row = $this->conn->fetch()){
			$result = true;
		}
		return $result;				
	}

	function exist_ext($where){
		$result = false;
		$query = "SELECT * FROM ".$this->table." WHERE $where;";
		
		$this->conn->query($query);
		if($row = $this->conn->fetch()){
			$result = true;
		}
		return $result;				
	}
	
	function update($field_name, $row_id, $newvalue){
		$newvalue = $this->conn->str($newvalue);
		$query = "UPDATE ".$this->table." SET $field_name = '$newvalue' WHERE ".$this->tableid."=$row_id";
		$this->conn->query($query);
	}

	function delete($id, $field = null){
		if(is_null($field)){
			$field = $this->tableid;
		}
		$query = "DELETE FROM ".$this->table." WHERE `$field`='$id'";
		$this->conn->query($query);
	}	
	
	function getPage($options = "1=1", $order = "", $page=0){
		$options = (strlen(trim($options))>0)?$options:"1=1";
		$this->data = array();
		$query = "SELECT * FROM ".$this->table." WHERE $options $order";
		
		$this->conn->query_paged($query, $page);
		$this->pages = $this->conn->pages;
		while($row = $this->conn->fetch()){
			$this->data[] = $row; 
		}
		return $this->data;		
	}

		
	function getArrayId($options = ""){
		$this->data = array();
		$query = "SELECT {$this->tableid} FROM {$this->table} WHERE 1=1 $options";
		$this->conn->query($query);
		while($row = $this->conn->fetch()){
			$this->data[] = $row[$this->tableid]; 
		}
		return $this->data;		
	}
			
	function getAll($options = ""){
		$this->data = array();
		$query = "SELECT * FROM ".$this->table." WHERE 1=1 $options";
		$this->conn->query($query);
		while($row = $this->conn->fetch()){
			$this->data[] = $row; 
		}
		return $this->data;		
	}


	function getById($id){
		return $this->getBy($this->tableid, $id);
	}

	function getBy($field, $value, $options=""){
		$this->item = array();
		if($options!=""){
				$options = " ORDER BY ".$options;
		}
		$query = "SELECT * FROM ".$this->table." WHERE (`$field`='$value') $options";

		$this->conn->query($query);
		if($this->conn->rowCount()>0)
			$this->item = $this->conn->fetch();
		return $this->item;		
	}
	
	function save(){
		if(array_key_exists($this->tableid, $this->item) && $this->item[$this->tableid] != ""){
			$query = $this->sqlUpdate();
			$this->conn->query($query);
		} else {
			$query = $this->sqlInsert();
			$this->conn->query($query);
			$this->item[$this->tableid] = $this->conn->get_last_id();
		}
		$this->getById($this->item[$this->tableid]);
	}
	
	function sqlUpdate(){
		$separator = $fields = $values = "";
		foreach($this->fields as $field => $type){
			if($field != $this->tableid){
				$escape = $this->conn->escapeValue($type);
				$fields .= "$separator`$field`=$escape".$this->conn->escape($this->item[$field])."$escape";
				$separator = ", ";
			}
		}
		$sql = sprintf("UPDATE %s SET %s WHERE %s = %s;", $this->table, $fields, $this->tableid, $this->item[$this->tableid]);
		return $sql;
	}	
	
	function sqlInsert(){
		$separator = $fields = $values = "";
		foreach($this->fields as $field => $type){
			if(array_key_exists($field, $this->item)){
				$escape = $this->conn->escapeValue($type);
				$fields .= "$separator`$field`";
				$values .= "$separator$escape".$this->conn->escape($this->item[$field])."$escape";
				$separator = ", ";
			}
		}
		$sql = sprintf("INSERT INTO ".$this->table." (%s) VALUES (%s);", $fields, $values);	
		//echo $sql;
		return  $sql;
	}
  
	function getItem($field){
		return isset($this->item[$field])?$this->item[$field]:"";
	}
	
	function str($field){
		return html_entity_decode( $this->getItem($field), ENT_QUOTES, CHARSET);
	}
	
	function sortingNew($filter, $sorting_field="sorting"){
		$query = "SELECT MAX({$sorting_field})+1 as max_value FROM {$this->table} WHERE 1=1 AND {$filter} ";
		$this->conn->query($query);
		$res = $this->conn->fetch();
		return $res["max_value"];
	}
	
	/*
	 *  id: entity_id
	 *  action: 'up' or 'down' //actually up or else
	 *  filter: eg  language='italian'... used to filter some value of the table
	 *  sorting_field: the column name
	 */
	function sortingMove($position, $action, $filter="true", $sorting_field="sorting")
	{
		$sort = ($action=="up")?"DESC":"ASC";
		$operand = ($action=="up")?"<=":">=";
		
		$query = "SELECT * FROM ".$this->table." WHERE {$sorting_field} {$operand} {$position} AND {$filter} ORDER BY {$sorting_field} {$sort} LIMIT 0,2";
		$this->conn->query($query);
		while( $row = $this->conn->fetch()){
			$array[] = $row;
		}

		if( count($array) == 2 ){
			$first  = "UPDATE {$this->table} SET {$sorting_field} = ".$array[1][$sorting_field]." WHERE {$this->tableid} = ".$array[0][$this->tableid];
			$second  = "UPDATE {$this->table} SET {$sorting_field} = ".$array[0][$sorting_field]." WHERE {$this->tableid} = ".$array[1][$this->tableid];

		}

		$this->conn->query($first);
		$this->conn->query($second);
	}	
}
?>
