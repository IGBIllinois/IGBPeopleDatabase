<?php #login_page

$page_title = "IGB People Database Search"; 

include_once 'includes/header.inc.php'; 


if (isset($_SESSION['webpage'])) {
        $webpage = $_SESSION['webpage'];
}
else {
        $dir = dirname($_SERVER['PHP_SELF']);

        $webpage = $dir . "/index.php";
}

$message = "";

if (isset($_POST['login'])) {
        
        $username = $_POST['username'];
        $password = $_POST['password'];
        $username = trim(rtrim($username));
        $error = false;
        if ($username == "") {
                $error = true;
                $message .= "Please enter your NetID<br>";
        }
        if ($password == "") {
                $error = true;
                $message .= "Please enter your password<br>";
        }
        if ($error == false) {
			
			$connect;
			$connect = ldap_connect(ldap_host) or die ("can't connect");
			$bindDN = "uid=" . $username . "," . ldap_people_ou;
			$success = ldap_bind($connect, $bindDN, $password) ;
			if ($success){	
						$user = new user($db);
                                                $admin_id=0;
                                                if($user->is_admin($username)) {
                                                    $admin_id = $user->is_superadmin($username);
                                                    
                                                }

						 
								session_destroy();
								session_start();
								
								$_SESSION['username'] = $username;
								$_SESSION['admin_id'] = $admin_id;
								$_SESSION['admin'] = TRUE;
										
							  $location = "https://" . $_SERVER['SERVER_NAME'] . ($_SERVER['SERVER_PORT'] == '80' ? '' : ":".$_SERVER['SERVER_PORT']) . $webpage;
							  header("Location: " . $location);
						
				
			}
			else{
				$message = "Invalid username or password";	
			}

        }       
}

?>






<?php 



$login_html = "";

$login_html .= "<h1> IGB Database Login </h1>
	  <br>
	  <form id='login' action='login.php' method='post' name='login'>
	  <div class='login'>
	  
		  <table class='medium'>
			<tr>
				<td class='xs'>    
					<label class='required'>Name </label> 
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
					<label class='required'>Password</label> 
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

/*
session_start();

if (isset($_SESSION['webpage'])) { $webpage = $_SESSION['webpage']; }
else { $webpage = "add.php"; }

if (isset($_POST['login']) ){
	
$user_id = "";	


$username = $_POST['username'];
$password = $_POST['password']; 


	
$user = new user($db);
$admin_id = $user->is_admin($username,$password);


        if (empty($admin_id)) {
			
			$error = "* Invalid username or password";
                
        }
        else {
         
                session_destroy();
                session_start();
                
                $_SESSION['username'] = $username;
                header("Location: " . $webpage);
        
        }


/*

if(empty($admin_id)){
	$error = "* Invalid username or password";
}
else{
	$error = "";
	//$_SESSION['group'] == "admin"; 	
}




	

}


?> 





<?php 

/*	if ($_SESSION['group'] == "admin"){
		
		echo "hello";
	}
	
	else {*/
	
		echo $login_html;
	//}
?>



 
<?php 

include ("includes/footer.inc.php"); 

?> 
