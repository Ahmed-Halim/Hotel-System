<?php
//remove sessions and cookies then redirect user to home page
session_start();
session_unset();
session_destroy();
setcookie("user_id", NULL, time() - 3600, "/");
header( "refresh:0;url=home.php" );

?>
