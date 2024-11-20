<?php
session_start();
// Include config file
require_once "config.php";

// Define variables and initialize with empty values
$username = $email = $password = "";
$username_err = $email_err = $password_err = "";

// Check database connection
if ($mysqli->connect_error) {
    die("Connection failed: " . $mysqli->connect_error);
}

// Processing form data when form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Handle Sign In
    if (isset($_POST['action']) && $_POST['action'] == 'sign_in') {
        $sql = "SELECT username, email, password FROM users WHERE LOWER(email) = LOWER(?)";

        if ($stmt = $mysqli->prepare($sql)) {
            $input_email = trim($_POST['sign_in_email']);
            $stmt->bind_param("s", $input_email);
            $input_password = trim($_POST['sign_in_password']);

            if ($stmt->execute()) {
                $stmt->bind_result($username, $email, $hashed_password);
                if ($stmt->fetch()) {
                    if (!empty($hashed_password)) {
                        if (password_verify($input_password, $hashed_password)) {
                            // Set session variables
                            $_SESSION["loggedin"] = true;
                            $_SESSION["username"] = $username;
                            $_SESSION["email"] = $email;
                            $_SESSION["hashed_password"] = $hashed_password;

                            // Redirect based on user type (Admin or Student)
                            if ($email == "admin@ue.edu.ph") {
                                header("location: dashboard.php");
                            } else {
                                header("location: students_db.php");
                            }
                            exit();
                        } else {
                            $password_err = "The password you entered was not valid.";
                        }
                    } else {
                        $password_err = "Failed to retrieve password hash.";
                    }
                } else {
                    $email_err = "No account found with that email.";
                }
            } else {
                echo "Oops! Something went wrong. Please try again later.";
            }
            $stmt->close();
        } else {
            echo "Prepare error: " . $mysqli->error;
        }
    }

    // Handle Sign Up
    if (isset($_POST['action']) && $_POST['action'] == 'sign_up') {
        // Get form data
        $username = trim($_POST['username']);
        $email = trim($_POST['email']);
        $password = trim($_POST['password']);
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

        // Check if email already exists
        $sql = "SELECT username, email FROM users WHERE email = ?";
        if ($stmt = $mysqli->prepare($sql)) {
            $stmt->bind_param("s", $email);
            $stmt->execute();
            $stmt->store_result();

            if ($stmt->num_rows > 0) {
                $email_err = "An account with this email already exists!";
            } else {
                // Insert new user into the database
                $insert_sql = "INSERT INTO users (username, email, password) VALUES (?, ?, ?)";
                if ($stmt_insert = $mysqli->prepare($insert_sql)) {
                    $stmt_insert->bind_param("sss", $username, $email, $hashed_password);
                    if ($stmt_insert->execute()) {
                        // Redirect to index after successful sign-up
                        header("location: index.php");
                        exit();
                    } else {
                        echo "Error creating account: " . $stmt_insert->error;
                    }
                    $stmt_insert->close();
                }
            }
            $stmt->close();
        }
    }
}

$mysqli->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login and Registration</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            display: flex;
            justify-content: flex-end;
            align-items: center;
            height: 100vh;
            padding-right: 150px;
            background-image: url('lualhati3.jpg');
            overflow: hidden;
            background-size: cover;
            background-repeat: no-repeat;
            background-position: center;
        }
        .login-container {
            background-color: rgba(255, 255, 255, 0.8);
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            width: 400px;
            text-align: center;
            box-sizing: border-box;
            color: black;
        }
        .login-container img {
            max-width: 100px;
            margin-bottom: 20px;
        }
        .login-container h2 {
            margin-bottom: 20px;
            color: black;
        }
        .login-container input {
            width: 100%;
            padding: 10px;
            margin: 10px 0;
            border: 1px solid #ccc;
            border-radius: 4px;
            box-sizing: border-box;
        }
        .login-container button {
            width: 100%;
            padding: 15px;
            background-color: #28a745;
            border: none;
            border-radius: 4px;
            color: #fff;
            font-size: 16px;
            cursor: pointer;
            box-sizing: border-box;
        }
        .login-container button:hover {
            background-color: #218838;
        }
        .toggle-link {
            color: #007bff;
            cursor: pointer;
            text-decoration: underline;
            margin-top: 10px;
            display: block;
        }
        .error {
            color: red;
            font-size: 14px;
            margin-top: 10px;
        }
    </style>
</head>
<body>
    <div class="login-container">
        <img src="UE logo.png" alt="UE Logo">
        <h2 id="form-title">Sign In</h2>
        
        <form id="sign-in-form" onsubmit="return validateSignInForm()" method="POST" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" style="display: block;">
            <input type="hidden" name="action" value="sign_in">
            <input type="email" name="sign_in_email" id="sign-in-email" placeholder="Email" required>
            <input type="password" name="sign_in_password" id="sign-in-password" placeholder="Password" required>
            <button type="submit">Sign In</button>
            <!-- Error message for sign-in -->
            <div class="error"><?php echo $email_err ? $email_err : $password_err; ?></div>
        </form>

        <form id="sign-up-form" method="POST" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" style="display: none;">
            <input type="hidden" name="action" value="sign_up">
            <input type="text" name="username" id="sign-up-username" placeholder="Username" required>
            <input type="email" name="email" id="sign-up-email" placeholder="Email" required>
            <input type="password" name="password" id="sign-up-password" placeholder="Password" required>
            <button type="submit">Sign Up</button>
            <!-- Error message for sign-up -->
            <div class="error"><?php echo $email_err; ?></div>
        </form>

        <span id="toggle-link" class="toggle-link" onclick="toggleForms()">Don’t have an account? Sign Up</span>
    </div>

    <script>
        function validateSignInForm() {
            var email = document.getElementById("sign-in-email").value;
            var password = document.getElementById("sign-in-password").value;
            var emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;

            if (!emailRegex.test(email)) {
                alert("Invalid email format.");
                return false;
            }
            if (password.length < 8) {
                alert("Password must be at least 8 characters long.");
                return false;
            }
            return true;
        }

        function toggleForms() {
            var signInForm = document.getElementById("sign-in-form");
            var signUpForm = document.getElementById("sign-up-form");
            var toggleLink = document.getElementById("toggle-link");
            var formTitle = document.getElementById("form-title");

            if (signInForm.style.display === "none") {
                signInForm.style.display = "block";
                signUpForm.style.display = "none";
                formTitle.textContent = "Sign In";
                toggleLink.textContent = "Don’t have an account? Sign Up";
            } else {
                signInForm.style.display = "none";
                signUpForm.style.display = "block";
                formTitle.textContent = "Sign Up";
                toggleLink.textContent = "Already have an account? Sign In";
            }
        }
    </script>
</body>
</html>
