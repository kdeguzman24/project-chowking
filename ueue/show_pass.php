<?php
session_start();
require_once "config.php";  // Ensure your DB connection is correct

// Check if the user is logged in
if (!isset($_SESSION['email'])) {
    echo json_encode(['success' => false, 'error' => 'User not logged in']);
    exit();
}

$email = $_SESSION['email'];  // Get the logged-in user's email

// Fetch the password (plain text) from the database (just for testing)
$sql = "SELECT password FROM users WHERE email = ?";
$stmt = $mysqli->prepare($sql);

if ($stmt === false) {
    echo json_encode(['success' => false, 'error' => 'Error preparing statement: ' . $mysqli->error]);
    exit();
}

$stmt->bind_param("s", $email);
$stmt->execute();
$stmt->store_result();

if ($stmt->num_rows > 0) {
    // Fetch the plain password from the database
    $stmt->bind_result($password);
    $stmt->fetch();

    echo json_encode(['success' => true, 'password' => $password]);  // Return the plain password for testing
} else {
    echo json_encode(['success' => false, 'error' => 'Password not found']);
}

$stmt->close();
$mysqli->close();
?>
