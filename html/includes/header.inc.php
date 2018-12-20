<?php
	require_once 'main.inc.php';
        require_once 'session.inc.php';
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
"http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<HTML>
<HEAD>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">


<script type='text/javascript' language='javascript' src='vendor/components/jquery/jquery.min.js'></script>
<script type='text/javascript' language='javascript' src='vendor/datatables/media/js/jquery.dataTables.min.js'></script>

<script type='text/javascript' language='javascript' src='vendor/components/jquery.maskedinput/jquery.maskedinput.min.js'></script>
<script type='text/javascript' language='javascript' src='vendor/components/colorbox/jquery.colorbox.js'></script>

<script type='text/javascript' language='javascript' src='includes/js/script.js'></script>

<link rel="stylesheet" type="text/css" href="includes/stylesheet.css"></link>

<TITLE>IGB People Database</TITLE>


</HEAD>

<BODY>

<?php

date_default_timezone_set('America/Chicago');
error_reporting(E_ERROR | E_WARNING | E_PARSE);

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



