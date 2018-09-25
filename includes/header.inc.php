<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
"http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<HTML>
<HEAD>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">

<script type='text/javascript' language='javascript' src='includes/js/jquery-1.6.1.min.js'></script>
<script type='text/javascript' language='javascript' src='includes/js/jquery.dataTables.min.js'></script>
<script type='text/javascript' language='javascript' src='includes/js/jquery.maskedinput-1.3.min.js'></script>
<script type='text/javascript' language='javascript' src='includes/js/jquery.colorbox.js'></script>


<link rel="stylesheet" type="text/css" href="includes/stylesheet.css"></link>

<TITLE>IGB People Database</TITLE>


</HEAD>

<BODY>

<script>


$(document).ready(function(){			   
  
  $("a#directory").click(function(){
  $("ul#directory").toggle();
  });
  
  $("a#admin").click(function(){
  $("ul#admin").toggle();
  });

  $("#uin").mask("999999999");
  $(".phone").mask("(999) 999-9999");
  

});


</script>


<?php

if(!isset($_SERVER['HTTPS']) || $_SERVER['HTTPS'] == ""){
    $redirect = "https://".$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];
    header("HTTP/1.1 301 Moved Permanently");
    header("Location: $redirect");
}

include_once 'includes/main.inc.php';
date_default_timezone_set('America/Chicago');
error_reporting(E_ERROR | E_WARNING | E_PARSE);



//error_reporting(E_ALL);
/*ini_set('display_errors', '1');


*/


$header_html = "<div id='container'>
				<div id='header'>
				<br><center>IGB People Database</center>
				</div>
				<div id='content' class='left'>
				<ul>
					<li><a href='index.php'>Home</a></li>
					<li><a href='add.php'>Add</a></li>
					<li><a href='search.php'>Search</a></li>
					<li><a id='directory' href='' onclick='return false;'>Directory</a>
						<ul id='directory'>
							<li id='directory'>
								<a href='alphadir.php' >Alpha</a>
							</li>
							<li id='directory'>
								<a href='themedir.php' >Theme</a>
							</li>
		
						</ul>
					</li>".
                                        (($_SESSION['admin_id'] != 0) ? 
					("<li><a id='admin' href='' onclick='return false;'>Admin</a>
						<ul id='admin'>
							<li id='admin'>
								<a href='theme_edit.php' >Manage Themes</a>
							</li>
							<li id='admin'>
								<a href='type_edit.php' >Manage Types</a>
							</li>
							<li id='admin'>
								<a href='key_edit.php' >Manage Key Rooms</a>
							</li>
							<li id='admin'>
								<a href='dept_edit.php' >Manage Departments</a>
							</li>
		
						</ul>
					</li>".
                                        "<li id='reports'><a href='reports.php'>Reports</a></li>")
                                        : "") .
					"<li><a href='logout.php'>Log Out</a></li>  
				</ul>
				</div>
				<div id='content'>";
		
echo $header_html;

?>



