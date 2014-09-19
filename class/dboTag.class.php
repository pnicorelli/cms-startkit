<?php
class dboTag extends DbObject{
 
 public $table = "tag";
 public $page_dimension = "";


 public function __construct($id = null){
  parent::__construct();
  if(!is_null($id)) $this->getById($id);
  
  //$this->conn->page_dimension=2;
  
 }

}
?>