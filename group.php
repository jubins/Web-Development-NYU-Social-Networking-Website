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
		<title>Gallery NYU Social Network </title>
		<link rel="stylesheet" href="styles/home_style.css" media="all"/>
	</head>

<body>

	<!-- Container Starts -->
	<div class="container">
		<div id="head_wrap">
			<div id="header">
				<ul id="menu">
					<li><a href="home.php" >Home</a></li>
					<li><a href="members.php">Friends</a></li>
					<strong>Gallery</strong>
					
					<?php
					$get_groups = "select * from GROUP_SN";
					$run_groups = pg_query($con,$get_groups);
					
					while($row = pg_fetch_array($run_groups)){
						$group_id = $row['group_id'];
						$group_name = $row['group_name'];
						
						echo "<li> <a href='group.php?topic=$group_id'>$group_name</a> </li>";
					}
					?>
				</ul>
				<form method="get" action="results.php" id="form1">
					<img id="shortlogo" src="images\shortestlogo.png" height="40" width="70" />
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
					 <p><strong>Last Login</strong>  : $last_login</p>
					 <p><strong>Member Since</strong>: $registered_date</p>
					 <br/><br/>
					 <p><a href='my_messages.php'>Messages</a> </p>
					 <!-- <p><a href='my_posts.php'>My Posts</a> </p>
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
				<h3 id="welcome_header">Hi,  <?php echo $user_name;?>!</h3>
				<form action="home.php?id=<?php echo $user_id;?>" method="post" id="f" >
				</br>					
					<input type="text"  name="title" placeholder="What's on your mind!" required="required"/></br></br>
					<textarea name="content" placeholder="Want to describe it..."></textarea><br/>
					<button type="submit" name="sub"><strong>Post to the wall</strong></button>
				</form></br>
				<?php insertPost() ?>
				<h3 id="post_header">All posts in this category!</h3>
				<?php get_Cats();?>
				
			</div>
			<!-- Content timeine ends-->
		</div>
		<!-- Content Ends-->
	</div>
	<!-- Container Ends -->

</body>

</html>

<?php } ?>