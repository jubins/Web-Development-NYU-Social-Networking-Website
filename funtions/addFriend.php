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

	$status=2;
	$add_friend = "INSERT into friendship_sn (user_id1,user_id2,status)
	                                  VALUES ('$session_user_id','$user_id','$status')";
	$run_add_friend = pg_query($con,$add_friend);
	
	if($run_add_friend){
		echo "<script>alert('Friend Request has been sent') </script>";
		echo "<script>window.open('../user_profile.php?u_id=$user_id','_self' )</script>)";
		
	}

}
?>