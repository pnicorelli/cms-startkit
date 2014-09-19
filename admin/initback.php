<?php
require_once("../init.php");


if(!isset($_SESSION["admin".NOME_SESSIONE]) or $_SESSION["admin".NOME_SESSIONE] == "")
{
	header("Location: index.php");
	exit();
}
?>