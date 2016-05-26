<?php


$dbname      = "host=pdc-amd01.poly.edu port=5432 dbname=jas1464 user=jas1464 password=bzs3vt%w";
$con         = pg_connect( "$dbname"  );
 if(!$con){
    echo "Error : Unable to open database";
  } 

if(isset($_GET['u_id'])){
	global $con;
	$user_id = $_GET['u_id'];
    $session_user_id = $_GET['s_id'];

	$title = addslashes($_POST['title']);
	$content = addslashes($_POST['content']);
	$wallImage = ($_POST['wall_image']);
	
	if($title == '' and ($title == '' or $content == '')){
		echo "<script>alert('Please add something in the post!') </script>";
		exit();
	}
	else{
		$insert = "insert into POST_SN (user_id,post_title,post_message,post_creation_timestamp,byuser_id,image_name)
							   values  ('$user_id','$title','$content',NOW(),'$session_user_id','$wallImage')";
							   
		$run = pg_query($con,$insert);
		
		if($run){
			echo "<h4 id='timeline_header'>Posted on your timeline, looks good!<br/><br/></h4>";
			$update = "update USER_SN set user_posts='yes' where user_id='$user_id'";
			$run_update = pg_query($con,$update);
			echo "<script>window.open('../user_profile.php?u_id=$user_id','_self' )</script>)";
		}
		
	}

}





?>