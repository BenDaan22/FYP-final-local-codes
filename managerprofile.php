<!--
	Author: Ben Joshua Daan
	Student ID: C13358586

	Final Year Project: Schedule_Me_Out

	Description:
	Displays the Manager's personal details and allows them to update if desired so
	Displays the reuqests made by the employees, decides whether to Approve or Reject the request

-->


<?php
	//call the logIn.php to keep track of the current logged in user
	include('logIn.php');


	//connect to the website database through require_once()
	require_once('connect.php');

	//when button btn-edit is pressed
	if(isset($_POST['btn-edit']))
	{
		//echo "You pressed edit button";
		//submit the information to the database
		//to prevent SQL injection use mysqli_real_escape_string()
		$email = mysqli_real_escape_string($conn, $_POST['email']);
		$gender = mysqli_real_escape_string($conn, $_POST['gender']);
		$phone = mysqli_real_escape_string($conn, $_POST['phone']);
		$address = mysqli_real_escape_string($conn, $_POST['address']);
		$county = mysqli_real_escape_string($conn, $_POST['county']);
		$workArea = mysqli_real_escape_string($conn, $_POST['workArea']);
		$position = mysqli_real_escape_string($conn, $_POST['position']);
		$username = $_SESSION['username'];

		//create a query to handle user registration into mysql database
		$queryUpdate = "UPDATE USER SET `gender` ='$gender', `phone` ='$phone', `address` ='$address', `county` ='$county', `workArea` ='$workArea', `position` ='$position' WHERE `username` ='$username' ";
		
		//for debugging purposes
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

	//if the work availability button is pressed
	if (isset($_POST['btn-avail'])) 
	{
		//push forward the information to the schedule table
		$workDate = mysqli_real_escape_string($conn, $_POST['workDate']);
		$workDays = mysqli_real_escape_string($conn, $_POST['workDays']);
		$startTime = mysqli_real_escape_string($conn, $_POST['startTime']);
		$endTime = mysqli_real_escape_string($conn, $_POST['endTime']);
		$totalHours = mysqli_real_escape_string($conn, $_POST['totalHours']);

		//insert into the schedule table
		$workQuery = "INSERT INTO SCHEDULE (`scheduleStatus`, `takerName`, `startTime`, `endTime`, `workDays`, `workDate`, `totalHours`) VALUES ('NOT TAKEN', '', '$startTime', '$endTime', '$workDays', '$workDate', '$totalHours');";

		//for debugging purposes
		echo "<pre>Debug: $workQuery</pre>";

		$result = mysqli_query($conn,$workQuery);

		if (false == $result) 
		{
			//make an alert box if failed
			printf("error: %s\n", mysqli_error($conn));
			echo'<script type="text/javascript">alert("Work Creation failed");</script>';
		}
		else
		{
			//make an alert box if succeeded
			echo'<script type="text/javascript">alert("Work Creation successfull");</script>';
		}
	}

?>

<!DOCTYPE html>
<html>
<head>
	<title>Schedule Me Out</title>

	<!--for jQuery -->
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>


	<!-- script for pending requests slide animation-->
	<script>
		$(document).ready(function()
		{
		    $("#slidePending").click(function(e){
		        $("#slidePanel").slideToggle("slow");
		    });
		});

		//for available work hours
		$(document).ready(function()
		{
		    $("#slideAvail").click(function(e){
		        $("#slideContents").slideToggle("slow");
		    });
		});

	</script>

	<style>
		/*for sliding panel of off work request   */
		#slidePending
		{
			cursor:pointer;
		}
		#slidePanel 
		{
			padding: 50px;
			display: none;
		}

		#slideAvail
		{
			cursor:pointer;
		}
		#slideContents
		{
			padding: 50px;
			display: none;
		}
	</style>
</head>
<body>
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
	        <li><a href="#details">DETAILS</a></li>
	        <li><a href="#availWork">AVAILABLE WORK</a></li>
	        <li><a href="#pendReq">REQUESTS</a></li>
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
				$managerUsername = $_SESSION['username'];
				echo "Welcome:". " " . $managerUsername;
			}
		?>
	  </p>
	</div>

	<div id="details" class="table-responsive fluid_container">
		<h2 align="center">Personal Details</h2>

		<?php
			//execute the SQL query and return records
			$search ="SELECT username,firstname,lastname,email,gender,phone,address,county,workArea,position FROM USER WHERE username ='$managerUsername'";
	  		$result = mysqli_query($conn, $search);	

	  		if (!$result) {
				echo "Could not successfully run query ($search) from Database: " . mysql_error();
			    exit;
			}

			if (mysqli_num_rows($result) == 0) {
			    echo "Please fill in your details";
			    exit;
			}


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

			}
		?>

		<!-- table to display user personal details -->
		<table class="table table-striped" align="center" style="width: 450px; margin: auto;">
			<!-- show the personal details of the manager in a table -->
			<tr>
				<td> <strong>Name:</strong> </td>
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
	</div>



	<!--pending requests div -->
	<div id="pendReq" class="table-responsive fluid_container">
		<div id="slidePending"><h2 align="center">PENDING REQUESTS</h2></div>
		<div id="slidePanel">
			<!--contents will be placed here -->
			<div align="center">
				<div class="row fluid_container">
					<!-- Show all the requests made by the employee -->
					<table id="pendReq" class="fluid_container table table-striped" align="center" style="width: 450px; margin: auto;"">
						<th>
							Employee Name:
						</th>

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
							Description:
						</th>


						<!--php code to display the requests made by the employees -->
						<?php
							$requestQuery = "SELECT * from HOLIDAY";

							$requestResult = mysqli_query($conn, $requestQuery);
							//if there are no requests in the database
							if (!$requestResult) {
								echo "Could not successfully run query ($requestQuery) from Database: " . mysql_error();
						    	exit;
							}

							if (mysqli_num_rows($result) == 0) {
						    	echo "No requests found";
						    exit;
							}

							//if there are reuqests
							if ($requestResult) 
							{
								while ($row = mysqli_fetch_assoc($requestResult)) 
								{
									//get the results from database
									$requestID = $row["requestID"];
									$displayEmpName = $row["name"];
									$displayReqType = $row["holidayType"];
									$displayHolidayFrom =  $row["holidayFrom"];
									$displayHolidayTo =  $row["holidayTo"];
									$displayWorkReturn =  $row["workReturn"];
									$displayDescription = $row["description"];

									echo "<tr>";
								    
								    //show the request details in a table
								    echo "<td align='center'>". $displayEmpName . "</td>";
								    echo "<td align='center'>". $displayReqType . "</td>";
								    echo "<td align='center'>". $displayHolidayFrom . "</td>";
								    echo "<td align='center'>". $displayHolidayTo . "</td>";
								    echo "<td align='center'>". $displayWorkReturn . "</td>";
								    echo "<td align='center'>". $displayDescription . "</td>";

								    //button approve pass the request id value to update the request to be approved.
								    //GET will take the id of the row and use it to track which form is approved or rejected
								    echo "<td align='center'>". '<form method="GET">
								    		<button type="submit" class="btn btn-default pull-right" value="'.$row['requestID'].'" name="approve"> APPROVE </button>
								    		</form>' . "</td>";

								    echo "<td align='center'>". '<form method="GET">
								    		<button type="submit" class="btn btn-default pull-right" value="'.$row['requestID'].'" name="reject"> REJECT </button>
								    		</form>' . "</td>";

								
								    echo "</tr>";


								}//end while
							}//end if

							//if the manager has decided to approve the request
							if (isset($_GET['approve'])) 
							{
								$id = $_GET['approve']; //whichever row is clicked then GET the row id
								echo $id;
								
								//when a row is selected then update the status
								$queryStatus = "UPDATE HOLIDAY SET `requestStatus` = 'APPROVED' WHERE `requestID` = '$id' ";

								$statusResult = mysqli_query($conn,$queryStatus);

								echo "<pre>Debug: $statusResult</pre>";

								if (false == $statusResult) 
								{
									//make an alert box if failed
									printf("error: %s\n", mysqli_error($conn));
									echo'<script type="text/javascript">alert("Approving of request failed");</script>';

								}
								else
								{
									//make an alert box if request is approved
									echo'<script type="text/javascript">alert("You have approved the request");</script>';

									//to reload the web page
									echo '<script>location.href="managerprofile.php"; </script>';
								}

								

							}

							//if the manager has decided to reject the request
							if (isset($_GET['reject'])) 
							{
								$id = $_GET['reject']; //whichever row is clicked then GET the row id
								echo $id;
								
								//when a row is selected then update the status
								$queryStatus = "UPDATE HOLIDAY SET `requestStatus` = 'REJECTED' WHERE `requestID` = '$id' ";

								$statusResult = mysqli_query($conn,$queryStatus);

								echo "<pre>Debug: $statusResult</pre>";

								if (false == $statusResult) 
								{
									//make an alert box if failed
									printf("error: %s\n", mysqli_error($conn));
									echo'<script type="text/javascript">alert("Rejecting of request failed");</script>';
								}
								else
								{
									//make an alert box if request reject
									echo'<script type="text/javascript">alert("You have rejected the request");</script>';

									//to reload the web page
									echo '<script>location.href="managerprofile.php"; </script>';
								}


							}


						?>

						
					</table>
				</div> <!--div end for row -->
			</div><!--div end for contents -->

		</div>

	</div> <!-- end of div pending requests -->

	<!--div for the manager to create available work for employees to take -->
	<div id="availWork" class="table-responsive fluid_container">
		<div id="slideAvail"><h2 align="center">AVAILABLE WORK</h2> </div>

		<!-- to make the contents slide down -->
		<div id="slideContents">
		<form method="POST">
			<div class="row">
				<div align="center" style="width: 250px; margin: auto;">
					DATE OF WORK:
					<input style="width: 210px;" type="text" class="form-control" id="workDate" maxlength="20" name="workDate" placeholder="e.g 2017-09-02">
				</div>

				<div align="center" style="width: 250px; margin: auto;">
					WORK DAY:
					<input style="width: 210px;" type="text" class="form-control" id="workDays" maxlength="20" name="workDays" placeholder="e.g Monday">
				</div>

				<div align="center" style="width: 250px; margin: auto;">
					START TIME:
					<input style="width: 210px;" type="text" class="form-control" id="startTime" maxlength="20" name="startTime" placeholder="e.g 09:00">
				</div>

				<div align="center" style="width: 250px; margin: auto;">
					END TIME:
					<input style="width: 210px;" type="text" class="form-control" id="endTime" maxlength="20" name="endTime" placeholder="e.g 17:00">
				</div>

				<div align="center" style="width: 250px; margin: auto;">
					TOTAL HOURS:
					<input style="width: 210px;" type="text" class="form-control" id="totalHours" maxlength="20" name="totalHours" placeholder="e.g 8">
				</div>
			</div>
				
			<!--button to create available work when pressed -->
			<button type="submit" class="btn btn-default pull-right" name="btn-avail">Make Available</button>
		</form>

		<!--to show if the available work has been taken by an employee -->
		<div align="center">
			<div class="row fluid_container">
				<!--show if the work has been taken by an employee along with the available work details -->
				<table id="availWork" class="fluid_container table table-striped" align="center" style="width: 450px; margin: auto;"">
					<th>
							Work Availability:
					</th>

					<th>
							Employee Name:
					</th>

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
						//php code to display all the work availability details
						$availableWorkQuery = "SELECT * from SCHEDULE";

						$workResult = mysqli_query($conn, $availableWorkQuery);

						//if there are no requests in the database
						if (!$workResult) {
							echo "Could not successfully run query ($availableWorkQuery) from Database: " . mysql_error();
						    exit;
						}

						if (mysqli_num_rows($workResult) == 0) {
						   	echo "No work found";
							exit;
						}	

						//if there are available work made by the manager
						if ($workResult) 
							{
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
								echo "<td align='center'>". $scheduleStatus . "</td>";
								echo "<td align='center'>". $takerName . "</td>";
								echo "<td align='center'>". $startTime . "</td>";
								echo "<td align='center'>". $endTime . "</td>";
								echo "<td align='center'>". $workDays . "</td>";
								echo "<td align='center'>". $workDate . "</td>";
								echo "<td align='center'>". $totalHours . "</td>";
								

								echo "</tr>";	
							}//end while
						}//end if				

					?>
				</table>

			</div>

		</div>

		</div> <!--end div to panel the contents down -->
	</div> <!-- end div for available work days -->


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