<?php
$dbname      = "host=pdc-amd01.poly.edu port=5432 dbname=jas1464 user=jas1464 password=bzs3vt%w";
$con         = pg_connect( "$dbname"  );
 if(!$con){
    echo "Error : Unable to open database";
  } 

if(isset($_GET['u_id'])){
	global $con;
	$user_id = $_GET['u_id'];
    $sender_user_id = $_GET['s_id'];

	$status=1;
	$acpt_req = "UPDATE friendship_sn SET status='$status' WHERE user_id1='$sender_user_id' AND user_id2='$user_id'";
	$run_acpt_req = pg_query($con,$acpt_req);
	
	if($run_acpt_req){
		echo "<script>alert('Friend Request has been accepted') </script>";
		echo "<script>window.open('../my_friends.php','_self' )</script>)";
		
	}

}
?>