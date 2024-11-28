<?php
// Assuming you have a database connection file
require_once 'config.php'; // Ensure this file initializes $mysqli

// Check if the form was submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get the form data
    $recipientEmail = $_POST['recipient'];
    $subject = $_POST['subject'];
    $messageContent = $_POST['message']; // Fix the variable name to match the database

    // Start the session and get the sender's email
    session_start();
    if (!isset($_SESSION['email'])) {
        die("Error: User is not logged in.");
    }
    $senderEmail = $_SESSION['email']; // Replace with your session variable if needed

    // Prepare and execute the query
    $query = "INSERT INTO compose_message (sender_email, recipient_email, subject, message_content, sent_at) 
              VALUES (?, ?, ?, ?, NOW())";

    if ($stmt = $mysqli->prepare($query)) {
        $stmt->bind_param("ssss", $senderEmail, $recipientEmail, $subject, $messageContent);
        if ($stmt->execute()) {
            // Redirect to the inbox after sending the message
            header("Location: inbox.php");
            exit();
        } else {
            echo "Error: Could not send the message. " . $stmt->error;
        }
        $stmt->close();
    } else {
        // Handle the error if the query fails to prepare
        echo "Error: " . $mysqli->error;
    }
}
?>

