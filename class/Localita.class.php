<?php
class Localita extends DbObject{
 
 public $table = "localita";
 public $tableid = "id";

 public function __construct($id = null){
  parent::__construct();
  if(!is_null($id)) $this->getById($id);
 }

}
?>