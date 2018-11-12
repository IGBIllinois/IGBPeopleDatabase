<?php
//////////////////////////////////////////
//                                      //
//      logout.php                      //
//										//
//////////////////////////////////////////

require_once 'includes/main.inc.php';
$session = new session(__SESSION_NAME__);
$session->destroy_session();
header("Location: login.php");

?>
 