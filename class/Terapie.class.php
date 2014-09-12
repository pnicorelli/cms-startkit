<?php
/*
 * Articles are records who represent a single page.
 */
 
class Terapie extends DbObject{
	
		public $table = "terapie";	
		public $tableid = "id";	
		public $images = null;	
		
		public function __construct($id = null){
			parent::__construct();
			if(!is_null($id) && $id>0) $this->getById($id);
		}


}
