<?php
session_start();

// Check if the user is logged in
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    http_response_code(403);
    echo json_encode(['success' => false, 'error' => 'Access denied']);
    exit;
}

// Fetch the hashed password from the database
require 'db_connection.php'; // Ensure you have a valid database connection
$username = $_SESSION['username'];

$stmt = $conn->prepare("SELECT password FROM users WHERE username = ?");
$stmt->bind_param("s", $username);
$stmt->execute();
$stmt->bind_result($hashed_password);
$stmt->fetch();
$stmt->close();

// Return the password securely (decrypt if necessary)
echo json_encode(['success' => true, 'password' => $hashed_password]);
?>
