<!--
	Author: Ben Joshua Daan
	Student ID: C13358586

	Final Year Project: Schedule_Me_Out

	Description:
	Whenever the user selects log out option it will destroy the session made
-->

<?php
	session_start();
	session_destroy();
	header("Location: index.php");
?>