<!--Content area starts-->
		<div id="content">	
			<div>
				<img src="images/NYUGate.png" style="float:left; margin-left:-40px"/>
			</div>
				<div id="form2" >
					<form action="" method="post">
					
					<h2>New to NYU Social Network?</h2>
					
						<table>
							<tr>
								<td align="right">Name:</td>
								<td><input type="text" name="u_name" placeholder="Enter your name" required="required"</td>
							</tr>
							<tr>
								<td align="right">Password:</td>
								<td><input type="password" name="u_pass" placeholder="Enter your password" required="required"</td>
							</tr>
							<tr>
								<td align="right">Email:</td>
								<td><input type="email" name="u_email" placeholder="Enter your email" required="required"</td>
							</tr>
							<tr>
								<td align="right">Country:</td>
								<td>
								<select name="u_country" required="required">
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
								<td align="right" required="required">Gender:</td>
								<td>
								<select name="u_gender">
									<option>Select Gender</option>
									<option>Male</option>
									<option>Female</option>
								</select>
								</td>
							</tr>
						
							<tr>
								<td align="right" required="required">Date of Birth:</td>
								<td><input type="date" name="u_birthday" placeholder="MM/DD/YYYY"></td>
							</tr>
						
							<tr>
								<td colspan="6">
								</br><button name="sign_up">Sign Up</button>
								</td>
							</tr>
						
						</table>
					</form>
					<?php  
						include("user_insert.php");
					?>
				</div>
			
		</div>
		<!--Content area ends-->
		
	</div>
	<!--Container ends-->