<?php
session_start();
include("includes/connection.php");
include("funtions/functions.php");
include("user_insert.php");

if(!isset($_SESSION['user_email'])){
	header("location:index.php");
}
else{
?>
<!DOCTYPE html>
<html>
	<head>
		<title>Edit Posts NYU Social Network </title>
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
					<li><a href="members.php">Members</a></li>
					<li><a href="my_friends.php">Friends</a></li>
					<li><a href="gallery.php" >Gallery</a></li>
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
				
				$user_posts = "select * from post_sn where user_id='$user_id' " ;
				$run_posts = pg_query($con,$user_posts);
				$posts = pg_num_rows($run_posts);
				
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
					 <!-- <p><a href='my_posts.php?u_id=$user_id' style='text-decoration:underline'>My Posts ($posts)</a> </p>
					 -->
					 <p><a href='edit_profile.php?u_id=$user_id'>Settings</a> </p>
					 <p><a href='logout.php'>Logout</a> </p>
					 </div>
					 ";
				?>
				</div>
			</div>
			<!-- User timeline ends-->
			<!-- Content timeine starts-->
			<div id="content_timeline">
				<?php 
					if(isset($_GET['post_id'])){
						$get_id = $_GET['post_id'];
						
						$get_post = "select * from post_sn  where post_id='$get_id'";
						$run_post = pg_query($con,$get_post);
						$row = pg_fetch_array($run_post);
						
						$post_title = $row['post_title'];
						$post_content = $row['post_message'];
						$post_image = $row['image_name'];
						
					}
				?>
				</br>
				<form action="" method="post">
				</br>
					<h2 style="color:brown">Edit Your Post</h2><br/>
					<div id='editPosts'><br/>
					&nbsp <input type="text" name="title" value="<?php echo "&nbsp $post_title";?>" required="required"/></br></br>
					&nbsp <textarea name="content"> <?php echo "$post_content";?> </textarea><br/><br/>
					&nbsp <input id = "editImageUpload" type="file" name="wall_image" /><br/>
					<?php
						if($post_image == null){
							echo "<p> <br/><br/><br/><br/><br/><br/><br/> &nbsp &nbsp &nbsp &nbsp &nbsp &nbsp &nbsp &nbsp &nbsp
							No image uploaded to preview
							</p>";
						}
						else{
						echo "<img src='../social_network/user/user_images/$post_image'><br/>";
						}
					?>
					</div> <br/>  &nbsp
					<input id="editPostButton" type="submit" name="update" value="Update Post">
				</form></br>
				
				<?php
				if(isset($_POST['update'])){
					$title =$_POST['title'];
					$content = $_POST['content'];
					$postImage = $_POST['wall_image'];
				
					$update_post = "update post_sn set post_title='$title',post_message='$content',image_name='$postImage'
					                where post_id='$get_id'";
					
					$run_update = pg_query($con,$update_post);
					
					if($run_update){
						echo "<script>alert('A Post has been edited!')</script>";
						echo "<script>window.open('home.php','_self')</script>";
					}
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