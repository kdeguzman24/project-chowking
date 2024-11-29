<?php
require_once 'config.php'; // Database connection

session_start();
if (!isset($_SESSION['email'])) {
    die("Error: User is not logged in.");
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $recipientEmail = $_POST['recipient'];
    $subject = $_POST['subject'];
    $messageContent = $_POST['message'];

    $senderEmail = $_SESSION['email'];

    // Insert query
    $query = "INSERT INTO compose_message (sender_email, recipient_email, subject, message_content, sent_at) 
              VALUES (?, ?, ?, ?, NOW())";

    if ($stmt = $mysqli->prepare($query)) {
        $stmt->bind_param("ssss", $senderEmail, $recipientEmail, $subject, $messageContent);
        if ($stmt->execute()) {
            // Redirect to inbox with success
            header("Location: inbox.php?status=success");
            exit();
        } else {
            // Log error and display user-friendly message
            error_log("Error: " . $stmt->error, 3, "errors.log");
            echo "Error: Could not send the message. Please try again.";
        }
        $stmt->close();
    } else {
        error_log("Query preparation failed: " . $mysqli->error, 3, "errors.log");
        echo "Error: Something went wrong. Please try again later.";
    }
}
?>
