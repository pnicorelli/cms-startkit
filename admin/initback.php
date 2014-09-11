<?php
error_reporting(E_ALL);

require_once("../init_noauth.php");


if(!isset($_SESSION["admin".NOME_SESSIONE]) or $_SESSION["admin".NOME_SESSIONE] == "")
{
	header("Location: index.php");
	exit();
}
?>
