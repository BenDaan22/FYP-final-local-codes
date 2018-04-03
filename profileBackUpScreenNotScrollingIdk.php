<?php
	include('logIn.php');

	//connect to the website database through require_once()
	require_once('connect.php');

	//when button btn-edit is pressed to change the user's profile details
	if(isset($_POST['btn-edit']))
	{
		//echo "You pressed edit button";
		//submit the information to the database
		//to prevent SQL injection use mysqli_real_escape_string()
		$email = mysqli_real_escape_string($conn, $_POST['email']);
		$gender = mysqli_real_escape_string($conn, $_POST['gender']);
		$phone = mysqli_real_escape_string($conn, $_POST['phone']);
		$username = $_SESSION['username'];

		//create a query to handle user registration into mysql database
		$queryUpdate = "UPDATE USER SET `email` ='$email', `gender` ='$gender', `phone` ='$phone' WHERE `username` ='$username' ";

		echo "<pre>Debug: $queryUpdate</pre>";

		$result = mysqli_query($conn,$queryUpdate);

		if (false == $result) 
		{
			//make an alert box if failed
			printf("error: %s\n", mysqli_error($conn));
			echo'<script type="text/javascript">alert("Update failed");</script>';
		}
		else
		{
			//make an alert box if succeeded
			echo'<script type="text/javascript">alert("Update successfull");</script>';
		}
		
	}

	//to make the request 
	if(isset($_POST['btn-req']))
	{
		$reqType = mysqli_real_escape_string($conn, $_POST['reqType']);
		$name = mysqli_real_escape_string($conn, $_POST['name']);
		$holidayFrom = mysqli_real_escape_string($conn, $_POST['holidayFrom']);
		$holidayTo = mysqli_real_escape_string($conn, $_POST['holidayTo']);
		$workReturn = mysqli_real_escape_string($conn, $_POST['workReturn']);
		$currentDate = mysqli_real_escape_string($conn, $_POST['currentDate']);
		$comment = mysqli_real_escape_string($conn, $_POST['comment']);
		$username = $_SESSION['username'];


		$offSql = "INSERT INTO `holiday` (`holidayType`, `name`, `holidayFrom`, `holidayTo`, `workReturn`, `currentDate`, `description`) VALUES ('$reqType', '$name', '$holidayFrom', '$holidayTo', '$workReturn', '$currentDate', '$comment')";

		echo "<pre>Debug: $offSql</pre>";

		$result = mysqli_query($conn,$offSql);

		if (false == $result) 
		{
			//make an alert box if failed
			printf("error: %s\n", mysqli_error($conn));
			echo'<script type="text/javascript">alert("Request failed");</script>';
		}
		else
		{
			//make an alert box if succeeded
			echo'<script type="text/javascript">alert("Request successfull");</script>';
		}

	}
?>
<!DOCTYPE html>
<html>
	<head>
		<title>Schedule Me Out</title>

		<!--for jQuery -->
		<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>


		<!-- script for off work request -->
		<script>
			$(document).ready(function()
			{
			    $("#requestOff").click(function(e){
			        $("#panel").slideToggle("slow");
			    });
			});
		</script>

		<style>
			/*for sliding panel of off work request   */
			#requestOff
			{
				cursor:pointer;
			}
			#panel
			{
				padding: 50px;
				display: none;
			}
		</style>

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
		        <li><a href="logOut.php">HOME</a></li>
		        <li><a href="#profile">PROFILE </a></li>
		        <li><a href="#record">RECORDS</a></li>
		        <li><a href="#offWork">REQUEST </a></li>
		        <li><a href="logOut.php">LOG OUT</a></li>
		      </ul>
		    </div>
		  </div>
		</nav>

		<div class="jumbotron text-center">
		  <h1>Profile</h1>
		  <p>
		  	<?php
		  		//printout who is logged in
				if (isset($_SESSION['username']))
				{
					//check which user is logged in
					$username = $_SESSION['username'];
					echo "Welcome:". " " . $username;
				}

			?>
		  </p>
		</div>

		<!--this will show the details of the current logged in user -->
		<div id="profile" class="table-responsive fluid_container">
			<div class="row">
				<h2 align="center">Personal Details</h2>
				<?php
					$username = $_SESSION['username'];

					//execute the SQL query and return records
					$search ="SELECT username,firstname,lastname,email,gender,phone FROM USER WHERE username ='$username'";
			  		$result = mysqli_query($conn, $search);	

			  		
			  		if (!$result) {
						echo "Could not successfully run query ($search) from Database: " . mysql_error();
					    exit;
					}
					
					if (mysqli_num_rows($result) == 0) {
					    echo "No rows found";
					    exit;
					}
					
					if ($result) 
					{
						while ($row = mysqli_fetch_assoc($result)) 
						{
							//get the results from database
							$displayFirstname = $row["firstname"];
							$displayLastname =$row["lastname"];
							$displayEmail =  $row["email"];
							$displayGender =  $row["gender"];
							$displayPhone =  $row["phone"];

						}
					}
				?>

				<table class="table table-striped" align="center" style="width: 450px; margin: auto;">
					<tr>
						<td>
							Name:
						</td>
						<!-- merge first name and lastname together -->
						<td> <?php echo $displayFirstname . " " . $displayLastname?></td>
					</tr>

					<tr>
						<td>
							Email:
						</td>
						<td>
							<?php echo $displayEmail ?></td>
					</tr>

					<tr>
						<td>
							Gender:
						</td>
						<td> 
							
							<?php 
								echo $displayGender
							?>
						</td>
					</tr>
					<tr>
						<td>
							Phone:
						</td>
						<td> <?php echo $displayPhone ?></td></td>
					</tr>

				</table>
			</div> <!--div row -->
			<!-- drop down table for update details -->
			<button class="btn btn-default dropdown-toggle" type="button" id="dropdownMenu1" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
			    Edit Profile
			  <span class="caret"></span>
			</button>

			<ul class="dropdown-menu scrollable-menu" role="menu" aria-labelledby="dropdownMenu1" style="width: 200px">
				<form method="POST">
					<input type="text" class="form-control" id="email" maxlength="20" name="email" placeholder="Enter email">
					<input type="text" class="form-control" id="gender" maxlength="20" name="gender" placeholder="Enter Gender">
					<input type="text" class="form-control" id="phone" maxlength="20" name="phone" placeholder="Enter Phone number"> 
				
					<button type="submit" class="btn btn-default pull-right" name="btn-edit">Update</button>
				</form>
			</ul>
		</div> <!--profile div -->

		


		<!--make a holiday or day off request -->
		<div id="offWork" class="fluid_container">
			<div id="requestOff"> <h2 align="center">CREATE REQUEST </h2></div>
			<form method="POST">
				<div id="panel">
					<div align="center">
						<img src="requesticon.png" height="90" width="110">
					</div>	
					<div class="row" align="center">
						<div class="col-sm-4" class="form-control">
							REQUEST TYPE:
							<input style="width: 210px;" type="text" class="form-control" id="reqType" maxlength="20" name="reqType" placeholder="e.g Holiday, sick leave, etc.">
						</div>

						<div class="col-sm-4" class="form-control">
							NAME (captital letters):
							<input style="width: 210px;" type="text" class="form-control" id="name" maxlength="20" name="name" placeholder="Enter name">
						</div>

						<div class="col-sm-4" class="form-control">
							CURRENT DATE: <input style="width: 210px;" type="text" class="form-control" id="currentDate" maxlength="20" name="currentDate" placeholder="e.g 2017-08-02">
						</div>

					</div>

					<p align="center"><strong>I Request the following Dates (YYYY-MM-DD) </strong></p>
					<div class="row" align="center" style="padding-bottom: 50px;">
						<div class="col-sm-4" class="form-control">
							FROM: <input style="width: 210px;" type="text" class="form-control" id="holidayFrom" maxlength="20" name="holidayFrom" placeholder="Enter start request">
						</div>

						<div class="col-sm-4" class="form-control">
							TO: <input style="width: 210px;" type="text" class="form-control" id="holidayTo" maxlength="20" name="holidayTo" placeholder="Enter end request">
						</div>

						<div class="col-sm-4" class="form-control">
							RETURN TO WORK DATE: <input style="width: 210px;" type="text" class="form-control" id="workReturn" maxlength="20" name="workReturn" placeholder="Enter back to work date">
						</div>

					</div>
					<p align="center"><strong>I understand that if I do not return to work on the date agreed on this application form,</strong></p>
					<p align="center"><strong>this will be regarded as a serious misconduct, which could lead to my dismissal.</strong></p>

					<div class="row" style="padding-bottom: 50px; margin-left: 0px;">
						REASONS FOR REQUEST: <br>
						<textarea name='comment' id='comment' class="form-control" rows="4"></textarea>
					</div>

					<button type="submit" class="btn btn-default pull-right" name="btn-req">Submit</button>
				</div>
			</form>

		</div>

		<div id="record" class="table-responsive fluid_container">
			<h2 align="center">REQUEST RECORDS</h2>

			<table class="table table-striped" align="center" style="width: 450px; margin: auto;">
				<th>
					Request Type:
				</th>
				<th>
					From:
				</th>
				<th>
					To:
				</th>
				<th>
					Return:
				</th>

				<?php
					$username = $_SESSION['username'];

					//execute the SQL query and return records
					$query ="SELECT * FROM HOLIDAY where name = '$username'";
					//$query = "SELECT * from user JOIN holiday where username= '$username'"; 
				  	$result = mysqli_query($conn, $query);	

		  		
				  	if (!$result) {
						echo "Could not successfully run query ($query) from Database: " . mysql_error();
					    exit;
					}
							
					if (mysqli_num_rows($result) == 0) {
					    echo "No rows found";
					    exit;
					}
							
					if ($result) 
					{
						while ($row = mysqli_fetch_assoc($result)) 
						{
							//get the results from database
							$displayReqType = $row["holidayType"];
							$displayHolidayFrom =  $row["holidayFrom"];
							$displayHolidayTo =  $row["holidayTo"];
							$displayWorkReturn =  $row["workReturn"];

							echo "<tr>";
						    
						    echo "<td align='center'>". $displayReqType . "</td>";
						    echo "<td align='center'>". $displayHolidayFrom . "</td>";
						    echo "<td align='center'>". $displayHolidayTo . "</td>";
						    echo "<td align='center'>". $displayWorkReturn . "</td>";

						    echo "</tr>";
						}
					}
				?>
			</table>
		</div> <!-- record end div -->

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