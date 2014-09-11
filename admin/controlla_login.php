<?php
require_once("../init.php"); 

$username = str_replace ("'", "&#39;", $_POST["username"]); 
$password = str_replace ("'", "&#39;", $_POST["password"]); 

$user = new Users();
$check = $user->exist($username, $user->wwcrypt($password));


if($check)
{
	header("Location: index.php?errore=login");
	exit();
}
else
{
	$user->create_session($idLogin);
	header("Location: accesso.php");
	exit();
}
?>
