<?php
session_start();

// Include the database connection from config.php
require_once "config.php";  // Assuming your $mysqli is defined in config.php

// Check if the form is submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Ensure user is logged in
    if (!isset($_SESSION['username'])) {
        header("Location: index.php"); // Redirect to login if not logged in
        exit();
    }

    // Get the username and email from the form (sent as hidden input)
    $username = $_POST['username'];
    $email = $_POST['email'];

    // SQL to delete the user account
    $sql = "DELETE FROM users WHERE username = ? AND email = ?";

    // Prepare the statement using the existing $mysqli connection
    if ($stmt = $mysqli->prepare($sql)) {
        // Bind parameters and execute the query
        $stmt->bind_param("ss", $username, $email);
        if ($stmt->execute()) {
            // Optionally, destroy the session after deleting the account
            session_destroy();
            header("Location: index.php"); // Redirect to the homepage or login page
            exit();
        } else {
            echo "Error: " . $stmt->error;
        }
        $stmt->close();
    } else {
        echo "Error preparing the statement: " . $mysqli->error;
    }

    // Close the database connection
    $mysqli->close();
}
?>
