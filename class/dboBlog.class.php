<?php
class dboBlog extends DbObject{
 
 public $table = "blog";

 public function __construct($id = null){
  parent::__construct();
  if(!is_null($id)) $this->getById($id);
  
 }

}
?>