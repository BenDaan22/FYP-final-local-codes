<!--
	Author: Ben Joshua Daan
	Student ID: C13358586

	Final Year Project: Schedule_Me_Out

	Description:
	Displays the employee's personal details and will have the option to update their details if desired to
	Allows to create requests with following request details
	Allows to display past requests the current logged in user has made, along witht request status

-->


<?php
	//call the logIn.php to keep track of the current logged in user
	include('logIn.php');

	//connect to the website database through require_once()
	require_once('connect.php');

	//when button btn-edit is pressed to change the user's profile details
	if(isset($_POST['btn-edit']))
	{
		//echo "You pressed edit button";
		//submit the information to the database
		//to prevent SQL injection use mysqli_real_escape_string()
		$gender = mysqli_real_escape_string($conn, $_POST['gender']);
		$phone = mysqli_real_escape_string($conn, $_POST['phone']);
		$address = mysqli_real_escape_string($conn, $_POST['address']);
		$county = mysqli_real_escape_string($conn, $_POST['county']);
		$workArea = mysqli_real_escape_string($conn, $_POST['workArea']);
		$position = mysqli_real_escape_string($conn, $_POST['position']);
		$username = $_SESSION['username'];

		//create a query to handle user registration into mysql database
		$queryUpdate = "UPDATE USER SET `gender` ='$gender', `phone` ='$phone', `address` ='$address', `county` ='$county', `workArea` ='$workArea', `position` ='$position' WHERE `username` ='$username' ";
		
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

		//mysqli_real_escape_string(); prevents MySQLi Injection
		$reqType = mysqli_real_escape_string($conn, $_POST['reqType']);
		$name = mysqli_real_escape_string($conn, $_POST['name']);
		$holidayFrom = mysqli_real_escape_string($conn, $_POST['holidayFrom']);
		$holidayTo = mysqli_real_escape_string($conn, $_POST['holidayTo']);
		$workReturn = mysqli_real_escape_string($conn, $_POST['workReturn']);
		$currentDate = mysqli_real_escape_string($conn, $_POST['currentDate']);
		$comment = mysqli_real_escape_string($conn, $_POST['comment']);
		$username = $_SESSION['username'];

		//insert the request details into the HOLIDAY table in mysql
		$offSql = "INSERT INTO `HOLIDAY` (`holidayType`, `name`, `holidayFrom`, `holidayTo`, `workReturn`, `currentDate`, `description`,`requestStatus`) VALUES ('$reqType', '$name', '$holidayFrom', '$holidayTo', '$workReturn', '$currentDate', '$comment', '')";

		//for debugging purposes
		echo "<pre>Debug: $offSql</pre>";

		/*end of creating a session for the user through their name  */

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

	//button functionality for viewing past requests
	if (isset($_POST['btn-nameReq']))
	{
		$nameRequest = mysqli_real_escape_string($conn, $_POST['nameRequest']);

		/*this will help to retain the past requests that the logged in user through entering their name and creating a session*/
		$query = "SELECT * from HOLIDAY where name = '$nameRequest'";
		echo "<pre>Debug: $query</pre>";

		$val = mysqli_query($conn, $query);
		echo $count= mysqli_num_rows($val);

		//if it finds name of the current log in user correctly it returns 1
		if ($count > 0) 
		{
			//create the session for the user logged in
			$_SESSION['nameRequest'] = $nameRequest; // Initializing Session
							
		}
	}
?>
<!DOCTYPE html>
<html>
	<head>
		<title>Schedule Me Out</title>

		<!--for jQuery -->
		<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>


		<!-- script for off work request slide animation-->
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
		        <li><a href="#myPage">HOME</a></li>
		        <li><a href="#profile">PROFILE </a></li>
		        <li><a href="#availableWork">AVAILABLE WORK</a></li>
		        <li><a href="#offWork">REQUEST </a></li>
		        <li><a href="#record">RECORDS</a></li>
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
					$search ="SELECT username,firstname,lastname,email,gender,phone,address,county,workArea,position FROM USER WHERE username ='$username'";
			  		$result = mysqli_query($conn, $search);

			  		
			  		//if the query can not be executed
			  		if (!$result) {
						echo "Could not successfully run query ($search) from Database: " . mysql_error();
					    exit;
					}
					
					//if there is nothing in the database
					if (mysqli_num_rows($result) == 0) {
					    echo "Please fill in your details";
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
							$displayAddress =  $row["address"];
							$displayCounty =  $row["county"];
							$displayWorkArea =  $row["workArea"];
							$displayPosition =  $row["position"];


						}//end while
					}//end if
				?>
			
				<!-- table to display user personal details -->
				<table class="table table-striped" align="center" style="width: 450px; margin: auto;">
					<!-- Make a table to show the employee's personal details -->
					<tr>
						<td>
							<strong>Name:</strong>
						</td>
						<!-- merge first name and lastname together -->
						<td> <?php echo $displayFirstname . " " . $displayLastname?></td>

						<td>
							<strong>Address:</strong>
						</td>
						<!-- merge first name and lastname together -->
						<td> <?php echo $displayAddress ?></td>
						
					</tr>

					<tr>
						<td>
							<strong> Email: </strong>
						</td>
						<td> <?php echo $displayEmail ?></td>

						<td>
							<strong>County:</strong>
						</td>
						<!-- merge first name and lastname together -->
						<td> <?php echo $displayCounty ?></td>
					</tr>

					<tr>
						<td>
							<strong> Gender: </strong>
						</td>
						<td> <?php echo $displayGender ?> </td>

						<td>
							<strong>Work Area:</strong>
						</td>
						<!-- merge first name and lastname together -->
						<td> <?php echo $displayWorkArea ?></td>

					</tr>
					<tr>
						<td>
							<strong> Phone: </strong>
						</td>
						<td> <?php echo $displayPhone ?></td></td>

						<td>
							<strong>Position:</strong>
						</td>
						<!-- merge first name and lastname together -->
						<td> <?php echo $displayPosition ?></td>

					</tr>
				</table>
			
			</div> <!--div row -->
			<!-- drop down table for update details -->
			<button class="btn btn-default dropdown-toggle" type="button" id="dropdownMenu1" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
			    Edit Profile
			  <span class="caret"></span>
			</button>

			<!-- drop down menu to update the personal details -->
			<ul class="dropdown-menu scrollable-menu" role="menu" aria-labelledby="dropdownMenu1" style="width: 230px">
				<form method="POST">
					<input type="text" class="form-control" id="gender" maxlength="50" name="gender" placeholder="Enter Gender">
					<input type="text" class="form-control" id="phone" maxlength="50" name="phone" placeholder="Enter Phone number">
					<input type="text" class="form-control" id="address" maxlength="90" name="address" placeholder="Enter Address"> 
					<input type="text" class="form-control" id="county" maxlength="30" name="county" placeholder="Enter County"> 
					<input type="text" class="form-control" id="workArea" maxlength="30" name="workArea" placeholder="Enter Work Area"> 
					<input type="text" class="form-control" id="position" maxlength="30" name="position" placeholder="Enter Position">  
				
					<button type="submit" class="btn btn-default pull-right" name="btn-edit">Update</button>
				</form>
			</ul>
		</div> <!--profile div -->

		<!--make a holiday or day off request -->
		<div id="offWork" class="fluid_container">
			<!--to make the sliding animation for the panel -->
			<div id="requestOff"> <h2 align="center">CREATE REQUEST </h2></div>
			<form method="POST">
				<div id="panel">
					<!-- Show the create request form -->
					<div align="center">
						<img src="requesticon.png" height="90" width="110">
					</div>	
					<div class="row" align="center">
						<div class="col-sm-4" class="form-control">
							REQUEST TYPE:
							<input style="width: 210px;" type="text" class="form-control" id="reqType" maxlength="20" name="reqType" placeholder="e.g Holiday, sick leave, etc.">
						</div>

						<div class="col-sm-4" class="form-control">
							FULL NAME (captital letters):
							<input style="width: 210px;" type="text" class="form-control" id="name" maxlength="20" name="name" placeholder="Enter full name">
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

					<!-- when this button is clicked call the function and pass the details -->
					<button type="submit" class="btn btn-default pull-right" name="btn-req">Submit</button>
				</div>
			</form>

		</div>

		<!-- show the past requests the user has made -->
		<div id="record" class="table-responsive fluid_container">
			<h2 align="center">REQUEST RECORDS</h2>
			<form method="POST">

				<input type="text" style="width: 140px; margin: 0 auto;" class="form-control" id="nameRequest" maxlength="20" name="nameRequest" placeholder="Enter full name">

				<button type="submit" class="btn btn-default pull-right" name="btn-nameReq">Submit</button>
			</form>
			<div class="row fluid_container table-responsive">
				<table id="record" class="fluid_container table table-striped" align="center" style="width: 450px; margin: auto;">
					<!-- Show the past request records in a table -->
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
					<th>
						Request Status:
					</th>

					<?php

						/* to retain the username and the name of the currently logged in user */
						$username = $_SESSION['username'];
						$nameRequest = $_SESSION['nameRequest'];
						

						//execute the SQL query and return records
						//this is for the join Query
						//takes in the current logged in user's username and name for a join query to find past requests.
						$joinQuery = "SELECT * from USER JOIN HOLIDAY where username= '$username' AND name='$nameRequest' ";
					  	$result = mysqli_query($conn, $joinQuery);	

			  			//echo "<pre>Debug: $joinQuery</pre>";

					  	if (!$result) {
							echo "Could not successfully run query ($joinQuery) from Database: " . mysql_error();
						    exit;
						}
								
						if (mysqli_num_rows($result) == 0) {
						    echo "No Past requests found";
						    exit;
						}
								
						if ($result) 
						{
							//while there is data to be fetch
							while ($row = mysqli_fetch_assoc($result)) 
							{
								//get the results from database
								$displayReqType = $row["holidayType"];
								$displayHolidayFrom =  $row["holidayFrom"];
								$displayHolidayTo =  $row["holidayTo"];
								$displayWorkReturn =  $row["workReturn"];
								$displayRequestStatus =  $row["requestStatus"];

								echo "<tr>";
							    
							    echo "<td align='center'>". $displayReqType . "</td>";
							    echo "<td align='center'>". $displayHolidayFrom . "</td>";
							    echo "<td align='center'>". $displayHolidayTo . "</td>";
							    echo "<td align='center'>". $displayWorkReturn . "</td>";
							    echo "<td align='center'>". $displayRequestStatus . "</td>";

							    echo "</tr>";
							}
						}
					?>

				</table>
			</div> <!-- div row end -->
		</div> <!-- record end div -->

		<!-- div to see if there are any available work for employees to accept -->
		<div id="availableWork" class="table-responsive fluid_container">
			<h2 align="center">AVAILABLE WORK</h2>

			<div class="row fluid_container">
				<table id="availableWork" class="fluid_container table table-striped" align="center" style="width: 450px; margin: auto;"">
				
					<!-- available work details -->
					<th>
							Start Time:
					</th>

					<th>
							End Time:
					</th>

					<th>
							Work Day:
					</th>

					<th>
							Work Date:
					</th>

					<th>
							Total Hours:
					</th>

					<?php
						//query to get all the available work details
						$workQuery = "SELECT * from SCHEDULE WHERE `scheduleStatus` = 'NOT TAKEN' ";

						$workResult = mysqli_query($conn, $workQuery);

						//if there are no requests in the database
						if (!$workResult) {
							echo "Could not successfully run query ($workQuery) from Database: " . mysql_error();
						    exit;
						}

						if (mysqli_num_rows($workResult) == 0) {
					    	echo "No Available work found";
						    exit;
						}

						//if there are available work show in a table
						if ($workResult) {
							while ($row = mysqli_fetch_assoc($workResult)) 
							{
								//get the results from database
								$dayNum = $row["dayNum"];
								$scheduleStatus = $row["scheduleStatus"];
								$takerName = $row["takerName"];
								$startTime =  $row["startTime"];
								$endTime =  $row["endTime"];
								$workDays =  $row["workDays"];
								$workDate = $row["workDate"];
								$totalHours = $row["totalHours"];

								echo "<tr>";	

								//show the available work details in a table
								echo "<td align='center'>". $startTime . "</td>";
								echo "<td align='center'>". $endTime . "</td>";
								echo "<td align='center'>". $workDays . "</td>";
								echo "<td align='center'>". $workDate . "</td>";
								echo "<td align='center'>". $totalHours . "</td>";

								//button to take the request
							    echo "<td align='center'>". '<form method="GET">
								    		<button type="submit" class="btn btn-default pull-right" value="'.$row['dayNum'].'" name="take"> TAKE </button>
								    		</form>' . "</td>";
								echo "</tr>";	


							} //end while


						}//end if

						//if the employee decides to take the available work
						if (isset($_GET['take'])) 
						{
							//will be used to show the employee's name when the work is accepted
							$nameRequest = $_SESSION['nameRequest'];

							$id = $_GET['take']; //GET the row id thats been clicked

							$takeQuery = "UPDATE SCHEDULE SET `scheduleStatus` = 'TAKEN', `takerName` = '$nameRequest' WHERE `dayNum` = '$id' ";

							$takeStatus =  mysqli_query($conn,$takeQuery);


							echo "<pre>Debug: $takeStatus</pre>";

							if (false == $takeStatus) 
							{
								//make an alert box if succeed
								printf("error: %s\n", mysqli_error($conn));
									echo'<script type="text/javascript">alert("Accepting of work failed");</script>';
							}
							else
							{

								//make an alert box if failed
								echo'<script type="text/javascript">alert("You Have Accepted the Work");</script>';

								//to reload the web page
								echo '<script>location.href="profile.php"; </script>';


							}


						}//end if for GET take

					?>
				</table>

			</div>

		</div> <!-- end div for available work -->
		

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