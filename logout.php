<?php
//////////////////////////////////////////
//                                      //
//      logout.php                      //
//										//
//////////////////////////////////////////

include 'includes/main.inc.php';
session_destroy();
header("Location: login.php")

?>
 