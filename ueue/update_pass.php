<?php
session_start();
require_once "config.php";
// Handle Forgot Password
if (isset($_POST['action']) && $_POST['action'] == 'forgot_password') {
    $email = trim($_POST['email']);
    $token = bin2hex(random_bytes(32)); // Generate a secure token
    $expiry = date('Y-m-d H:i:s', strtotime('+1 hour'));

    // Check if email exists
    $sql = "SELECT email FROM users WHERE email = ?";
    if ($stmt = $mysqli->prepare($sql)) {
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $stmt->store_result();

        if ($stmt->num_rows > 0) {
            // Save token to password_reset table
            $insert_sql = "INSERT INTO password_reset (email, token, expiry) VALUES (?, ?, ?) ON DUPLICATE KEY UPDATE token = ?, expiry = ?";
            if ($stmt_insert = $mysqli->prepare($insert_sql)) {
                $stmt_insert->bind_param("sssss", $email, $token, $expiry, $token, $expiry);
                $stmt_insert->execute();
                $stmt_insert->close();

                // Send email with reset link
                $reset_link = "http://yourwebsite.com/reset_password.php?token=$token";
                $subject = "Password Reset Request";
                $message = "Click this link to reset your password: $reset_link";
                $headers = "From: noreply@yourwebsite.com";

                mail($email, $subject, $message, $headers);
                echo "Password reset link has been sent to your email.";
            }
        } else {
            echo "No account found with this email.";
        }
        $stmt->close();
    }
}


// Handle Reset Password
if (isset($_POST['action']) && $_POST['action'] == 'reset_password') {
    $token = trim($_POST['token']);
    $new_password = trim($_POST['new_password']);
    $confirm_password = trim($_POST['confirm_password']);

    if ($new_password === $confirm_password) {
        $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);

        // Verify token
        $sql = "SELECT email FROM password_reset WHERE token = ? AND expiry > NOW()";
        if ($stmt = $mysqli->prepare($sql)) {
            $stmt->bind_param("s", $token);
            $stmt->execute();
            $stmt->bind_result($email);
            if ($stmt->fetch()) {
                // Update password in users table
                $update_sql = "UPDATE users SET password = ? WHERE email = ?";
                if ($stmt_update = $mysqli->prepare($update_sql)) {
                    $stmt_update->bind_param("ss", $hashed_password, $email);
                    if ($stmt_update->execute()) {
                        echo "Password has been updated.";
                    } else {
                        echo "Failed to update password.";
                    }
                    $stmt_update->close();
                }
            } else {
                echo "Invalid or expired token.";
            }
            $stmt->close();
        }
    } else {
        echo "Passwords do not match.";
    }
}
$stmt->close();
$mysqli->close();
?>
