<?php
//ini_set("display_errors", "1");
//error_reporting(E_ALL);
date_default_timezone_set("Europe/Rome");
define("ROOT_APATH", dirname(__FILE__)."/");
require_once("config/config.php");
require_once("config/db.php");

if(!isset($_SESSION)){
	session_start();
}

// Class Autoloading
function __autoload($class_name) {
	require_once ROOT_APATH."class/".$class_name.".class.php";
}

// DB Virtualizzation
eval("class DB extends ". DB_TYPE . " { }");

$ww = new MainController($_SERVER["PHP_SELF"]);

?>
