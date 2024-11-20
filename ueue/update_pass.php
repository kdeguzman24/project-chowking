<?php
session_start();
require_once "config.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (!isset($_SESSION["email"])) {
        header("location: index.php");
        exit();
    }
    
    $email = $_SESSION["email"];
    $new_password = trim($_POST["new_password"]);
    $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);

    $sql = "UPDATE users SET password = ? WHERE email = ?";
    if ($stmt = $mysqli->prepare($sql)) {
        $stmt->bind_param("ss", $hashed_password, $email);
        if ($stmt->execute()) {
            echo "Password updated successfully.";
        } else {
            echo "Error updating password.";
        }
        $stmt->close();
    }
    $mysqli->close();
}
?>
