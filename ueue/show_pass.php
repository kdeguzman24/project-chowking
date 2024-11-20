<?php
session_start();
require_once "config.php";  // Make sure this file includes your database connection

// Check if the user is logged in
if (!isset($_SESSION['email'])) {
    echo json_encode(['success' => false, 'error' => 'User not logged in']);
    exit();
}

$email = $_SESSION['email'];  // Get the logged-in user's email

// Fetch the password from the database
$sql = "SELECT password FROM users WHERE email = ?";
$stmt = $mysqli->prepare($sql);

if ($stmt === false) {
    // If there was an error preparing the statement
    echo json_encode(['success' => false, 'error' => 'Error preparing statement: ' . $mysqli->error]);
    exit();
}

$stmt->bind_param("s", $email);
$stmt->execute();
$stmt->store_result();

if ($stmt->num_rows > 0) {
    // Fetch the password from the database
    $stmt->bind_result($password);
    $stmt->fetch();

    echo json_encode(['success' => true, 'password' => $password]);  // Return the password (for demonstration purposes)
} else {
    // If no password was found for the user
    echo json_encode(['success' => false, 'error' => 'Password not found']);
}

$stmt->close();
$mysqli->close();
?>
