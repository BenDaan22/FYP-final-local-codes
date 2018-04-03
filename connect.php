<!--
  Author: Ben Joshua Daan
  Student ID: C13358586

  Final Year Project: Schedule_Me_Out

  Description:
  This set of codes will establish connection with the mysql database

-->

<?php
      //Database connection details
      $username =  = "root";
      $servername = "localhost";
      $password = "22111995"; //"password"; // = ""; no password for WAMP server
      $dbname = "FYP";

      //create connection
      $conn = mysqli_connect($servername,$username,$password,$dbname);

      //check connection
      if(!$conn)
      {
        die("connection failed:" . mysqli_connect_error($conn));
        echo "Connection Error";
      }
      else
      {
        //this shows that its connected to MySQL database correctly
        echo "Connected successfully";
      }
      


     // mysqli_close($conn);
?>