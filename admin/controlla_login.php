<?php
require_once("../init.php"); 

$username = $ww->get("username");
$password = $ww->get("password");

$user = new UserAdminister();
$idLogin = $user->do_login($username, $password);


if($idLogin==0)
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