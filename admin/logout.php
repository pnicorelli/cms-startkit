<?php
require_once("../init.php");

$auth->logout();

header("Location: index.php?logout=si");
exit();

?>
