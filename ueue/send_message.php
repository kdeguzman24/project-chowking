<?php
// Assuming you have a database connection file
require_once 'config.php';

// Check if the form was submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get the form data
    $recipientEmail = $_POST['recipient'];
    $subject = $_POST['subject'];
    $messageText = $_POST['message'];

    // Get the sender's email (you can replace this with the logged-in user's email)
    // This example assumes the sender's email is stored in a session variable.
    session_start();
    $senderEmail = $_SESSION['email']; // Replace with your session variable if needed

    // Insert the message into the database
    $query = "INSERT INTO messages (sender_email, recipient_email, subject, message_text, timestamp) 
              VALUES (?, ?, ?, ?, NOW())";

    if ($stmt = $mysqli->prepare($query)) {
        $stmt->bind_param("ssss", $senderEmail, $recipientEmail, $subject, $messageText);
        $stmt->execute();
        $stmt->close();

        // Redirect to the inbox after sending the message
        header("Location: inbox.php");
        exit();
    } else {
        // Handle the error if the query fails
        echo "Error: " . $mysqli->error;
    }
}
?>
