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
	
	/*We need to get the row to change in friendship_sn*/
	$status=1;
	$select_friendhsip  = "SELECT * from friendship_sn WHERE user_id1='$session_user_id' AND user_id2='$user_id' and status='$status'
		            UNION  SELECT * from friendship_sn WHERE user_id1='$user_id' AND user_id2='$session_user_id' and status='$status'";
	$run_friendship = pg_query($con,$select_friendhsip);
	$friendship_count = pg_num_rows($run_friendship); //We want the exact row and get the user_id1 and user_id2 data from it.
	
	if($friendship_count == 1){
		$row_friendship = pg_fetch_array($run_friendship);
		$user_id1= $row_friendship['user_id1'];
		$user_id2= $row_friendship['user_id2'];
		
		$uStatus=0;
		$remove_friend = "UPDATE friendship_sn SET status='$uStatus' WHERE user_id1='$user_id1' AND user_id2='$user_id2'";
		$run_remove_friend = pg_query($con,$remove_friend);
	
		if($run_remove_friend){
			echo "<script>alert('Friend has been Removed from friend list') </script>";
			echo "<script>window.open('../user_profile.php?u_id=$user_id','_self' )</script>)";
			
		}
		
	}
	

}

?>