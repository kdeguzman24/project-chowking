<?php
session_start();

// Check if session variables exist

$username = $_SESSION['username'];
$email = $_SESSION['email'];

?>

<!DOCTYPE html> 
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Settings</title>

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">

    <style>
         #hamburger {
            background: none;
            border: none;
            cursor: pointer;
            font-size: 24px;
            color: #940b10;
            margin-right: 25px; /* Add some margin-right */
        }

        h1 {
            color: #940b10;
            font-size: 24px;
            margin-bottom: 20px;
        }

        .fixed-header {
            display: flex;
            align-items: center;
            padding: 35px 15px;
            background-color: #f4f4f4;
            border-bottom: 2px solid #940b10;
            margin-bottom: 20px;
        }

        .fixed-header img {
            width: 90px;
            border-radius: 50%;
            margin-right: 20px;
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.5);
        }

        .name-position h2 {
            margin: 0;
            font-size: 20px;
            color: #940b10;
        }

        .name-position p {
            margin: 0;
            font-size: 14px;
            color: #000000;
        }

        .settings-details {
            background-color: white;
            padding: 15px;
            border-radius: 5px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            margin-top: 20px;
        }

        .settings-details h2 {
            color: #940b10;
        }

        .settings-section {
            margin-bottom: 30px;
        }

        .settings-section label {
            display: block;
            font-size: 16px;
            color: #333;
            margin-bottom: 5px;
        }

        .settings-section input,
.settings-section select {
    width: 100%; /* Full width for responsiveness */
    max-width: 1500px; /* Max width for larger screens */
    padding: 10px;
    font-size: 14px;
    border: 1px solid #ddd;
    border-radius: 5px;
    margin-bottom: 10px;
    box-sizing: border-box; /* Ensures padding doesn't increase the width */
}

        .settings-section input[type="checkbox"] {
            width: auto;
            display: inline-block;
        }

        /* Editable field styles */
        input[disabled] {
            background-color: #f0f0f0;
        }

        input.editable {
            background-color: #ffffff;
            border: 1px solid #940b10;
        }

        .edit-btn, .save-btn, .delete-btn {
            background-color: #940b10;
            color: white;
            border: none;
            padding: 8px 12px;
            margin-left: 10px;
            cursor: pointer;
            border-radius: 5px;
        }

        .save-btn {
            display: none;
        }

         .delete-btn {
             background-color: #d9534f;
                color: white;
         }

        .delete-btn:hover {
             background-color: #c9302c;
         }

        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            display: flex;
            height: 100vh;
            overflow: hidden;
            background-color: #eaeaea;
        }

        .sidebar {
            height: 96%;
            width: 250px; /* Adjusted for better responsiveness */
            position: fixed;
            top: 0;
            left: 0;
            background-color: #940b10;
            padding: 20px 0;
            display: flex;
            flex-direction: column;
            align-items: center;
            border-top-right-radius: 30px; /* Rounded top-right corner */
            border-bottom-right-radius: 30px; /* Rounded bottom-right corner */
            box-shadow:
            1.3px 0px 0.5px rgba(0, 0, 0, 0.18),
            2.9px 0px 1.2px rgba(0, 0, 0, 0.142),
            4.8px 0px 2.1px rgba(0, 0, 0, 0.124),
            7.3px 0px 3.4px rgba(0, 0, 0, 0.111),
            10.5px 0px 5.1px rgba(0, 0, 0, 0.1),
            14.9px 0px 7.6px rgba(0, 0, 0, 0.09),
            21.1px 0px 11.2px rgba(0, 0, 0, 0.08),
            30.7px 0px 17.1px rgba(0, 0, 0, 0.069),
            47.3px 0px 28px rgba(0, 0, 0, 0.056),
            84px 0px 53px rgba(0, 0, 0, 0.038);
            transition: width 0.3s ease;
        }

        .sidebar.active {
            width: 90px;
        }

        .sidebar.active + .main-content {
            margin-left: 90px;
        }

        .main-content {
            margin-left: 250px; 
            padding: 20px;
            flex-grow: 1;
            background-color: #f4f4f4;
            height: 100vh;
            overflow-y: auto;
            transition: margin-left 0.3s;
        }

        /* ============================================================ */
        .sidebar.active a span {
            display: none;
        }

        .sidebar.active .navbar-title h2, 
        .sidebar.active .navbar-title p {
            display: none;
        }

        .sidebar.active img {
            width: 58px;
            margin-left: 10px;
        }

        .sidebar a span {
            margin-left: 10px;
        }

        .sidebar.active a i {
            margin-left: 26px;
        }
        /* ============================================================ */



        #hamburger {
            background: none;
            border: none;
            cursor: pointer;
            font-size: 24px;
            color: #940b10;
            margin-right: 25px; /* Add some margin-right */
        }

        .navbar-title {
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 20px;
        }

        .navbar-title img {
            width: 58px;
            margin-right: 10px;
        }

        .navbar-text {
            color: white;
            text-align: center; /* Centered text */
        }

        .navbar-text h2 {
            font-size: 17px;
            margin: 0;
        }

        .navbar-text p {
            font-size: 10px;
            margin: 0;
            color: white;
        }

        .sidebar a {
            padding: 15px 20px; /* Increased padding for better click area */
            text-decoration: none;
            font-size: 16px;
            color: white;
            display: flex;
            align-items: center;
            justify-content: flex-start;
            width: 90%; /* Adjusted for better spacing */
            border-radius: 5px;
            transition: background 0.3s, transform 0.2s; /* Added transform for hover */
            margin: 10px 0; /* Reduced margin */
        }

        .sidebar a i {
            margin-left: 30px;
            margin-right: 15px;
            font-size: 24px; /* Increased icon size for visibility */
        }

        .sidebar a:hover {
            background-color: #b13333;
            transform: scale(1.05); /* Added scaling effect on hover */
        }

        .main-content {
            margin-left: 250px; /* Adjusted margin */
            padding: 20px;
            flex-grow: 1;
            background-color: #f4f4f4;
            height: 100vh;
            overflow-y: auto;
            transition: margin-left 0.3s;
        }

        h1 {
            color: #940b10;
            font-size: 24px;
            margin-bottom: 20px;
        }

        .fixed-header {
            display: flex;
            align-items: center;
            padding: 35px 15px;
            background-color: #f4f4f4;
            border-bottom: 2px solid #940b10;
            margin-bottom: 20px;
        }

        .fixed-header img {
            width: 90px;
            border-radius: 50%;
            margin-right: 20px;
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.5);
        }

        .name-position {
            display: flex;
            flex-direction: column;
        }

        .name-position h2 {
            margin: 0;
            font-size: 20px;
            color: #940b10;
        }

        .name-position p {
            margin: 0;
            font-size: 14px;
            color: #000000;
        }

        .widgets {
            display: flex;
            justify-content: space-around;
            margin-top: 20px;
        }

        .widget {
            background-color: white;
            padding: 15px;
            border-radius: 5px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            width: 150px;
            text-align: center;
        }

        .recent-activity,
        .calendar,
        .quick-actions {
            margin-top: 20px;
        }

        .recent-activity ul,
        .calendar {
            list-style-type: none;
            padding: 0;
            background-color: white;
            border-radius: 5px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        .recent-activity li,
        .calendar p {
            padding: 10px;
            margin-bottom: 10px;
        }

        .quick-actions button {
            padding: 10px 20px;
            margin: 5px;
            background-color: #940b10;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            transition: background 0.3s, transform 0.2s; /* Added transition for buttons */
        }

        .quick-actions button:hover {
            background-color: #b13333;
            transform: scale(1.05); /* Scale effect on hover */
        }

        footer {
            padding: 20px;
            background-color: #940b10;
            color: white;
            text-align: center;
            margin-top: 20px;
        }

        footer a {
            color: white;
            text-decoration: none;
        }

        @media (max-width: 768px) {
            .sidebar {
                width: 100px;
            }

            .main-content {
                margin-left: 100px;
            }

            .sidebar a {
                font-size: 14px;
                padding: 8px 10px;
            }
        }



        @media (max-width: 480px) {
            .sidebar {
                width: 100%;
                position: relative;
                height: auto;
                flex-direction: row;
                justify-content: space-around;
                padding: 10px 0;
            }

            .main-content {
                margin-left: 0;
                padding-top: 60px;
            }

            .sidebar a {
                flex: 1;
                text-align: center;
            }
        }

        .password-container {
    position: relative;
    width: 100%;
}

#password {
    width: 100%; /* Set to 100% to match the others */
    padding: 8px;
    padding-right: 40px; /* Add padding to the right to create space for the eye icon */
}

.show-password-btn {
    position: absolute;
    right: 10px;
    top: 40%;
    transform: translateY(-50%);
    background: none;
    border: none;
    cursor: pointer;
    padding: 0;
}

.eye-icon {
    font-size: 18px;
}

    </style>
</head>

<body>

    <!-- Sidebar -->
    <div class="sidebar">
        <div class="navbar-title">
            <img src="UE logo.png" alt="University of the East Logo">
            <div class="navbar-text">
                <h2>UNIVERSITY<br>OF THE EAST</h2>
                <p>MANILA CAMPUS</p>
            </div>
        </div>
        <a href="dashboard.php"><i class="fa-solid fa-chalkboard"></i> <span>Dashboard</span></a>
        <a href="students.php"><i class="fa-regular fa-user"></i> <span>Students</span></a>
        <a href="adminReport.php"><i class="fa-solid fa-magnifying-glass"></i> <span>View Reports</span></a>
        <a href="statistics.php"><i class="fa-solid fa-chart-gantt"></i> <span>Statistics</span></a>
        <a href="settings.php"><i class="fas fa-sliders-h"></i> <span>Settings</span></a>
        <a href="notifications.php"><i class="fas fa-bell"></i> <span>Notifications</span></a>
        <a href="index.php"><i class="fas fa-sign-out-alt"></i> <span>Logout</span></a>
    </div>

    <!-- Main content -->
    <div class="main-content">
    <div class="fixed-header">
        <button id="hamburger" class="hamburger" onclick="toggleSidebar()">
            <i class="fas fa-bars"></i>
        </button>
        <img src="ProfilePic.png" alt="Profile Picture">
        <div class="name-position">
            <h2><?php echo htmlspecialchars($username); ?></h2>
            <p>Settings</p>
        </div>
    </div>

    <div class="settings-details">
        <h2>Account Settings</h2>
        <div class="settings-section">
            <label for="username">Username</label>
            <input type="text" id="username" value="<?php echo htmlspecialchars($username); ?>" disabled>

        </div>

        <div class="settings-section">
            <label for="email">Email</label>
            <input type="email" id="email" value="<?php echo htmlspecialchars($email); ?>" disabled>
        </div>

       <!-- Password Edit Section -->
<div class="settings-section">
    <label for="password">Password</label>
    <div class="password-container">
        <!-- Password input field -->
        <input type="password" id="password" name="password" value="******" disabled> <!-- Initially disabled -->

        <!-- Eye icon button to toggle visibility -->
        <button type="button" id="show-password-btn" class="show-password-btn" onclick="togglePasswordVisibility()">
            <i class="fas fa-eye-slash" id="eye-icon"></i> <!-- Initially set as 'eye-slash' (hidden password) -->
        </button>
    </div>
</div>

      


    <script>
        // Sidebar toggle function
        function toggleSidebar() {
            var sidebar = document.querySelector('.sidebar');
            sidebar.classList.toggle('active');
            var mainContent = document.querySelector('.main-content');
            mainContent.style.marginLeft = sidebar.classList.contains('active') ? '80px' : '250px'; // Adjust margin based on the collapsed state
        }


        // Toggle edit mode for input fields
        function toggleEdit(fieldId) {
            var inputField = document.getElementById(fieldId);
            var editButton = inputField.nextElementSibling;
            var saveButton = editButton.nextElementSibling;

            if (inputField.disabled) {
                inputField.disabled = false;
                inputField.classList.add('editable');
                saveButton.style.display = 'inline-block';
                editButton.style.display = 'none';
            }
        }

        // Save the edited field and switch back to non-editable mode
        function saveField(fieldId) {
            var inputField = document.getElementById(fieldId);
            var editButton = inputField.nextElementSibling;
            var saveButton = editButton.nextElementSibling;

            inputField.disabled = true;
            inputField.classList.remove('editable');
            saveButton.style.display = 'none';
            editButton.style.display = 'inline-block';

            // Add your code to handle saving the updated field value here
            console.log(fieldId + " saved with value: " + inputField.value);
        }
        function togglePasswordVisibility() {
    const passwordInput = document.getElementById('password');
    const eyeIcon = document.getElementById('eye-icon');

    if (passwordInput.type === 'password') {
        // Fetch the password from the server (this could be done via AJAX or a fetch call)
        fetch('show_pass.php')
            .then(response => {
                console.log('Response:', response);  // Log the raw response
                return response.json();
            })
            .then(data => {
                console.log('Data:', data);  // Log the data returned by the PHP script

                if (data.success) {
                    passwordInput.value = data.password;  // Replace with actual password
                    passwordInput.type = 'text';
                    eyeIcon.classList.remove('fa-eye-slash');
                    eyeIcon.classList.add('fa-eye');
                } else {
                    console.error('Error fetching password:', data.error);
                    alert('Error fetching password: ' + data.error);
                }
            })
            .catch(error => {
                console.error('Error fetching password:', error);
                alert('An error occurred while fetching the password.');
            });
    } else {
        passwordInput.type = 'password';
        passwordInput.value = '******';  // Masked value again
        eyeIcon.classList.remove('fa-eye');
        eyeIcon.classList.add('fa-eye-slash');
    }
}

    </script>

</body>
</html>