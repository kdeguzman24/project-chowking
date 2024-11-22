<?php
session_start();

// Check if user is logged in by verifying session variable
if (!isset($_SESSION['email'])) {
    echo json_encode(['success' => false, 'error' => 'User not logged in']);
    exit();
}

// If logged in, fetch the session email
$email = $_SESSION['email'];


?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Report Section</title>
    <!-- FontAwesome Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <!-- Chart.js CDN -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
           body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            display: flex;
            height: 100vh;
            overflow: hidden;
            background-color: #eaeaea;
        }

        /* Sidebar */
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
            text-align: center;
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
            font-size: 24px;
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
        h1 {
            color: #940b10;
            font-size: 24px;
            margin-bottom: 20px;
        }

        /* Report Section Styling */
        .report-section {
            margin-top: 20px;
        }

        .report-box {
            background-color: white;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 0 20px rgba(0, 0, 0, 0.1);
            margin-bottom: 40px;
        }

        .report-box h2 {
            color: #940b10;
            margin-bottom: 20px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }

        table, th, td {
            border: 1px solid #ddd;
        }

        th, td {
            padding: 10px;
            text-align: left;
        }

        th {
            background-color: #f2f2f2;
        }

        /* Compose Message Form */
        .compose-message {
            background-color: white;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 0 20px rgba(0, 0, 0, 0.1);
            margin-top: 20px;
        }

        .compose-message h2 {
            color: #940b10;
            margin-bottom: 20px;
        }

        .compose-message form {
            display: flex;
            flex-direction: column;
        }

        .compose-message label {
            margin-bottom: 5px;
            font-weight: bold;
        }

        .compose-message input[type="text"],
        .compose-message textarea {
            padding: 10px;
            margin-bottom: 15px;
            border: 1px solid #ddd;
            border-radius: 5px;
            font-size: 14px;
        }

        .compose-message button {
            padding: 10px;
            background-color: #940b10;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 16px;
        }

        .compose-message button:hover {
            background-color: #b13333;
        }

        /* Radio Button Choices */
        .compose-message .choice {
            margin-bottom: 15px;
        }

        .compose-message input[type="radio"] {
            margin-right: 10px;
        }

        /* File Input */
        .compose-message input[type="file"] {
            margin-bottom: 15px;
        }
        #hamburger {
            background: none;
            border: none;
            cursor: pointer;
            font-size: 24px;
            color: #940b10;
            margin-right: 25px; /* Add some margin-right */
        } 
    </style>
</head>
<body>
    <!-- Sidebar -->
    <div class="sidebar">
        <div class="navbar-title">
            <img src="UE logo.png" alt="Logo">
            <div class="navbar-text">
                <h2>UNIVERSITY<br>OF THE EAST</h2>
                <p>MANILA CAMPUS</p>
            </div>
        </div>
        <a href="students_db.php"><i class="fa-solid fa-chalkboard"></i> <span>Dashboard</span></a>
        <a href="viewReport.php"><i class="fa-solid fa-magnifying-glass"></i> <span>View Reports</span></a>
        <a href="students_stats.php"><i class="fa-solid fa-chart-gantt"></i> <span>Statistics</span></a>
        <a href="students_rep.php"><i class="fa-solid fa-envelope"></i> <span>Report</span></a>
        <a href="students_sett.php"><i class="fas fa-sliders-h"></i> <span>Settings</span></a>
        <a href="students_notifs.php"><i class="fas fa-bell"></i> <span>Notifications</span></a>
        <a href="index.php"><i class="fas fa-sign-out-alt"></i> <span>Logout</span></a>   
    </div>

    <!-- Main content -->
    <div class="main-content">
        <div class="header">
            <button id="hamburger" class="hamburger" onclick="toggleSidebar()">
                <i class="fas fa-bars"></i>
            </button>
            <h1>Report Section</h1>
        </div>

        <!-- Compose Message Section -->
        <div class="compose-message">
            <h2>Compose Message</h2>
            <form method="POST" action="report.php" enctype="multipart/form-data">
    <div class="choice">
        <label><input type="radio" name="report-type" value="issue" required> Report an Issue</label>
        <label><input type="radio" name="report-type" value="suggestion" required> Make a Suggestion</label>
    </div>

    <label for="subject">Subject: </label>
    <select id="subject" name="subject" required>
        <option value="" disabled selected>Select Subject</option>
        <option value="hallway">Hallway</option>
        <option value="classroom">Classroom</option>
        <option value="cr">Comfort Room (CR)</option>
        <option value="elevator">Elevator</option>
        <option value="stairs">Stairs</option>
        <option value="library">Library</option>
        <option value="others">Others</option>
    </select>

    <label for="issue">Issue:</label>
    <input type="text" id="issue" name="issue" placeholder="Enter the issue" required></textarea>

    <label for="message">Message:</label>
    <textarea id="message" name="message" rows="4" required></textarea>

    <!-- Prefilled Sender Email -->
    <label for="sender_email">Your Email:</label>
    <input 
        type="email" 
        id="sender_email" 
        name="sender_email" 
        placeholder="Enter your email" 
        required 
        value="<?php echo $_SESSION['email']; ?>" 
        readonly
    >

    <!-- Prefilled Recipient Email -->
    <label for="recipient_email">Recipient Email:</label>
    <input 
        type="email" 
        id="recipient_email" 
        name="recipient_email" 
        value="admin@ue.edu.ph" 
        readonly 
    >

    <label for="file">Attach a file:</label>
    <input type="file" id="file" name="file" accept=".jpg, .jpeg, .png, .pdf, .doc, .docx">

    <button type="submit">Send Message</button>
</form>


    <script>
        function toggleSidebar() {
            var sidebar = document.querySelector('.sidebar');
            sidebar.classList.toggle('active');
            var mainContent = document.querySelector('.main-content');
            mainContent.style.marginLeft = sidebar.classList.contains('active') ? '80px' : '250px';
        }
    </script>
</body>
</html>
