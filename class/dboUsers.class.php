<?php

class dboUsers extends DbObject{
	
		public $table = "users";	
		public $tableid = "id";	
		
		public $uid;
		public $hash;
		
		function dboUsers(){
			parent::__construct();
			$this->uid = null;
			$this->hash = null;			
		}	
	
		function do_login($username, $password){
			$exist = false;
			$password = $this->cryptopwd($password);
			if( $this->exist_ext("  `username` = '{$username}' AND `password` = '{$password}'") ){
				$data = $this->getArrayId(" AND `username` = '{$username}' AND `password` = '{$password}'");
				$this->uid = $data[0];
				$this->hash = $this->cryptopwd($username.$password.$this->uid);
				
				$exist = true;
			}
			return $exist;
		}
		
		function validate_hash($id, $hash){
			$result = null;
			$query = "SELECT username, password FROM {$this->table} WHERE {$this->tableid} = {$id};";
			$this->conn->query($query);
			$item = $this->conn->fetch();
			$calc_hash = $this->cryptopwd($item["username"].$item["password"].$id);
			if($hash == $calc_hash){
				$result = true;
			}
			
			return $result;
		}
		
		private function cryptopwd($string){
			return md5( sha1($string) );
		}
}
