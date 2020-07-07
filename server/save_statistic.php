<?php

include("mysql_connection_info.php");

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);
// Check connection
if ($conn->connect_error) {
	die("Could not connect to the database.");
}

// Get variables
if (isset($_POST["idname"])) 					{ $idname 			= mysql_real_escape_string($_POST["idname"]); }
if (isset($_POST["title"])) 					{ $title 			= mysql_real_escape_string($_POST["title"]); }
if (isset($_POST["password"])) 					{ $password 		= md5(mysql_real_escape_string($_POST["password"])); }
if (isset($_POST["hide_student_ids"])) 			{ $hide_student_ids = mysql_real_escape_string($_POST["hide_student_ids"]); }
if (isset($_POST["json"])) 						{ $json 			= mysql_real_escape_string($_POST["json"]); }
$timestamp = time();

// Check if idname is unique
if ($result = $conn->query("SELECT idname FROM " . $prefix . "statobjects WHERE idname = '" . $idname . "' LIMIT 1")) {
    if ($result->num_rows > 0) {
    	die("The unique name already exists.");
    }
}

$hide_student_ids = $hide_student_ids == "true" ? 1 : 0;

// Store data
$sql = "INSERT INTO " . $prefix . "statobjects 
  (idname, title, password, hide_student_ids, json)
VALUES 
  ('$idname', '$title', '$password', '$hide_student_ids', '$json')";


if ($conn->query($sql) === TRUE) {
    echo "success";
} else {
    echo "Storing the data to the database failed: " . $conn->error;
}

?>