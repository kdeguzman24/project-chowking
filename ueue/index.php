<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Page</title>
    <style>
        /* Existing CSS */
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
    </style>
</head>
<body>
    <div class="login-container">
        <img src="UE logo.png" alt="UE Logo">
        <h2 id="form-title">Sign In</h2>

        <!-- Sign-In Form -->
        <form id="sign-in-form" action="auth.php" method="post" style="display: block;">
            <input type="email" name="email" placeholder="Email" required>
            <input type="password" name="password" placeholder="Password" required>
            <button type="submit" name="signin">Sign In</button>
        </form>

        <!-- Sign-Up Form -->
        <form id="sign-up-form" action="auth.php" method="post" style="display: none;">
            <input type="text" name="username" placeholder="Username" required>
            <input type="email" name="email" placeholder="Email" required>
            <input type="password" name="password" placeholder="Password" required>
            <button type="submit" name="signup">Sign Up</button>
        </form>

        <span id="toggle-link" class="toggle-link" onclick="toggleForms()">Don’t have an account? Sign Up</span>
    </div>

    <script>
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