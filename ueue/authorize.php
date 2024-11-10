<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "user_db";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Sign-Up Handling
if (isset($_POST['signup'])) {
    $username = $_POST['username'];
    $email = $_POST['email'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

    $sql = "INSERT INTO users (username, email, password) VALUES ('$username', '$email', '$password')";

    if ($conn->query($sql) === TRUE) {
        echo "<script>alert('Sign-up successful!');</script>";
    } else {
        echo "<script>alert('Error: " . $sql . "<br>" . $conn->error . "');</script>";
    }
}

// Login Handling
if (isset($_POST['signin'])) {
    $email = $_POST['email'];
    $password = $_POST['password'];

    $sql = "SELECT * FROM users WHERE email='$email'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();

        // Debugging output: see what the password hash looks like
        echo "<pre>";
        var_dump($user); // This will show user data including the hashed password
        echo "</pre>";

        // Check if the password matches the hashed password stored in the database
        if (password_verify($password, $user['password'])) {
            // Start session to track logged-in user
            session_start();
            $_SESSION['user'] = $user['email'];  // Store user email in session (can store ID or role as well)
            
            // Redirect based on user role
            if ($email === "admin@ue.edu.ph") {
                header("Location: dashboard.php");
            } else {
                header("Location: students_db.php");
            }
            exit();
        } else {
            // Show alert if password doesn't match
            echo "<script>alert('Invalid password.');</script>";
        }
    } else {
        // Show alert if email doesn't exist
        echo "<script>alert('No user found with this email.');</script>";
    }
}

$conn->close();
?>
