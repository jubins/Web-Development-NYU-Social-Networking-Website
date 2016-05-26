<?php	
	global $con;
	global $name;
	
	$get_id = $_GET['post_id'];
		
	$get_com ="select * from comments_sn where post_id='$get_id' ORDER BY 1 DESC";
	
	$run_com = pg_query($con,$get_com);
	
	while($row=pg_fetch_array($run_com)){
		
		$com = $row['comment'];
		$com_name = $row['comment_author'];
		$date = $row['date'];
		
		echo "
		<br/><div id='comments'>
		<h5>$com_name</h5><span><i>on $date, wrote: </i></span>
		$com
		</div>
		
		";
	}
	
?>