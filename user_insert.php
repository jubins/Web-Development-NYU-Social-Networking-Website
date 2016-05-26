<?php 
include("/includes/connection.php");
if(isset($_POST['sign_up'])){
	
	$name = pg_escape_string($con,$_POST['u_name']);
	$pass = pg_escape_string($con,$_POST['u_pass']);
	$email = pg_escape_string($con,$_POST['u_email']);
	$country = pg_escape_string($con,$_POST['u_country']);
	$gender = pg_escape_string($con,$_POST['u_gender']);
	$birthday = pg_escape_string($con,$_POST['u_birthday']);
	$date = date("m-d-y");
	$status = "unverified";
	$posts ="No";
	
	$email_verification_code = (string)mt_rand();
	
	$get_email = "select * from USER_SN where user_email_id='$email'";
	$run_email = pg_query($con,$get_email);
	$check = pg_num_rows($run_email);
	
	if ($check == 1) {
		echo "<script>alert('this email is already registered,Please try another!') </script>";
		exit();
	}			
	elseif(strlen($pass) < 8){
		echo "<script>alert('Password should be minimum of 8 characters') </script>";
		exit();
	}
	else{
		$insert = "insert into USER_SN (user_name, user_pass, user_gender, user_birthday, user_phone, user_email_id, user_country, user_register_date, user_last_login, user_staus, user_posts,verification_code ,       user_profile_pic) 
				               VALUES  ( '$name',  '$pass',   '$gender',   '$birthday',  '000000000', '$email',      '$country',      '$date',          '$date',         '$status' ,'$posts' , '$email_verification_code','default.jpg')";
		
		$run_insert = pg_query($con,$insert);
		if($run_insert){
			$_SESSION['user_email']=$email;
			
			$res_user_id = pg_query($con,$get_email);
			$row_user_id = pg_fetch_assoc($res_user_id);
			$get_user_id = $row_user_id['user_id'];
			$insert_user_permissions = "insert into permissions_sn (user_id, profile_info, gallery, posts) values ('$get_user_id','2','2','2')";
			$run_insert_user_permissions = pg_query($con,$insert_user_permissions);
			
			echo "<script>alert('Registration process is almotst done.We have sent a mail from SONET, please check your inbox for further instructions!') </script>";
			echo "<script>window.open('index.php','_self')</script>";

		}
	}
	
	/*Compose the mail to send */
	$to = $email; /*users email from the form*/
	$subject = "Email Verification for SONET socical network";
	/*Can be anything, either html message or plain text*/
	$message = "
		<html>
			Hi <strong>$name</strong>, <br/>
			You have registered with www.sonet.com, please verify your email by clicking the below link.
			<br/>
			<a href='http://localhost/social_network/verify.php?code=$email_verification_code'>Click to verify your email</a><br/>
			Thank  you for registering with us!
		</html>";
	
	// Always set content-type when sending HTML email
	$headers = "MIME-Version: 1.0" . "\r\n";
	$headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
	$headers .= 'From: socnet2016@gmail.com' . "\r\n";

	mail($to,$subject,$message,$headers);
}

?>