<?php
// Database credentials
$db_host = "localhost"; // Database host
$db_user = "root";      // Database username
$db_pass = "";          // Database password
$db_name = "backend"; // Database name

// Create connection
$mysqli = new mysqli($db_host, $db_user, $db_pass, $db_name);

// Check connection
if ($mysqli->connect_error) {
    die("Connection failed: " . $mysqli->connect_error);
}
?>
