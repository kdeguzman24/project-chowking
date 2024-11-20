<?php
session_start();
require_once "config.php";

// Only proceed if the form has been submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Ensure the user is logged in
    if (!isset($_SESSION["email"])) {
        header("location: index.php");
        exit();
    }

    // Get the user's email from the session
    $email = $_SESSION["email"];
    
    // Sanitize the new password from the form
    $new_password = trim($_POST["new_password"]);
    
    // Hash the new password for security
    $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);

    // SQL query to update the password
    $sql = "UPDATE users SET password = ? WHERE email = ?";
    if ($stmt = $mysqli->prepare($sql)) {
        // Bind the parameters and execute the query
        $stmt->bind_param("ss", $hashed_password, $email);
        if ($stmt->execute()) {
            echo "Password updated successfully.";
        } else {
            echo "Error updating password: " . $stmt->error;
        }
        $stmt->close();
    } else {
        echo "Error preparing the query: " . $mysqli->error;
    }
    
    // Close the connection
    $mysqli->close();
}
?>
