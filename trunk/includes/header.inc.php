<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
"http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<HTML>
<HEAD>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
<script type='text/javascript' language='javascript' src='libs/jquery-1.6.1.min.js'></script>
<script type='text/javascript' language='javascript' src='libs/jquery.dataTables.min.js'></script>
<script type='text/javascript' language='javascript' src='libs/jquery.maskedinput-1.3.min.js'></script>
<script type='text/javascript' language='javascript' src='libs/jquery.colorbox.js'></script>


<link rel="stylesheet" type="text/css" href="includes/stylesheet.css"></link>

<TITLE>IGB People Database</TITLE>


</HEAD>

<BODY>

<script>
/*
jQuery(function($){
   $("#date").mask("99/99/9999");
   $("#phone").mask("(999) 999-9999");
   $("#tin").mask("99-9999999");
   $("#ssn").mask("999-99-9999");
});
*/

$(document).ready(function(){			   
  
  $("a#directory").click(function(){
  $("ul#directory").toggle();
  });
  
  $("a#admin").click(function(){
  $("ul#admin").toggle();
  });

  $("#uin").mask("999999999");
  $(".phone").mask("(999) 999-9999");
  
  /*$('#input').click(function() {
	$(this).setCursorPosition(1);
  });*/

});


</script>


<?php
/*
if(!isset($_SERVER['HTTPS']) || $_SERVER['HTTPS'] == ""){
    $redirect = "https://".$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];
    header("HTTP/1.1 301 Moved Permanently");
    header("Location: $redirect");
}
*/
include_once 'includes/main.inc.php';
date_default_timezone_set('America/Chicago');
error_reporting(E_ERROR | E_WARNING | E_PARSE);

//error_reporting(E_ALL);
/*ini_set('display_errors', '1');

<li><a id='account' href='' onclick='return false;'>Account</a>
					
						<ul id='account'>
		
							<li>
								<a href='profile.php?user_id=".$user_id."' >Profile</a>
							</li>
							<li>test</li>
		
						</ul>
					</li>

					<li><a id='account' href='' onclick='return false;'>Account</a>
					
						<div id='account'>
						<ul id='account'>
							<li>
								<a href='search.php' >Profile</a>
							</li>
							<li>test</li>
		
						</ul>
						</div>
					</li>

*/


$header_html = "<div id='container'>
				<div id='header'>
				<br><center>IGB People Database - Testing</center>
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
					</li>")
                                        : "") .
					"<li><a href='logout.php'>Log Out</a></li>  
				</ul>
				</div>
				<div id='content'>";
		
echo $header_html;

?>



