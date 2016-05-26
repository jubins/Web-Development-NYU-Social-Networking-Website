<?php
include("/includes/connection.php");

session_start();

session_destroy();

header("location:index.php");
	
//Entire code below updates the LastLogin field
	global $con;
	
	$user_email = $_SESSION['user_email'];
	$get_user = "select * from USER_SN where user_email_id='$user_email'";
	$run_user = pg_query($con,$get_user);
	$row = pg_fetch_array($run_user);
				
	$user_id = $_SESSION['user_id'];
	$user_name = $row['user_name'];
					
	$last_login = "update USER_SN set user_last_login = NOW() where user_id='$user_id' and user_email='$user_email'";
	$last_login_update = pg_query($con,$last_login);

?>