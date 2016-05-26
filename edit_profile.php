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
		<title>Edit Profile NYU Social Network </title>
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
					<li><a href="home.php">Home</a></li>
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
				$user_pass = $row['user_pass'];
				$user_email = $row['user_email_id'];
				$user_phone = $row['user_phone'];
				$user_birthday = $row['user_birthday'];
				$user_gender = $row['user_gender'];
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
					 <p><a href='edit_profile.php?u_id=$user_id' style='text-decoration:underline'>Settings</a> </p>
					 <p><a href='logout.php'>Logout</a> </p>
					 </div>
					 ";
				?>
				</div>
			</div>
			<!-- User timeline ends-->
			<!-- Content timeine starts-->
			<div id="content_timeline"><br/><br/>
				<h2 style='color:brown'>Change your profile settings</h2>
				<br></br>
				<form action="" method="post" enctype="multipart/form-data" id="f1">
								
						<table>
							<tr>
								<td align="right">Name:</td>
								<td><input id="f1" type="text" name="u_name" required="required" value="<?php echo$user_name;?>"/></td>
							</tr>
							<tr>
								<td align="right">Password:</td>
								<td><input type="text" name="u_pass" required="required" value="<?php echo $user_pass;?>"/></td>
							</tr>
							<tr>
								<td align="right">Email:</td>
								<td><input type="email" name="u_email" enctype="value" required="required" disabled="disabled" value="<?php echo $user_email;?>"/></td>
							</tr>
							<tr>
								<td align="right">Phone:</td>
								<td><input type="tel" name="u_phone" value="<?php echo $user_phone;?>"/></td>
							</tr>
							<tr>
								<td align="right">Date Of Birth:</td>
								<td><input type="date" name="u_birthday" value="<?php echo $user_birthday;?>"/></td>
							</tr>
							<tr>
								<td align="right">Country:</td>
								<td>
								<select name="u_country">
								    <option><?php echo $user_country;?></option>
									<option>Select your country</option>
									<option>Afghanistan</option>
									<option>Bangladesh</option>
									<option>Canada</option>
									<option>China</option>
									<option>Denmark</option>
									<option>Ecuador</option>
									<option>France</option>
									<option>Guam</option>
									<option>Hongkong</option>
									<option>India</option>
									<option>Japan</option>
									<option>Korea</option>
									<option>Poland</option>
									<option>Quatar</option>
									<option>Russia</option>
									<option>Switzerland</option>
									<option>Thailand</option>
									<option>United States</option>
									<option>United Kingdom</option>
									<option>Zimbabwe</option>
								</select>
								</td>
							</tr>
							<tr>
								<td align="right">Gender:</td>
								<td>
								<select name="u_gender" disabled="disabled">
								    <option><?php echo $user_gender;?></option>
									<option>Select Gender</option>
									<option>Male</option>
									<option>Female</option>
								</select>
								</td>
							</tr>
					
						    <tr>
								<td align="right" >Photo:</td>
								<td><input type="file" enctype="multipart/form-data" name="u_image" style="background:white"/></td>
							</tr>
														
							<tr align="right">
								<td colspan="6">
								<br></br>
								<button type="submit" name="update"><strong>Update</strong></button>
								</td>
							</tr>
						<?php updateProfile() ?>
						</table>
					</form>
					
					<!--Privacy settings area starts-->
					</br></br></br>
					<h3 style='color:red'>Change your privacy settings</h3>
					<br>
					<form action="" method="post" enctype="multipart/form-data" id="f1">
								
						<table>
							<tr>
								<td align="right">Personal Details:</td>
								<td>
								<select name="u_personalDataPrivacy">
									<option>Only Me</option>
									<option>Friends</option>
									<option>Everyone</option>
								</select>
								</td>
							</tr>
							
							<tr>
								<td align="right">Timeline: </td>
								<td>
								<select name="u_timelinePrivacy" >
									<option>Only Me</option>
									<option>Friends</option>
									<option>Everyone</option>
								</select>
								</td>
							</tr>
							
							<tr>
								<td align="right">Gallery:</td>
								<td>
								<select name="u_galleryPrivacy">
									<option>Only Me</option>
									<option>Friends</option>
									<option>Everyone</option>
								</select>
								</td>
							</tr>
							
							<tr align="right">
								<td colspan="6">
								<br></br>
								<button type="submit" name="update_privacy"><strong>Change</strong></button>
								</td>
							</tr>
						</table>
						
						<?php updatePrivacy(); ?>
					</form>
					<!--Privacy settings area ends-->
					
			</div>
			<!-- Content timeine ends-->
		</div>
		<!-- Content Ends-->
	</div>
	<!-- Container Ends -->

</body>

</html>

<?php } ?>