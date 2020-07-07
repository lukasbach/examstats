<?php

include("mysql_connection_info.php");

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);
// Check connection
if ($conn->connect_error) {
	die("Connection failed: " . $conn->connect_error);
}

// sql to create table
$sql = "CREATE TABLE " . $prefix . "statobjects (
id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY, 
idname VARCHAR(30) NOT NULL,
title VARCHAR(30),
password VARCHAR(50),
hide_student_ids BOOLEAN,
creation_date TIMESTAMP,
json TEXT NOT NULL
)";

if ($conn->query($sql) === TRUE) {
    echo "Table(s) created successfully";
} else {
    echo "Error creating table: " . $conn->error;
}

?>