<?php

class UserAdminister {
	private $conn;

	public function __construct(){
		$this->conn = new Db();
	}


	public function do_login($username, $password){
		$result = 0;
		$query = "SELECT * FROM utenti_admin WHERE ut_username='".$username."' AND ut_password='".$this->wwcrypt($password)."'";
		
		
		$this->conn->query($query);
		if($this->conn->rowCount()>0){
			$row = $this->conn->fetch();
			$result = $row["ut_id"];
		}
		
		
		return $result;
	}


	public function create_session($id){
		$result = false;
		$query = "SELECT * FROM utenti_admin WHERE ut_id='".$id."'";
		$this->conn->query($query);
		if($this->conn->rowCount()>0){
			$row = $this->conn->fetch();
			$_SESSION["admin".NOME_SESSIONE] = "ok";
			$_SESSION["admin_user_id"] = $row["ut_id"];
		}
		return $result;
	}

	public function destroy_session(){
		unset($_SESSION["admin".NOME_SESSIONE]);
		unset($_SESSION["admin_user_id"]);
		session_unset();
		return true;
	}

	public function wwcrypt($valore){
		return md5(sha1($valore));
	}


	
}
?>
