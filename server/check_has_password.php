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

// Check if idname is unique
if ($result = $conn->query("SELECT password FROM " . $prefix . "statobjects WHERE idname = '" . $idname . "' LIMIT 1")) {
    if ($result->num_rows == 0) {
    	die("There is no statistic with the name " . $idname . ".");
    }
}

// Load data
$row = $result->fetch_assoc();
$passwordStored = $row["password"];

// Verify password
if (mysql_real_escape_string(md5("")) == $passwordStored) {
	echo "false";
	exit;
} else {
	echo "true";
	exit;
}
?>