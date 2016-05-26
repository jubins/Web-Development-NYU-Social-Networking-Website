<?php

	$dbname      = "host=pdc-amd01.poly.edu port=5432 dbname=jas1464 user=jas1464 password=bzs3vt%w";
	$con = pg_connect( "$dbname"  );
/* 	if(!$con){
		echo "Error : Unable to open database";
	} 
	else {
		echo "Opened database successfully";
	} */
	
function getGroups(){
	$get_groups = "select * from GROUP_SN";
	$run_groups = pg_query($con,$get_groups);

	while($row = pg_fetch_array($run_groups)){
		$group_id = $row['group_id'];
		$group_name = $row['group_name'];
							
		echo "<option value='$group_id'>$gorup_name</option>";
	}
}


//Function for inseting posts into the wall
function insertPost(){

	if(isset($_POST['sub'])){
		global $con;
		global $user_id;
		$title = addslashes($_POST['title']);
		$content = addslashes($_POST['content']);
		$wallImage = $_FILES['wall_image']['name'];
		$src_wallImage = $_FILES['wall_image']['tmp_name'];
		$dst_wallImage = "../social_network/user/user_images/";
		move_uploaded_file($src_wallImage,$dst_wallImage.$wallImage);
		
		if($title == '' and ($title == '' or $content == '')){
			echo "<script>alert('Please add something in the post!') </script>";
			exit();
		}
		else{
			$insert = "insert into POST_SN (user_id,post_title,post_message,post_creation_timestamp,byuser_id,image_name)
								   values  ('$user_id','$title','$content',NOW(),'$user_id','$wallImage')";
								   
			$run = pg_query($con,$insert);
			
			if($run){
				echo "<h4 id='timeline_header'>Posted on your timeline, looks good!<br/><br/></h4>";
				$update = "update USER_SN set user_posts='yes' where user_id='$user_id'";
				$run_update = pg_query($con,$update);
			}
		}
	}
}


//Function for inserting posts into the wall
function get_posts(){
    global $con;
	global $user_id;
	global $user_image;
	$per_page=7;
	if(isset($_GET['page'])){
		$page = $_GET['page'];		
	}
	else {
		$page = 1;
	}
	
	$start_From = ($page-1)*$per_page;
	
	$get_posts ="select * from POST_SN where user_id='$user_id'ORDER by 1 DESC LIMIT $per_page  OFFSET $start_From";
	
	$run_posts = pg_query($con,$get_posts);
	
	while($row_posts=pg_fetch_array($run_posts)){
		$post_id = $row_posts['post_id'];
		$post_user_id = $row_posts['user_id'];
		$post_by_user_id = $row_posts['byuser_id'];

		$post_title = $row_posts['post_title'];
		$content = $row_posts['post_message'];
		$post_date = $row_posts['post_creation_timestamp'];
		$post_image = $row_posts['image_name'];
		
		//Getting the user who has posted the thread
		$user = "select * from USER_SN where user_id='$post_by_user_id' AND user_posts='yes'";
		
		$run_user=pg_query($con,$user);
		$row_user = pg_fetch_array($run_user);
		$user_name = $row_user['user_name'];
		$user_image = $row_user['user_profile_pic'];
		$user_email = $_SESSION['user_email'];
			
		
		//Displaying them
		if ($post_image==null){
			echo "<div id='posts'>
		
		<p><img src='../social_network/user/user_images/$user_image' width='70' height='90'></p>
		<h3><a href = 'user_profile.php?u_id=$post_by_user_id'>$user_name </a></h3>
		<i>$post_date</i><br/>
		<h3>$post_title</h3>
		<p>$content</p>
		<a href='funtions/delete_post.php?post_id=$post_id' style='float:right;'><button id='posts1_button'>Delete Post</button></a>
		<a href='single.php?post_id=$post_id' style='float:right;'><button>Reply to this Post</button></a>
		
		</div><br/><br/>
		";
			
		}
		else{
			echo "
		<div id='posts_withImage'>
		<p><img src='../social_network/user/user_images/$user_image' width='70' height='90'></p><br/>
		<h3><a href = 'user_profile.php?u_id=$post_by_user_id'>$user_name </a></h3><br/>
		<i>$post_date</i><br/><br/><br/><br/><br/>
		<h3>&nbsp &nbsp$post_title</h3><br/>
		<p>&nbsp &nbsp$content</p><br/><br/><br/><br/><br/><br/>
		<p><img id = 'postedWallImage' src='../social_network/user/user_images/$post_image'></p><br/><br/><br/>
		<a href='funtions/delete_post.php?post_id=$post_id' style='float:right;'><button id='posts1_button'>Delete Post</button></a>
		<a href='single.php?post_id=$post_id' style='float:right;'><button>Reply to this Post</button></a>
		</div>
		<br/><br/>
		";
		}

	}
	
	include("pagination.php");
	
}


function single_posts(){
	
	if(isset($_GET['post_id'])){
		global $con;
		global $user_id;
		global $user_image;
		
		$get_id = $_GET['post_id'];
		
		$get_posts ="select * from POST_SN where post_id='$get_id'";
	
		$run_posts = pg_query($con,$get_posts);
	
	$row_posts=pg_fetch_array($run_posts);
	$post_id = $row_posts['post_id'];
	$post_user_id = $row_posts['user_id'];
	$by_user_id = $row_posts['byuser_id'];
		
		//"Comment below to limit post of all users to all users"
		if($user_id == $user_id){  
		//if($post_user_id == $user_id){
			$post_title = $row_posts['post_title'];
			$content = $row_posts['post_message'];
			$post_date = $row_posts['post_creation_timestamp'];
			$post_image = $row_posts['image_name'];
			
			//Getting the user who has posted the thread
			$user = "select * from USER_SN where user_id='$by_user_id' AND user_posts='yes'";
			
			$run_user=pg_query($con,$user);
			$row_user = pg_fetch_array($run_user);
			$user_name = $row_user['user_name'];
			$user_image = $row_user['user_profile_pic'];
			
			//Getting the user session
			$user_com = $_SESSION['user_email'];
			$get_com = "select * from USER_SN where user_email_id='$user_com'";
			$run_com = pg_query($con,$get_com);
			$row_com = pg_fetch_array($run_com);				
			$user_com_id = $row_com['user_id'];
			$user_com_name = $row_com['user_name'];
			
			
			//Displaying them
			if ($post_image==null){
				echo "<div id='posts'>
			
			<p><img src='../social_network/user/user_images/$user_image' width='70' height='90'></p>
			<h3><a href = 'user_profile.php?u_id=$by_user_id'>$user_name </a></h3>
			<i>$post_date</i><br/>
			<h3>$post_title</h3>
			<p>$content</p>
			
			
			</div><br/><br/>
			";
				
			}
			else{
				echo "
			<div id='posts_withImage'>
			<p><img src='../social_network/user/user_images/$user_image' width='70' height='90'></p><br/>
			<h3><a href = 'user_profile.php?u_id=$by_user_id'>$user_name </a></h3><br/>
			<i>$post_date</i><br/><br/><br/><br/><br/>
			<h3>&nbsp &nbsp$post_title</h3><br/>
			<p>&nbsp &nbsp$content</p><br/><br/><br/><br/><br/><br/>
			<p><img id = 'postedWallImage' src='../social_network/user/user_images/$post_image'></p><br/><br/>
			</div>
			<br/><br/>
			";
			}
			
			if(isset($_POST['reply'])){
				global $con;
				$comment = $_POST['comment'];
				
				$insert = "insert into comments_sn (post_id,user_id,comment,comment_author,date) values ('$post_id','$post_user_id','$comment','$user_com_name',NOW())";
				
				$run = pg_query($con,$insert);
				
				echo "<h4 id='comment_reply'><br/>Your reply has been added!<h4>";
				
				
			}
			include("comments.php");
				
			echo "
			<form action='' method='post' id='reply'><br/>
			<textarea cols='50' rows='5' name='comment' placeholder='Write your reply'></textarea> <br/>
			<button type='submit' name='reply'>Reply to this</button>
			</form>
			";
			
		}
		
	}
}


function user_profile_single_posts(){
	
	if(isset ($_GET['post_id'])){
		global $con;
		global $user_id;
		global $user_image;
		
		$get_id = $_GET['post_id'];
		
		$get_posts ="select * from POST_SN where post_id='$get_id'";
	
		$run_posts = pg_query($con,$get_posts);
	
	$row_posts=pg_fetch_array($run_posts);
	$post_id = $row_posts['post_id'];
	$post_user_id = $row_posts['user_id'];
	$by_user_id = $row_posts['byuser_id'];
	
	//"Comment below to limit post of all users to all users"
		//if($user_id == $user_id){  	
		if($post_user_id == $user_id){
			
			$post_title = $row_posts['post_title'];
			$content = $row_posts['post_message'];
			$post_date = $row_posts['post_creation_timestamp'];
			$post_image = $row_posts['image_name'];
			
			//Getting the user who has posted the thread
			$user = "select * from USER_SN where user_id='$by_user_id' AND user_posts='yes'";
			
			$run_user=pg_query($con,$user);
			$row_user = pg_fetch_array($run_user);
			$user_name = $row_user['user_name'];
			$user_image = $row_user['user_profile_pic'];
			
			//Getting the user session
			$user_com = $_SESSION['user_email'];
			$get_com = "select * from USER_SN where user_email_id='$user_com'";
			$run_com = pg_query($con,$get_com);
			$row_com = pg_fetch_array($run_com);				
			$user_com_id = $row_com['user_id'];
			$user_com_name = $row_com['user_name'];
			
			
			//Displaying them
			if ($post_image==null){
				echo "<div id='posts'>
			
			<p><img src='../social_network/user/user_images/$user_image' width='70' height='90'></p>
			<h3><a href = 'user_profile.php?u_id=$by_user_id'>$user_name </a></h3>
			<i>$post_date</i><br/>
			<h3>$post_title</h3>
			<p>$content</p>
			</div><br/><br/>
			";
				
			}
			else{
				echo "
			<div id='posts_withImage'>
			<p><img src='../social_network/user/user_images/$user_image' width='70' height='90'></p><br/>
			<h3><a href = 'user_profile.php?u_id=$by_user_id'>$user_name </a></h3><br/>
			<i>$post_date</i><br/><br/><br/><br/><br/>
			<h3>&nbsp &nbsp$post_title</h3><br/>
			<p>&nbsp &nbsp$content</p><br/><br/><br/><br/><br/><br/>
			<p><img id = 'postedWallImage' src='../social_network/user/user_images/$post_image'></p><br/><br/>
			</div>
			<br/><br/>
			";
			}
			
			if(isset($_POST['reply'])){
				global $con;
				$comment = $_POST['comment'];
				
				$insert = "insert into comments_sn (post_id,user_id,comment,comment_author,date) values ('$post_id','$user_id','$comment','$user_com_name',NOW())";
				
				$run = pg_query($con,$insert);
				
				echo "<h4 id='comment_reply'><br/>Your reply has been added!<h4>";
					
			}
			include("comments.php");
				
			echo "
			<form action='' method='post' id='reply'><br/>
			<textarea cols='50' rows='5' name='comment' placeholder='Write your reply'></textarea> <br/>
			<button type='submit' name='reply'>Reply to this</button>
			</form>
			";
			
		}
		
	}
}


//Function for getting the categories or topics
function get_Cats(){
    global $con;
	global $user_id;
	global $post_id;
	global $group_id;
	global $user_image;
	$per_page=7;
	if(isset($_GET['page'])){
		$page = $_GET['page'];		
	}
	else {
		$page = 1;
	}
	
	$start_From = ($page-1)*$per_page;
	
	if(isset($_GET['topic']))
	$group_id = $_GET['topic'];

	$get_posts ="select * from POST_SN where group_id='$group_id' ORDER by 1 DESC LIMIT $per_page  OFFSET $start_From";
	
	$run_posts = pg_query($con,$get_posts);
	
	while($row_posts=pg_fetch_array($run_posts)){
		$post_id = $row_posts['post_id'];
		$post_user_id = $row_posts['user_id'];
		
		//"Uncomment below to show post of all users to all users"
		//if($user_id == $user_id){  
		if($post_user_id == $user_id){
			$post_title = $row_posts['post_title'];
			$content = $row_posts['post_message'];
			$post_date = $row_posts['post_creation_timestamp'];
			
			//Getting the user who has posted the thread
			$user = "select * from USER_SN where user_id='$post_user_id' AND user_posts='yes'";
			
			$run_user=pg_query($con,$user);
			$row_user = pg_fetch_array($run_user);
			$user_name = $row_user['user_name'];
			$user_image = $row_user['user_profile_pic'];
			$user_email = $_SESSION['user_email'];
				
			
			//Displaying them
			
			echo "<div id='posts'>
			
			<p><img src='../social_network/user/user_images/$user_image' width='70' height='90'></p>
			<h3><a href = 'user_profile.php?user_id=$post_user_id'>$user_name </a></h3>
			<i>$post_date</i>
			<h3>$post_title</h3>
			<p>$content</p>
			<a href='single.php?post_id=$post_id' style='float:right;'><button>Reply to this Post</button></a>
			
			</div><br/><br/>
			";
		}
		else{
			continue;
		}
	}
	
	include("pagination.php");
	
}


//Function for getting the search results
function GetResults(){
    global $con;
	global $user_id;
	global $post_id;
	global $user_image;
	$per_page=7;
	if(isset($_GET['page'])){
		$page = $_GET['page'];		
	}
	else {
		$page = 1;
	}
	
	$start_From = ($page-1)*$per_page;
	
	if(isset($_GET['user_query']))
	$search_term = $_GET['user_query'];

	$get_posts ="select * from POST_SN where post_title like '%$search_term%' OR post_message like '%$search_term%' ORDER by post_creation_timestamp DESC";
	
	$run_posts = pg_query($con,$get_posts);
	
	$count_result = pg_num_rows($run_posts);
	
	echo "<h1 id='searchcount_css'>Showing $count_result result(s) for '<i>$search_term</i>':</h1><br/><br/><br/><br/><br/><br/><br/><br/>";
	
	if($count_result==0){
		echo "<h3 id='searchcount_css'>Sorry we do not have any results for '<i>$search_term</i>'. Please search something else :)</h3>";
		exit();
	}
	
	
	while($row_posts=pg_fetch_array($run_posts)){
		$post_id = $row_posts['post_id'];
		$post_user_id = $row_posts['user_id'];
		
		//"Uncomment below to show post of all users to all users"
		//if($user_id == $user_id){  
		if($post_user_id == $user_id){
			$post_title = $row_posts['post_title'];
			$content = $row_posts['post_message'];
			$post_date = $row_posts['post_creation_timestamp'];
			
			//Getting the user who has posted the thread
			$user = "select * from USER_SN where user_id='$post_user_id' AND user_posts='yes'";
			
			$run_user=pg_query($con,$user);
			$row_user = pg_fetch_array($run_user);
			$user_name = $row_user['user_name'];
			$user_image = $row_user['user_profile_pic'];
			$user_email = $_SESSION['user_email'];
				
			
			//Displaying them
			
			echo "<div id='posts'>
			
			<p><img src='../social_network/user/user_images/$user_image' width='70' height='90'></p>
			<h3><a href = 'user_profile.php?user_id=$post_user_id'>$user_name </a></h3>
			<i>$post_date</i>
			<h3>$post_title</h3>
			<p>$content</p>
			<a href='single.php?post_id=$post_id' style='float:right;'><button>Reply to this Post</button></a>
			
			</div><br/><br/>
			";
		}
		else{
			continue;
		}
	}
	
	include("pagination.php");
	
}

//Function for inseting posts into the wall
function user_posts(){
    global $con;
	global $user_id;
	global $user_image;
	$per_page=7;
	
	if(isset($_GET['u_id'])){
		$u_id=$_GET['u_id'];		
	}
	
	
	$get_posts ="select * from POST_SN where user_id='$u_id' ORDER by 1 DESC ";
	
	$run_posts = pg_query($con,$get_posts);
	
	while($row_posts=pg_fetch_array($run_posts)){
		$post_id = $row_posts['post_id'];
		$post_user_id = $row_posts['user_id'];
		$post_by_user_id = $row_posts['byuser_id'];
		$post_image = $row_posts['image_name'];

		$post_title = $row_posts['post_title'];
		$content = $row_posts['post_message'];
		$post_date = $row_posts['post_creation_timestamp'];
		
		//Getting the user who has posted the thread
		$user = "select * from USER_SN where user_id='$post_by_user_id' AND user_posts='yes'";
		
		$run_user=pg_query($con,$user);
		$row_user = pg_fetch_array($run_user);
		$user_name = $row_user['user_name'];
		$user_image = $row_user['user_profile_pic'];
		$user_email = $_SESSION['user_email'];
			
		
		//Displaying them
		if ($post_image==null){
				echo "<div id='posts'>
			
			<p><img src='../social_network/user/user_images/$user_image' width='70' height='90'></p>
			<h3><a href = 'user_profile.php?u_id=$post_by_user_id'>$user_name </a></h3>
			<i>$post_date</i><br/>
			<h3>$post_title</h3>
			<p>$content</p>
			<a href='single.php?post_id=$post_id' style='float:right;'><button id='posts1_button'>View</button></a>
			<a href='edit_post.php?post_id=$post_id' style='float:right;'><button id='posts1_button'>Edit</button></a>
			<a href='funtions/delete_post.php?post_id=$post_id' style='float:right;'><button id='posts1_button'>Delete</button></a>
			</div><br/><br/><br/>
			";
				
			}
			else{
				echo "
			<div id='posts_withImage'>
			<p><img src='../social_network/user/user_images/$user_image' width='70' height='90'></p><br/>
			<h3><a href = 'user_profile.php?u_id=$post_by_user_id'>$user_name </a></h3><br/>
			<i>$post_date</i><br/><br/><br/><br/><br/>
			<h3>&nbsp &nbsp$post_title</h3><br/>
			<p>&nbsp &nbsp$content</p><br/><br/><br/><br/><br/><br/>
			<p><img id = 'postedWallImage' src='../social_network/user/user_images/$post_image'></p><br/><br/><br/><br/>
			<a href='single.php?post_id=$post_id' style='float:right;'><button>View</button></a>
			<a href='edit_post.php?post_id=$post_id' style='float:right;'><button>Edit</button></a>
			<a href='funtions/delete_post.php?post_id=$post_id' style='float:right;'><button>Delete</button></a>
			</div><br/><br/><br/>
			";
			}
		
		include("delete_post.php");

	}	
}



//Function for inseting posts into the profile
function user_profile_posts($profile_user_id,$session_user_id){
    global $con;

	$user_id=$profile_user_id;
	$get_posts ="select * from POST_SN where user_id='$user_id' ORDER BY post_id DESC";
	
	$run_posts = pg_query($con,$get_posts);
	
	if(isset($_POST['sub_frnd_wall'])){
		$wallImage = $_FILES['wall_image']['name'];
		$src_wallImage = $_FILES['wall_image']['tmp_name'];
		$dst_wallImage = "../social_network/user/user_images/";
		move_uploaded_file($src_wallImage,$dst_wallImage.$wallImage);
	}
	
	while($row_posts=pg_fetch_array($run_posts)){
		$post_id = $row_posts['post_id'];
		$post_user_id = $row_posts['user_id'];
		$post_by_user_id = $row_posts['byuser_id'];

		$post_title = $row_posts['post_title'];
		$content = $row_posts['post_message'];
		$post_date = $row_posts['post_creation_timestamp'];
		$post_image = $row_posts['image_name'];
		
		//Getting the user who has posted the thread
		$user = "select * from USER_SN where user_id='$post_by_user_id' AND user_posts='yes'";
		
		$run_user=pg_query($con,$user);
		$row_user = pg_fetch_array($run_user);
		$user_name = $row_user['user_name'];
		$user_image = $row_user['user_profile_pic'];
		$user_email = $_SESSION['user_email'];
			
		
		//Displaying them
		if ($post_image==null){
			echo "<div id='posts'>
		
		<p><img src='../social_network/user/user_images/$user_image' width='70' height='90'></p>
		<h3><a href = 'user_profile.php?u_id=$post_by_user_id'>$user_name </a></h3>
		<i>$post_date</i><br/>
		<h3>$post_title</h3>
		<p>$content</p>
		<a href='funtions/delete_post.php?post_id=$post_id' style='float:right;'><button id='posts1_button'>Delete Post</button></a>
		<a href='single.php?post_id=$post_id' style='float:right;'><button>Reply to this Post</button></a>
		
		</div><br/><br/>
		";
			
		}
		else{
			echo "
		<div id='posts_withImage'>
		<p><img src='../social_network/user/user_images/$user_image' width='70' height='90'></p><br/>
		<h3><a href = 'user_profile.php?u_id=$post_by_user_id'>$user_name </a></h3><br/>
		<i>$post_date</i><br/><br/><br/><br/><br/>
		<h3>&nbsp &nbsp$post_title</h3><br/>
		<p>&nbsp &nbsp$content</p><br/><br/><br/><br/><br/><br/>
		<p><img id = 'postedWallImage' src='../social_network/user/user_images/$post_image'></p><br/><br/><br/>
		<a href='funtions/delete_post.php?post_id=$post_id' style='float:right;'><button id='posts1_button'>Delete Post</button></a>
		<a href='single.php?post_id=$post_id' style='float:right;'><button name='profile_single_post'>Reply to this Post</button></a>
		</div>
		<br/><br/>
		";
		
		}

	}
		
			
}

function user_profile(){
	if(isset($_GET['u_id'])){
		global $con;
		$user_id = $_GET['u_id'];
		$up_id=$user_id;
		$select = "select * from user_sn where user_id='$user_id'";
		$run =pg_query($con,$select);
		$row =pg_fetch_array($run);
		
		$id = $row['user_id'];
		$user_image = $row['user_profile_pic'];
		$name = $row['user_name'];
		$country = $row['user_country'];
		$gender = $row['user_gender'];
		$user_email = $row['user_email_id'];
		$user_phone = $row['user_phone'];
		$user_birthday = $row['user_birthday'];
		$last_login = $row['user_last_login'];
		$register_date = $row['user_register_date'];
		
		if($gender == 'Male'){
			$msg = "Send him a message";
		}
		else {
			$msg = "Send her message";
		}
		
		echo "<img id = 'user_profile_image' src='../social_network/user/user_images/$user_image' width='190' height='170' />";
		echo "<div id='user_profile' style='color:#151B54'>
		
			  <form id='user_profile_details1'><br/>
			  <p><strong>Name: </strong>$name</p><br/>
			  <p><strong>Country: </strong>$country</p><br/>
			  <p><strong>Gender: </strong>$gender</p><br/>
			  <p><strong>Last Seen: </strong>$last_login</p><br/>
			  <p><strong>Member Since: </strong>$register_date</p>
			  </form><br/>
			  ";
			  
		$session_user_email = $_SESSION['user_email'];
		$get_user = "select * from USER_SN where user_email_id='$session_user_email'";
		$run_user = pg_query($con,$get_user);
		$row_user = pg_fetch_array($run_user);				
		$session_user_id = $row_user['user_id'];
		
		
		$select_friends = "SELECT * from friends_sn WHERE user_id1='$session_user_id' AND user_id2='$id' ";
		$res = pg_query($con,$select_friends);
		$friends_count = pg_num_rows($res);//this is to see if they are already friends
					
		
		if($friends_count == 1){
			
			echo "<div id='user_profile_FRD'>
			  <br/><br/><br/><br/><br/><br/>
			 <h4>&nbsp &nbspYou are friends with <strong>$name</strong>, Do you want to <a href='funtions/removeFriend.php?u_id=$user_id&s_id=$session_user_id'><button id = 'editPostButton' name = 'remove_friend'>UnFriend</button></a><br> </h4>
			  </div><br/>";
		} 
		if($friends_count != 1){
			if($session_user_id != $user_id){
				$status=2;
				$select_frnd_requests = "SELECT * from friendship_sn WHERE user_id1='$session_user_id' AND user_id2='$id' and status='$status'
								  UNION  SELECT * from friendship_sn WHERE user_id1='$id' AND user_id2='$session_user_id' and status='$status'";
				$run_frnd_requests = pg_query($con,$select_frnd_requests);
				$request_count = pg_num_rows($run_frnd_requests); //This is to see if there is already a request in place from either users;
				
				if($request_count == 1){
					echo "<div id='user_profile_SFR'>
						<br/><br/><br/><br/><br/><br/>
						<a href='funtions/addFriend.php?u_id=$user_id&s_id=$session_user_id'><button disabled name ='add_friend' id='friendRequestSentButton'> Friend Request Sent</button></a><br>
						</div><br/>";
				}
				else{
					echo "<div id='user_profile_SFR'>
						<br/><br/><br/><br/><br/><br/> &nbsp
						<a href='funtions/addFriend.php?u_id=$user_id&s_id=$session_user_id'> &nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp <button name ='add_friend' id='editPostButton'>
						Add Friend</button></a><br>
						</div><br/>";
				}
			}
			else{
				
				echo "<div> <br/><br/><br/><br/><br/><br/></div>";
			}
		}
		new_members($user_id,$session_user_id);
		
		//Toggle buttons
		echo"
		<form method = 'POST'>
		<input id='toggleButton_Gallery' type='submit' class='button' name='frnd_Info' value='About $name'/>
		<input id='toggleButton_Timeline' type='submit' name='frnd_Timeline' value='$name`s Timeline'/>
		<input id='toggleButton_Gallery' type='submit' class='button' name='frnd_Gallery' value='$name`s Gallery'/>
		</form>
		<br/><br/> 
		";
		
		
	//Checking Permissions given by user
	$select_permission_data = "select * from permissions_sn where user_id='$id';";
	$res_permissions = pg_query($con,$select_permission_data);
	$row_permissions = pg_fetch_assoc($res_permissions);
	$profile_permissions = $row_permissions['profile_info'];
	$gallery_permissions = $row_permissions['gallery'];
	$posts_permissions = $row_permissions['posts'];
	
	
	
		//Displays users Timeline
		if(isset($_POST['frnd_Timeline'])){
	
			//Applying the permission selected by user
			if($posts_permissions == '0'){
				
				if($user_id == $session_user_id){
					echo "<div id='user_profile_post'> 
				<h3 style='color:brown'> Post something on $name's wall </h3><br/>
			  
				<form action='funtions/postToUserProfile.php?u_id=$user_id&s_id=$session_user_id' method='post' enctype='value' id='f' >
				<table>
				<input type='text'  name='title' placeholder='Want to say something!' required='required'/></br></br>
				<textarea name='content' placeholder='Want to describe it...'></textarea><br/>
				<input type='file' enctype = 'multipart/form-data' name='wall_image' /><br/><br/>
				<button type='submit' name='sub_frnd_wall'><strong>Post to the wall</strong></button><br/>
				</form>
				<br/>
				</div>";
			user_profile_posts($user_id,$session_user_id);
				}
				else{
					echo "<h3 style='color:darkviolet'> We're sorry, $name is not sharing Timeline with anyone </h3> <br/><br/><br/>";
				}
				}
			
			if($posts_permissions == '1'){
				if (($friends_count == 1) or ($user_id == $session_user_id)){
				echo "<div id='user_profile_post'> 
				<h3 style='color:brown'> Post something on $name's wall </h3><br/>
			  
				<form action='funtions/postToUserProfile.php?u_id=$user_id&s_id=$session_user_id' method='post' enctype='value' id='f' >
				<table>
				<input type='text'  name='title' placeholder='Want to say something!' required='required'/></br></br>
				<textarea name='content' placeholder='Want to describe it...'></textarea><br/>
				<input type='file' enctype = 'multipart/form-data' name='wall_image' /><br/><br/>
				<button type='submit' name='sub_frnd_wall'><strong>Post to the wall</strong></button><br/>
				</form>
				<br/>
				</div>";
			user_profile_posts($user_id,$session_user_id);
				}
			}
			
			if($posts_permissions == '2'){
				echo "<div id='user_profile_post'> 
				<h3 style='color:brown'> Post something on $name's wall </h3><br/>
			  
				<form action='funtions/postToUserProfile.php?u_id=$user_id&s_id=$session_user_id' method='post' enctype='value' id='f' >
				<table>
				<input type='text'  name='title' placeholder='Want to say something!' required='required'/></br></br>
				<textarea name='content' placeholder='Want to describe it...'></textarea><br/>
				<input type='file' enctype = 'multipart/form-data' name='wall_image' /><br/><br/>
				<button type='submit' name='sub_frnd_wall'><strong>Post to the wall</strong></button><br/>
				</form>
				<br/>
				</div>";
			user_profile_posts($user_id,$session_user_id);
			}
		
		}
		
		//Displays users Gallery
		if(isset($_POST['frnd_Gallery'])){
			
			//Applying the permission selected by user
			if($gallery_permissions == '0'){
				if($user_id == $session_user_id)
				{
					showImage_FrndGallery($id,$name);
				}
				else{
					echo "<h3 style='color:darkviolet'> We're sorry, $name is not sharing Gallery with anyone </h3><br/><br/><br/>";
				}
			}
			
			if($gallery_permissions == '1'){
				if(($friends_count == 1) or ($user_id == $session_user_id)){
				showImage_FrndGallery($id,$name);
				}		
			}
			
			if($gallery_permissions == '2'){
			showImage_FrndGallery($id,$name);
			}
		}
		
		//Displays About info
		if(isset($_POST['frnd_Info'])){
			
			//Applying the permission selected by user
			if($profile_permissions == '0'){
				if($user_id == $session_user_id){
					echo"
			<h3 style='color:brown'> &nbsp&nbsp Details of $name : </h3>
			<form id='frnd_Info'><br/>
				<table>
					<tr><td align='left'><strong>&nbsp &nbspName: </strong><br/><br/></td>
						<td><input value='&nbsp $name' readonly/><br/><br/></td>
					</tr>
					
					<tr><td align='left'><strong>&nbsp &nbspCountry: </strong><br/><br/></td>
						<td><input value='&nbsp $country' readonly/><br/><br/></td>
					</tr>
					
					<tr><td align='left'><strong>&nbsp &nbspGender: </strong><br/><br/></td>
						<td><input value='&nbsp $gender' readonly/><br/><br/></td>
					</tr>
					
					<tr><td align='left'><strong>&nbsp &nbspDate of Birth: </strong><br/><br/></td>
						<td><input value='&nbsp $user_birthday' readonly/><br/><br/></td>
					</tr>
					
					<tr><td align='left'><strong>&nbsp &nbspEmail: </strong><br/><br/></td>
						<td><input value='&nbsp $user_email' readonly/><br/><br/></td>
					</tr>
					
					<tr><td align='left'><strong>&nbsp &nbspPhone: </strong><br/><br/></td>
						<td><input value='&nbsp $user_phone' readonly/><br/><br/></td>
					</tr>
					
					<tr><td align='left'><strong>&nbsp &nbspLast Seen: </strong><br/><br/></td>
						<td><input value='&nbsp $last_login' readonly/><br/><br/></td>
					</tr>
					
					<tr><td align='left'><strong>&nbsp &nbspMember Since: </strong><br/><br/></td>
						<td><input value='&nbsp $register_date' readonly/><br/><br/></td>
					</tr>
				</table>
			</form><br/>";
				}
				else{
					echo "<h3 style='color:darkviolet'> We're sorry, $name is not sharing Profile Details with anyone </h3><br/><br/><br/>";
				}
			}
			
			if($profile_permissions == '1'){
				if(($friends_count == 1) or ($user_id == $session_user_id)){
			
			echo"
			<h3 style='color:brown'> &nbsp&nbsp Details of $name : </h3>
			<form id='frnd_Info'><br/>
				<table>
					<tr><td align='left'><strong>&nbsp &nbspName: </strong><br/><br/></td>
						<td><input value='&nbsp $name' readonly/><br/><br/></td>
					</tr>
					
					<tr><td align='left'><strong>&nbsp &nbspCountry: </strong><br/><br/></td>
						<td><input value='&nbsp $country' readonly/><br/><br/></td>
					</tr>
					
					<tr><td align='left'><strong>&nbsp &nbspGender: </strong><br/><br/></td>
						<td><input value='&nbsp $gender' readonly/><br/><br/></td>
					</tr>
					
					<tr><td align='left'><strong>&nbsp &nbspDate of Birth: </strong><br/><br/></td>
						<td><input value='&nbsp $user_birthday' readonly/><br/><br/></td>
					</tr>
					
					<tr><td align='left'><strong>&nbsp &nbspEmail: </strong><br/><br/></td>
						<td><input value='&nbsp $user_email' readonly/><br/><br/></td>
					</tr>
					
					<tr><td align='left'><strong>&nbsp &nbspPhone: </strong><br/><br/></td>
						<td><input value='&nbsp $user_phone' readonly/><br/><br/></td>
					</tr>
					
					<tr><td align='left'><strong>&nbsp &nbspLast Seen: </strong><br/><br/></td>
						<td><input value='&nbsp $last_login' readonly/><br/><br/></td>
					</tr>
					
					<tr><td align='left'><strong>&nbsp &nbspMember Since: </strong><br/><br/></td>
						<td><input value='&nbsp $register_date' readonly/><br/><br/></td>
					</tr>
				</table>
			</form><br/>";
			}
			}
			
			if($profile_permissions == '2'){
			
			echo"
			<h3 style='color:brown'> &nbsp&nbsp Details of $name : </h3>
			<form id='frnd_Info'><br/>
				<table>
					<tr><td align='left'><strong>&nbsp &nbspName: </strong><br/><br/></td>
						<td><input value='&nbsp $name' readonly/><br/><br/></td>
					</tr>
					
					<tr><td align='left'><strong>&nbsp &nbspCountry: </strong><br/><br/></td>
						<td><input value='&nbsp $country' readonly/><br/><br/></td>
					</tr>
					
					<tr><td align='left'><strong>&nbsp &nbspGender: </strong><br/><br/></td>
						<td><input value='&nbsp $gender' readonly/><br/><br/></td>
					</tr>
					
					<tr><td align='left'><strong>&nbsp &nbspDate of Birth: </strong><br/><br/></td>
						<td><input value='&nbsp $user_birthday' readonly/><br/><br/></td>
					</tr>
					
					<tr><td align='left'><strong>&nbsp &nbspEmail: </strong><br/><br/></td>
						<td><input value='&nbsp $user_email' readonly/><br/><br/></td>
					</tr>
					
					<tr><td align='left'><strong>&nbsp &nbspPhone: </strong><br/><br/></td>
						<td><input value='&nbsp $user_phone' readonly/><br/><br/></td>
					</tr>
					
					<tr><td align='left'><strong>&nbsp &nbspLast Seen: </strong><br/><br/></td>
						<td><input value='&nbsp $last_login' readonly/><br/><br/></td>
					</tr>
					
					<tr><td align='left'><strong>&nbsp &nbspMember Since: </strong><br/><br/></td>
						<td><input value='&nbsp $register_date' readonly/><br/><br/></td>
					</tr>
				</table>
			</form><br/>
			
			";
			}
		}
	}

}

function new_members($user_id,$session_user_id){
	
	global $con;
	
	//fetch the new members list [say 10] of the person in interest
	$get_friends = "SELECT * FROM friends_sn WHERE user_id1='$user_id' LIMIT 10 OFFSET 0";
	$run_get_friends = pg_query($con,$get_friends);
	$friends_count = pg_num_rows($run_get_friends);
	
	echo "<br/><h3 style='color:red'>Friends($friends_count):</h3><br/>";
	while ($row_friend = pg_fetch_array($run_get_friends)){
		$friend_id = $row_friend['user_id2'];
		
		$user = "SELECT * FROM user_sn WHERE user_id='$friend_id'";
		$run_user = pg_query($con,$user);
		$row_user = pg_fetch_array($run_user);
		
		$user_id = $row_user['user_id'];
		$user_name = $row_user['user_name'];
		$user_image = $row_user['user_profile_pic'];
		
		echo "
			<span>
			<a href='user_profile.php?u_id=$user_id'>
			<img src='../social_network/user/user_images/$user_image' width='100' height='100' padding='5' title='$user_name' style='float:left;'/>
			</a>
			</span>
			";
	}
	echo "<br/><br/><br/><br/><br/><br/><br/><br/><br/><br/>";
}

//Function for uploading the images in Gallery
function uploadPhoto(){
    global $con;
	global $user_id;
	
	
	
if(isset($_POST['image'])){
	
	$wallImage = $_FILES['filetoUpload']['name'];
	$src_wallImage = $_FILES['filetoUpload']['tmp_name'];
	$dst_wallImage = "../social_network/user/user_images/";
	move_uploaded_file($src_wallImage,$dst_wallImage.$wallImage);
	
	$imageFile = pg_escape_string($_FILES['filetoUpload']['name']);
	$imageData = $src_wallImage;
	$escaped = pg_escape_bytea($imageData);
	$insert_image = "INSERT INTO PHOTO_SN (user_id,image_name,image) values('$user_id','$imageFile','$escaped')";

	$upload_image =	pg_query($con,$insert_image);
	
	if($upload_image==true){
		echo"<h3 id='gallery_header'><br/>Image uploaded successfully<br/></h3>";
	}
	
	}

}


//Function for displaying the images in Gallery
function showImage(){
	global $con;
	global $user_id;
	global $photo_id;
	
	$select_image = "SELECT * FROM PHOTO_SN WHERE user_id='$user_id' ORDER BY photo_id DESC";
	$res = pg_query($con, $select_image);
	$image_count = pg_num_rows($res);
	
	echo"<h3 id='gallery_header'>You have uploaded $image_count images:</h3><br/>";
	while($row=pg_fetch_assoc($res)){
		$data = pg_fetch_result($res,'image_name');
		$unes_image = pg_unescape_bytea($data);
		
		$image_name = $row['image_name'];
		
		echo"
		<img id='showImage' src='../social_network/user/user_images/$image_name' title='$image_name' height='150' width='150'/>
		";
		
	}echo "<br/><br/><br/>";
}

//Function for displaying the images of Friends gallery
function showImage_FrndGallery($id,$name){
	global $con;
	global $user_id;
	global $photo_id;
	

	//Selecting photo
	$select_image = "SELECT * FROM PHOTO_SN WHERE user_id='$id' ORDER BY photo_id DESC;";
	$res = pg_query($con, $select_image);
	$image_count = pg_num_rows($res);
	
	echo"<h3 style='color:brown' id='gallery_header'>$name has uploaded $image_count images:</h3><br/>";
	while($row=pg_fetch_assoc($res)){
		$data = pg_fetch_result($res,'image_name');
		$unes_image = pg_unescape_bytea($data);
		
		$image_name = $row['image_name'];
		
		echo"
		<img id='showImage' src='../social_network/user/user_images/$image_name' title='$image_name' height='150' width='150'/>
		";
	
	
	}echo "<br/><br/><br/>";
}


function updateProfile(){
	global $con;
	global $user_id;	
	
	if(isset($_POST['update'])){
		
		$wallImage = $_FILES['u_image']['name'];
		$src_wallImage = $_FILES['u_image']['tmp_name'];
		$dst_wallImage = "../social_network/user/user_images/";
		move_uploaded_file($src_wallImage,$dst_wallImage.$wallImage);
		
		$u_name = $_POST['u_name'] ;
		$u_pass = $_POST['u_pass'] ;
		$u_phone = $_POST['u_phone'];
		$u_birthday = $_POST['u_birthday'];
		$u_country = $_POST['u_country'];

		if($wallImage == null){
			
			$update = "update user_sn set user_name='$u_name',user_pass='$u_pass',user_phone='$u_phone',user_birthday='$u_birthday',user_country='$u_country' where user_id=$user_id";
		}
		else{
		$update = "update user_sn set user_name='$u_name',user_pass='$u_pass',user_phone='$u_phone',user_birthday='$u_birthday',user_country='$u_country', user_profile_pic='$wallImage' where user_id=$user_id";
		}
						
		$run = pg_query($con,$update);
						
			if($run){
				echo"<script>alert('Your Profile Changes have been updated')</script>";
				echo "<script>window.open('home.php','_self')</script>";
				}
						
		}
}


function user_friend_list(){
	global $con;
	global $user_id;
	
	$select_friends = "SELECT * from friends_sn WHERE user_id1='$user_id' ";
	$res = pg_query($con,$select_friends);
	$friends_count = pg_num_rows($res);
	
	while($row = pg_fetch_array($res)){
		$frnd_id=$row['user_id2'];
		$select_profile = "SELECT * from user_sn WHERE user_id='$frnd_id'";
		$frnd_res = pg_query($con,$select_profile);
		
		if($frnd_res){
			
			$frnd_name = pg_fetch_result($frnd_res,'user_name');
			
			$frnd_image = pg_fetch_result($frnd_res,'user_profile_pic');
			
			echo "<div>
				</br> </br> </br>
                <h3><a href='user_profile.php?u_id=$frnd_id'>$frnd_name</a></h3><br/>
				<p><img src='../social_network/user/user_images/$frnd_image' width='150' height='180' title='$frnd_name' /></p>
				
				
		
			</div></br></br>
		";
		}
		
	}
}


function frnd_request_process(){
	global $con;
	global $user_id;
	
	$status=2;
	$select_requests = "SELECT * from friendship_sn where user_id2='$user_id' AND status='$status'";
	$res = pg_query($con,$select_requests);
	
	while($row = pg_fetch_array($res)){
		$requestingUser_id = $row['user_id1'];
		$select_profile = "SELECT * from user_sn WHERE user_id='$requestingUser_id'";
		
		$frnd_res = pg_query($con,$select_profile);
		
		if($frnd_res){
			
			$frnd_name = pg_fetch_result($frnd_res,'user_name');			
			$frnd_image = pg_fetch_result($frnd_res,'user_profile_pic');
			
			echo "<div>
				<h3><a href='user_profile.php?u_id=$requestingUser_id'>$frnd_name</a></h3><br/>
				<p><img src='../social_network/user/user_images/$frnd_image' width='150' height='180' title='$frnd_name' /></p>
				<a href='funtions/frndAccept.php?u_id=$user_id&s_id=$requestingUser_id'><button id='friendRequestAcceptButton'>Accept Friend</button></a><br>
				</div></br></br>
		";
		}
	}
}


function updatePrivacy(){
	global $con;
	global $user_id;
	
	//Checking Permissions given by user
	$select_permission_data = "select * from permissions_sn where user_id='$user_id';";
	$res_permissions = pg_query($con,$select_permission_data);
	$row_permissions = pg_fetch_assoc($res_permissions);
	$user_permissions_id = "select user_id from permissions_sn";
	$permissions_id = pg_query($con,$user_permissions_id);
	$profile_permissions = $row_permissions['profile_info'];
	$gallery_permissions = $row_permissions['gallery'];
	$posts_permissions = $row_permissions['posts'];

	
	if(isset($_POST['update_privacy'])){

		$u_personalDataPrivacy = $_POST['u_personalDataPrivacy'] ;
		$u_timelinePrivacy = $_POST['u_timelinePrivacy'] ;
		$u_galleryPrivacy = $_POST['u_galleryPrivacy'];
		
		if($u_personalDataPrivacy == 'Only Me'){
			$update_privacy = "update permissions_sn set profile_info = '0' where user_id='$user_id' ";
			$run = pg_query($con,$update_privacy);
			}
			
		if($u_personalDataPrivacy == 'Friends'){
			$update_privacy = "update permissions_sn set profile_info = '1' where user_id='$user_id' ";
			$run = pg_query($con,$update_privacy);
			}
		
		if($u_personalDataPrivacy == 'Everyone'){
			$update_privacy = "update permissions_sn set profile_info = '2' where user_id='$user_id' ";
			$run = pg_query($con,$update_privacy);
			}
		
		if($u_timelinePrivacy == 'Only Me'){
			$update_privacy = "update permissions_sn set posts = '0' where user_id='$user_id' ";
			$run = pg_query($con,$update_privacy);
			}
			
		if($u_timelinePrivacy == 'Friends'){
			$update_privacy = "update permissions_sn set posts = '1' where user_id='$user_id' ";
			$run = pg_query($con,$update_privacy);
			}
		
		if($u_timelinePrivacy == 'Everyone'){
			$update_privacy = "update permissions_sn set posts = '2' where user_id='$user_id' ";
			$run = pg_query($con,$update_privacy);
			}
		
		
		if($u_galleryPrivacy == 'Only Me'){
			$update_privacy = "update permissions_sn set gallery = '0' where user_id='$user_id' ";
			$run = pg_query($con,$update_privacy);
			}
			
		if($u_galleryPrivacy == 'Friends'){
			$update_privacy = "update permissions_sn set gallery = '1' where user_id='$user_id' ";
			$run = pg_query($con,$update_privacy);
			}
			
		if($u_galleryPrivacy == 'Everyone'){
			$update_privacy = "update permissions_sn set gallery = '2' where user_id='$user_id' ";
			$run = pg_query($con,$update_privacy);
			}

			
			if($run){
				echo "<br/><h4 style='color:darkviolet'>&nbsp &nbsp &nbsp &nbsp &nbsp &nbsp &nbsp
				Status: Your Privacy Changes have been saved!
				</h4>
				";
				}
		
		
		//else{
		//	$insert_user_permissions = "insert into permissions_sn (user_id, profile_info, gallery, posts) values ('$user_id','1','1','1');";
		//}
		
		}
		//Privacy settings are applied in updateProfile function
}


?>