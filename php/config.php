<?php
$Server_Name="localhost" ;
$User_Name="appychat_user";
$Pwd="";
$DB_Name="appychat_db";
$conn=mysqli_connect($Server_Name, $User_Name, $Pwd, $DB_Name);
$googleClientId = "";
$googleClientSecret = "";
$googleURL = "";


if( ! $conn)
die ("Failed to connect to MySQL: " . mysqli_error());
?>




