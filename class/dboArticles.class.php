<?php
/*
 * Articles are records who represent a single page.
 */
 
class dboArticles extends DbObject{
	
		public $table = "articles";	
		public $tableid = "id";	
		public $images = null;	
		
		function __construct(){
			parent::__construct();
		}	
	
		function getBySlug($slug){
			return $this->getBy("slug", $slug);
		}

}
