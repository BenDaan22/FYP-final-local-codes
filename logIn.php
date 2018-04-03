<!--
	Author: Ben Joshua Daan
	Student ID: C13358586

	Final Year Project: Schedule_Me_Out

	Description:
	Will handle the staff credentials both manager and employee for logging in

-->

<?php
	session_start();

	//for database connection credentials
	include_once 'connect.php';
	
	$errorMessage= '';


	//if the button login is pressed then run commands to check database
	//send the variables to check the database for log in
	if (isset($_POST['btn-login'])) 
	{
		if (empty($_POST['username']) || empty($_POST['password'])) 
		{
			$errorMessage ="Username or password field is empty";
		}

		else
		{
			//create $checkUsername and $checkPassword variables to check database
			$checkUsername = mysqli_real_escape_string($conn, $_POST['username']);
			$checkPassword = md5($_POST['password']);

			// To protect MySQL injection for Security purpose
			/*
			$checkUsername = stripslashes($checkUsername);
			$checkPassword = stripslashes($checkPassword);
			$checkUsername = mysql_real_escape_string($checkUsername);
			$checkPassword = mysql_real_escape_string($checkPassword);
			*/

			echo $checkUsername;
			echo $checkPassword;

			echo "You are in logIn page";
			// SQL query to fetch information of registerd users and finds user match.
			echo $query = "SELECT * FROM `USER` WHERE `username`='$checkUsername' AND password='$checkPassword'" ;
			echo "<pre>Debug: $query</pre>";
			$val = mysqli_query($conn, $query);
			
			echo $count= mysqli_num_rows($val);

			
			//if it finds username and password correctly it returns 1
			if ($count == 1) 
			{
				//create the session for the user logged in
				$_SESSION['username'] = $checkUsername; // Initializing Session

				//check if the logging user is a manger or an employee
				if(strstr($_POST['username'],'Manager'))
				{
					//direct to managerprofile.php file
					header("Location: managerprofile.php"); // Redirecting To Other Page

				}

				else
				{
					//direct to profile.php file
					header("Location: profile.php"); // Redirecting To Other Page
				}
				
			}

			else 
			{
				echo'<script type="text/javascript">alert("Username or password is invalid");</script>';
			}
			
		}
		

		if (isset($_SESSION['username'])) {
			echo'<script type="text/javascript">alert("User already logged in");</script>';
		}
			
	}



?>

