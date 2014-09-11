<?php

class Auth{
	private $is_auth;
	
		function Auth(){
			$this->check_for_session();
		}
		
		function check_for_session(){
			$this->is_auth = false;
			if( isset( $_SESSION["uid"] ) OR isset( $_COOKIE["uid"] ) ){
				if( $this->validate_session() ){
					$this->is_auth = true;
				} else {
					$this->logout();
				}
			}
		}
		
		function login($username, $password, $use_cookie = false){
			$return = false;
			$u = new dboUsers();
			if( $u->do_login($username, $password) ){
				$_SESSION["uid"] = $u->uid;
				$_SESSION["hash"] = $u->hash;
				if($use_cookie){
					setcookie('uid', $u->uid, time()+60*60*24*30);
					setcookie('hash', $u->hash, time()+60*60*24*30);					
				}
				$return = true;
			}
			return $return;
		}
		
		function validate_session(){
			$return = false;
			if(isset($_COOKIE["uid"])){
				$_SESSION["uid"] = $_COOKIE["uid"];
				$_SESSION["hash"] = $_COOKIE["hash"];
			}
			/* validate session with user table*/
			$u = new dboUsers();
			$uid = isset($_SESSION["uid"])?$_SESSION["uid"]:0;
			$hash = isset($_SESSION["hash"])?$_SESSION["hash"]:0;
			if( $u->validate_hash($uid, $hash) ){
				$return = true;
			}
			return $return;
		}
		
		function logout(){
			$_SESSION = array();
		
			if(isset($_COOKIE["uid"])){
				setcookie('uid', 0, time()-3600);
				setcookie('hash', 0, time()-3600);
			}
		}
		
		function isAuth(){
			return $this->is_auth;
		}
		
		function getGroup(){
			$u = new dboUsers();
			$u->getById( $_SESSION["uid"] );
			return $u->item["group"];
		}
}
