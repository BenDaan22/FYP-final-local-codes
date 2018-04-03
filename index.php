<!--
	Author: Ben Joshua Daan
	Student ID: C13358586

	Final Year Project: Schedule_Me_Out

	Description:
	The initial page delivered to any user, or in other words the home page
	will handle user registration and logging in of the user

-->


<?php
	//session_start();

	//connect to the website database through require_once()
	require_once('connect.php');

	include('logIn.php');

	// just for debugging
	/*
	echo '<pre>';
	print_r($_POST);
	echo '</pre>';
	*/
	
	$emptylabels= "";
	//send the variables to the database when the submit button to sign up is pressed
	if(isset($_POST['btn-reg']))
	{
		if (empty($_POST['username']) || empty($_POST['password']) || empty($_POST['firstname']) || empty($_POST['lastname']) || empty($_POST['email']) || empty($_POST['checkPass'])) 
		{
			$emptylabels ="A field is left empty";
		}

		else
		{
			//submit the information to the database
			//to prevent SQL injection use mysqli_real_escape_string()
			$username = mysqli_real_escape_string($conn, $_POST['username']);
			$firstname = mysqli_real_escape_string($conn, $_POST['firstname']);
			$lastname = mysqli_real_escape_string($conn, $_POST['lastname']);
			$email = mysqli_real_escape_string($conn, $_POST['email']);
			$password = md5($_POST['password']);
			$againpass = md5($_POST['checkPass']);

			//create a query to handle user registration into mysql database
			$sql = "INSERT INTO `USER` (`username`, `firstname`, `lastname`, `email`, `password`, `againpassword`, `gender`, `phone`,`address`, `county`, `workArea`, `position`) VALUES ('$username', '$firstname', '$lastname', '$email', '$password', '$againpass', '', '','', '','', '')";

			echo "<pre>Debug: $sql</pre>\m";

			$result = mysqli_query($conn,$sql);

			if (false == $result) 
			{
				//make an alert box if failed
				printf("error: %s\n", mysqli_error($conn));
				echo'<script type="text/javascript">alert("User registration failed");</script>';
			}
			else
			{
				//make an alert box if succeeded
				echo'<script type="text/javascript">alert("User registration successfull");</script>';
			}
		}
	}
	

?>
<!DOCTYPE html>
<html>
  <head>
  	<title>Schedule Me Out</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
  	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
  	<link href="https://fonts.googleapis.com/css?family=Montserrat" rel="stylesheet" type="text/css">
  	<link href="https://fonts.googleapis.com/css?family=Lato" rel="stylesheet" type="text/css">
  	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>
  	<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>

  	<!-- call the css body style -->
	<link rel="stylesheet" type="text/css" href="style.css">
		
  </head>
  <body id="myPage" data-spy="scroll" data-target=".navbar" data-offset="60">
	<!-- Code gotten from bootstrap website -->
	<nav class="navbar navbar-default navbar-fixed-top">
	  <div class="container">
	    <div class="navbar-header">
	      <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#myNavbar">
	        <span class="icon-bar"></span>
	        <span class="icon-bar"></span>
	        <span class="icon-bar"></span>

	      </button>
	      <a class="navbar-brand" href="#myPage">Schedule Me Out</a>
	    </div>
	    <div class="collapse navbar-collapse" id="myNavbar">
	      <ul class="nav navbar-nav navbar-right">
	        <li><a href="#about">ABOUT</a></li>
	        <li><a href="#myPage">HOME</a></li>
	        <li><a href="#signUp">SIGN UP</a></li>
	        <li><a href="#logIn">LOG IN</a></li>
	      </ul>
	    </div>
	  </div>
	</nav>

	<div class="jumbotron text-center">
	  <h1>Company</h1>
	  <p>Welcome</p>
	</div>

	<!--about section-->
	<div id="about" class="fluid_container">
		<div class="row">
			<div class="col-sm-8">
				<h2>About the Website</h2>
				<h4>The website provides a way for the employee to request a day off or holiday from work online. Potentially cutting the need to be in the workplace physically to create requests. <br>With the help of this website employees and employers will be able to inform each other regarding each requests.</h4>
			</div>
			<div class="col-sm-4">
				<img src="questionmark.png">
			</div>
		</div>
	</div>

	<!--sign up section -->
	<div id="signUp" class="fluid_container">
		<h2 align="center">Sign Up</h2>
		<p align="center"> It's free</p>
		
		<form method="POST">
			<div class="row">
				<div class="col-sm-4 form-group">
					<input type="text" class="form-control" id="username" name="username" maxlength="20" placeholder="Enter username">
				</div>

				<div class="col-sm-4 form-group">
					<input type="text" class="form-control" id="firstname" name="firstname" placeholder="Enter Firstname">
				</div>

				<div class="col-sm-4 form-group">
					<input type="text" class="form-control" id="lastname" name="lastname" placeholder="Enter Lastname">
				</div>
			</div>


			<div class="row" >
				<div class="col-sm-4 form-group">
					<input type="text" class="form-control" id="email" name="email" placeholder="Enter email">
				</div>

				<div class="col-sm-4 form-group">
					<input type="password" class="form-control" id="password" maxlength="20" name="password" placeholder="Enter password">
				</div>

				<div class="col-sm-4 form-group">
					<input type="password" class="form-control" id="checkPass" maxlength="20" name="checkPass" placeholder="Re-enter password">
				</div>
			</div>

			<button type="submit" class="btn btn-default pull-right" name="btn-reg">Submit</button>

			<div align="center" style="margin:  auto; padding-top: 10px;">
				<b style="color: red;">
				<?php
					echo $emptylabels;
				?>
				</b>
			</div>

		</form>

	</div>
	<!--
	<button onclick="window.location.href='/trytry.html'"> Test lng </button>
	-->

	<div id="logIn" class="fluid_container">
		<h2 align="center">Log In</h2>
		<p align="center">Please Enter your details below</p>

		<form method="POST">
			
			<div class="row">
				<div align="center" style="width: 250px; margin: auto;">
					<input type="text" class="form-control" id="username" maxlength="20" name="username" placeholder="Enter username">
				</div>
			</div>
			

			<div class="row" style="padding-bottom: 10px;">
				<div align="center" style="width: 250px; margin: auto; padding-top: 15px;">
					<input type="password" class="form-control" id="password" maxlength="20" name="password" placeholder="Enter password">
				</div>
			</div>

			<button type="submit" class="btn btn-default pull-right" name="btn-login">Log In</button>


			<!-- call the errorMessage variable if the input fields are left empty -->
			<div align="center" style="margin:  auto; padding-top: 10px;">
				<b style="color: red;">
				<?php
					echo $errorMessage;
				?>
				</b>
			</div>
			
		</form>
		
	</div>
	

	<!-- allows for smooth transitioning from one header to another -->
	<script>
		$(document).ready(function(){
		  // Add smooth scrolling to all links in navbar + footer link
		  $(".navbar a, footer a[href='#myPage']").on('click', function(event) {
		    // Make sure this.hash has a value before overriding default behavior
		    if (this.hash !== "") {
		      // Prevent default anchor click behavior
		      event.preventDefault();

		      // Store hash
		      var hash = this.hash;

		      // Using jQuery's animate() method to add smooth page scroll
		      // The optional number (900) specifies the number of milliseconds it takes to scroll to the specified area
		      $('html, body').animate({
		        scrollTop: $(hash).offset().top
		      }, 900, function(){
		   
		        // Add hash (#) to URL when done scrolling (default click behavior)
		        window.location.hash = hash;
		      });
		    } // End if
		  });
		  
		  $(window).scroll(function() {
		    $(".slideanim").each(function(){
		      var pos = $(this).offset().top;

		      var winTop = $(window).scrollTop();
		        if (pos < winTop + 600) {
		          $(this).addClass("slide");
		        }
		    });
		  });
		})
	</script>


  </body>
</html>