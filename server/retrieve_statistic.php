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
if (isset($_POST["password"])) 					{ $password 		= md5(mysql_real_escape_string($_POST["password"])); }

// Check if idname is unique
if ($result = $conn->query("SELECT * FROM " . $prefix . "statobjects WHERE idname = '" . $idname . "' LIMIT 1")) {
    if ($result->num_rows == 0) {
    	die("There is no statistic with the name " . $idname . ".");
    }
}

// Load data
$row = $result->fetch_assoc();

$title = $row["title"];
$passwordStored = $row["password"];
$hide_student_ids = $row["hide_student_ids"];
$jsonText = $row["json"];

$json = json_decode($jsonText);

// Verify password
if ($password != $passwordStored) {
	die("Wrong password.");
}

// Remove student ids
if ($hide_student_ids) {
	foreach($json as $obj) {
		$obj->studentid = false;
		$obj->line = false;
	}
}

$return = [];
$return["resultdata"] = $json;
$return["title"] = $title;
$return["hide_student_ids"] = $hide_student_ids;

echo(json_encode($return));
?>