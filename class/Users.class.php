<?php
class Users extends DbObject{
	
	public $table = "users";
	public $tableid = "id";

	public function __construct($id = null){
		parent::__construct();
		if(!is_null($id) && $id>0) $this->getById($id);
	}

	public function wwcrypt($valore){
		return md5(sha1($valore));
	}





}

?>
