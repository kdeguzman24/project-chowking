<?php
session_start();

// Include config file
require_once "config.php";

// Check if the user is logged in, redirect to login page if not
if (!isset($_SESSION['email'])) {
    header("Location: login.php");
    exit();
}

// Process the form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve form data
    $sender_email = $_SESSION['email']; // Logged-in user's email
    $recipient_email = 'admin@ue.edu.ph'; // Default recipient email
    $message_text = $mysqli->real_escape_string($_POST['message']); // Sanitizing message input
    $subject = $mysqli->real_escape_string($_POST['subject']); // Sanitizing subject input
    $issue = $mysqli->real_escape_string($_POST['issue']); // Sanitizing issue input
    $status = 'sent'; // Default status

    // Validate sender email in the database
    $userCheckQuery = "SELECT email FROM users WHERE email = ?";
    $stmt = $mysqli->prepare($userCheckQuery);
    $stmt->bind_param("s", $sender_email);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows === 0) {
        die("Error: Sender email not registered.");
    }

    // Validate recipient email (admin email in this case)
    $stmt->bind_param("s", $recipient_email);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows === 0) {
        die("Error: Recipient email not registered.");
    }

    $stmt->close();

    // Insert the message along with the issue into the database
    $query = "INSERT INTO messages (sender_email, recipient_email, subject, issues, message_text, status) 
              VALUES (?, ?, ?, ?, ?, ?)";
    $stmt = $mysqli->prepare($query);
    $stmt->bind_param("ssssss", $sender_email, $recipient_email, $subject, $issue, $message_text, $status);

    if ($stmt->execute()) {
        // Set a session variable to display a success message on the next page
        $_SESSION['message_sent'] = "Message sent successfully!";
    } else {
        // If error occurs, set an error message
        $_SESSION['message_sent_error'] = "Error: " . $stmt->error;
    }

    $stmt->close();

    // Redirect to viewReport.php to display the result
    header("Location: viewReport.php");
    exit();
}

// Close the database connection
$mysqli->close();
?>
