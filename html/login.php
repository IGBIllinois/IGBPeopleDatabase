<?php #login_page

$page_title = "IGB People Database Search"; 

require_once 'includes/main.inc.php';


if(!isset($_SERVER['HTTPS']) || $_SERVER['HTTPS'] == ""){
    $redirect = "https://".$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];
    header("HTTP/1.1 301 Moved Permanently");
    header("Location: $redirect");
}

 
$session = new session(__SESSION_NAME__);
$message = "";
$webpage = $dir = dirname($_SERVER['PHP_SELF']) . "/index.php";
if ($session->get_var('webpage') != "") {
	$webpage = $session->get_var('webpage');
}

if (isset($_POST['login'])) {

	$username = trim(rtrim($_POST['username']));
	$password = $_POST['password'];

	$error = false;
	if ($username == "") {
		$error = true;
		$message .= html::error_message("Please enter your username.");
	}
	if ($password == "") {
		$error = true;
		$message .= html::error_message("Please enter your password.");
	}
	if ($error == false) {
		$login_user = new login_user($ldap,$username);
		$success = $login_user->authenticate($password);
                $user = new user($db);

                $admin_id=0;
                if($user->is_admin($username)) {
                    $admin_id = $user->is_superadmin($username);

                }

		if ($success==0) {
			$session_vars = array('login'=>true,
                'username'=>$username,
                'timeout'=>time(),
                'admin_id'=>$admin_id,
                'ipaddress'=>$_SERVER['REMOTE_ADDR']
        	);
            $session->set_session($session_vars);
            $ldap->set_bind_user($login_user->get_user_rdn());
            $ldap->set_bind_pass($password);


		$location = "https://" . $_SERVER['SERVER_NAME'] . $webpage;
        	header("Location: " . $location);
		}
		else {
			$message .= html::error_message("Invalid username or password. Please try again.");
		}
	}
}

?>





<HTML>
<HEAD>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">

<script type='text/javascript' language='javascript' src='includes/js/jquery-1.6.1.min.js'></script>
<script type='text/javascript' language='javascript' src='includes/js/jquery.dataTables.min.js'></script>
<script type='text/javascript' language='javascript' src='includes/js/jquery.maskedinput-1.3.min.js'></script>
<script type='text/javascript' language='javascript' src='includes/js/jquery.colorbox.js'></script>

<script type='text/javascript' language='javascript' src='includes/js/script.js'></script>

<link rel="stylesheet" type="text/css" href="includes/stylesheet.css"></link>

<TITLE>IGB People Database</TITLE>


</HEAD>

<?php 



$login_html = "";
$login_html .= "<div id='container'>
				<div id='header'>
				<br><center>IGB People Database</center>
				</div>
				<div id='content' class='left'>

				</div>
				<div id='content'>";

$login_html .= "<h1> IGB Database Login </h1>
	  <br>
	  <form id='login' action='login.php' method='post' name='login'>
	  <div class='login'>
	  
		  <table class='medium'>
			<tr>
				<td class='xs'>    
					<label class='required' style='font-size:12px'>Name </label> 
				</td>
				<td class='noborder'>    
					<input type='large' name='username' maxlength='50'
					value='";
					
	 if (isset($username)){$login_html .= $username ;}
$login_html .= "' >
				</td>  
		
			</tr>
			<tr>
				<td class='xs'>
					<label class='required' style='font-size:12px'>Password</label> 
				</td>    
				<td class='noborder'>
					<input type='password' name='password' maxlength='50'>
					
				</td>      
		
			</tr>
			<tr>
				<td class='xs'>
				</td>    
				<td class='noborder'><label class='errormsg'>". $message ."</label>
				</td>      
		
			</tr>
		  </table>
	  <br />
	  
	  <div class='alignright'>
				  <input type='submit' name='login' value='Log In' class='btn'>
			  </div>
	  </div>	</form>";





echo "<body onLoad=\"document.login.username.focus()\">"; 


		echo $login_html;

?>



 
<?php 

require_once ("includes/footer.inc.php"); 

?> 
