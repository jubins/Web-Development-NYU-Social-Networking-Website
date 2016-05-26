<?php
$dbname      = "host=pdc-amd01.poly.edu port=5432 dbname=jas1464 user=jas1464 password=bzs3vt%w";
$con         = pg_connect( "$dbname"  );
 if(!$con){
    echo "Error : Unable to open database";
  } 

if(isset($_GET['post_id'])){
	global $con;
	$post_id = $_GET['post_id'];
	
	$delete_post = "delete from post_sn where post_id='$post_id'";
	$run_delete = pg_query($con,$delete_post);
	
	if($run_delete){
		echo "<script>alert('A post has been deleted') </script>";
		echo "<script>window.open('../home.php','_self' )</script>)";
		
	}
}

?>