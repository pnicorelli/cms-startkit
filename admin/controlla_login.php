<?php
require_once("initback.php"); 

$username = $ww->get("username"); 
$password = $ww->get("password"); 


if( $auth->login($username, $password, USE_COOKIE) ){
	header("Location: accesso.php");
	exit();
} else {
	header("Location: index.php?errore=login");
	exit();
}

?>
