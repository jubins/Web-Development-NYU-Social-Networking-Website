<?php
session_start();
include("/includes/connection.php");
include("/funtions/functions.php");
include("/user_insert.php");

if(!isset($_SESSION['user_email'])){
	header("location:index.php");
}
else{
?>
<!DOCTYPE html>
<html>
	<head>
		<title>Members List NYU Social Network </title>
		<link rel="stylesheet" href="styles/home_style.css" media="all"/>
	</head>

<body>
<a href="home.php">
<img id="shortlogo" src="images\shortestlogo.png" height="40" width="70" /> </a>
	<!-- Container Starts -->
	<div class="container">
		<div id="head_wrap">
			<div id="header">
				<ul id="menu">
					<li><a href="home.php" >Home</a></li>
					<strong>Members</strong>
					<li><a href="my_friends.php">Friends</a></li>
					<li><a href="gallery.php">Gallery</a></li>
					<li><a href='logout.php'>Logout</a> </li>
					
			
				</ul>
				<form method="get" action="results.php" id="form1">
					
					<input type="text" name="user_query" placeholder="Search a topic" height="20" width="250" />
					<button type="submit" name="search" height="32" width="70" >Search</button>
					
				</form>
			</div>
			<!-- Header Ends-->
		</div>
		<!-- Header Wrapper Ends-->
		<!-- Content starts-->
		<div class="content">
			<!-- User timeline starts-->
			<div id="user_timeline">
				<div id="user_details">
				<?php 
				$user_email = $_SESSION['user_email'];
				$get_user = "select * from USER_SN where user_email_id='$user_email'";
				$run_user = pg_query($con,$get_user);
				$row = pg_fetch_array($run_user);
				
				$user_id = $row['user_id'];
				$user_name = $row['user_name'];
				$user_country = $row['user_country'];
				$user_image = $row['user_profile_pic'];
				$registered_date = $row['user_register_date'];
				$last_login = $row['user_last_login'];
				echo "
				     </p><img src='../social_network/user/user_images/$user_image' width='210' height='270' /></p>
					 <div id='user_mention'><br/>
					 <p><strong>Name</strong>        : $user_name</p>
					 <p><strong>Country</strong>     : $user_country</p>
					 <p><strong>Email</strong> 		 : $user_email</p>
					 <p><strong>Last Login</strong>  : $last_login</p>
					 <p><strong>Member Since</strong>: $registered_date</p>
					 <br/><br/>
					 <p><a href='user_profile.php?u_id=$user_id'>My Profile</a></p>
					 <p><a href='my_friends.php?u_id=$user_id'>My Friends</a> </p>
					 <!-- <p><a href='my_posts.php?u_id=$user_id'>My Posts</a> </p>
					 -->
					 <p><a href='edit_profile.php'>Settings</a> </p>
					 <p><a href='logout.php'>Logout</a> </p>
					 </div>
					 ";
				?>
				</div>
			</div>
			<!-- User timeline ends-->
			<!-- Content timeine starts-->
			<div id="content_timeline">
				</br>
				<!---Header block to display friends count starts-->
				<?php
				$get_members_count = pg_query("select count(*) as total from USER_SN");
				$total_members = pg_result($get_members_count,0);
				?>
				<h2>Total members of this site (<?php echo $total_members;?>)</h2> <br/><br/>
				<h4><?php echo $user_name;?>, add new friends:</h4><br/><br/>
				<!---Header block to display friends count ends-->
				
				<?php
				$get_members = "select * from USER_SN";
				$run_members = pg_query($con,$get_members);
				
				while($row = pg_fetch_array($run_members)){
				
				$user_id = $row['user_id'];
				$user_name = $row['user_name'];
				
				$user_image = $row['user_profile_pic'];
				
				
				
				echo "
				<a href='user_profile.php?u_id=$user_id'>
				<img src='../social_network/user/user_images/$user_image' width='150' height='180' title='$user_name' />
				</a>
					 
					 ";	
					 
				}
				
				?>
			</div>
			<!-- Content timeine ends-->
		</div>
		<!-- Content Ends-->
	</div>
	<!-- Container Ends -->

</body>

</html>

<?php } ?>