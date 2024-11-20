<?php
session_start();
require_once "config.php";

// Ensure the user is logged in
if (isset($_SESSION['email'])) {
    $email = $_SESSION['email'];

    // Fetch the user's password (hashed in the database, you can't get the original password)
    $sql = "SELECT password FROM users WHERE email = ?";
    if ($stmt = $mysqli->prepare($sql)) {
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $stmt->store_result();
        if ($stmt->num_rows > 0) {
            $stmt->bind_result($hashed_password);
            $stmt->fetch();
            
            // You shouldn't expose the actual password in plaintext
            // This is just for demonstration purposes (be cautious with this approach)
            echo json_encode(["success" => true, "password" => "******"]);
        } else {
            echo json_encode(["success" => false, "error" => "User not found"]);
        }
        $stmt->close();
    }
    $mysqli->close();
} else {
    echo json_encode(["success" => false, "error" => "User not logged in"]);
}
?>
