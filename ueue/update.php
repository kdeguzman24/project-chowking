<?php
session_start();
require_once "config.php";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // Validate and sanitize the input
    $new_username = trim($_POST['username']);
    $new_username = htmlspecialchars($new_username, ENT_QUOTES, 'UTF-8');

    if (!empty($new_username)) {
        // Retrieve email from session
        $user_email = $_SESSION['email']; // Ensure the email is stored in session during login

        if ($user_email) {
            // Update the username in the database using the email
            $query = "UPDATE users SET username = ? WHERE email = ?";
            $stmt = $mysqli->prepare($query);
            $stmt->bind_param("ss", $new_username, $user_email); // Use $user_email here

            if ($stmt->execute()) {
                $_SESSION['success_message'] = "Username updated successfully!";
                // Update username in session if stored
                $_SESSION['username'] = $new_username;
            } else {
                $_SESSION['error_message'] = "Failed to update username.";
            }
            $stmt->close();
        } else {
            $_SESSION['error_message'] = "User email not found in session.";
        }
    } else {
        $_SESSION['error_message'] = "Username cannot be empty.";
    }

    // Redirect back to the settings page
    header("Location: students_sett.php");
    exit;
}
?>
