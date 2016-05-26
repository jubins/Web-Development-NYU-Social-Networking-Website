<?php

include('includes/connection.php');

if(isset($_GET['code'])){
	global $con;
	$get_code = $_GET['code'];
	echo "code : $get_code";
	$get_user_verif = "SELECT * FROM user_sn WHERE verification_code='$get_code'";
	$run_user_verif = pg_query($con,$get_user_verif);
	
	$check_user_verif = pg_num_rows($run_user_verif);
	$row_user_verif = pg_fetch_array($run_user_verif);
	
	$verif_user_id = $row_user_verif['user_id'];
	$email=$row_user_verif['user_email_id'];
	if($check_user_verif == 1){
		$update_user = "UPDATE user_sn SET user_staus='verified' WHERE user_id='$verif_user_id'";
		$run_update_user = pg_query($con,$update_user);
		
		
		//echo "<h2>Thanks, Your email has been verified!</h2> Please
        //      <a href='http://localhost/social_network/index.php'>Login</a> to our Website<br/>	";
		//$_SESSION['user_email']=$email;
		echo "<script>alert('Thanks, Your email has been verified!! You can login now.') </script>";
		echo "<script>window.open('index.php','_self')</script>";
	}
	else{
		echo "<script>alert('Sorry,Email not verified,try again!')</script>";
	}
}

?>