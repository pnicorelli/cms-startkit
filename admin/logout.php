<?php
require_once("../init.php");

$user = new UserAdminister();
$user->destroy_session();

header("Location: index.php?logout=si");
exit();

?>