<?php
error_reporting(E_ALL);

require_once("../init.php");


if( $ww->page != "index.php" && $auth->isAuth() === false){
		header("location: index.php");
}
?>
