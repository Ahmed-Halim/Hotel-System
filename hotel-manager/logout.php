<?php

session_start();
session_unset();
session_destroy();
setcookie("user_id", NULL, time() - 3600, "/cookies/");
header( "refresh:0;url=../home.php" );

?>
