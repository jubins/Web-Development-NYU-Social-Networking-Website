
<?php

	/*Compose the mail to send */
	$to = 'rahulreddya.iiita@gmail.com'; /*users email from the form*/
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
	$headers .= 'From: <webmaster@sonet.com>' . "\r\n";
	
	mail($to,$subject,$message,$headers);

	?>