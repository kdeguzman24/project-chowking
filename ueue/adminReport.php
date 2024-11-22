<?php
session_start();
require_once "config.php";

// Fetch reports from the database
$query = "SELECT * FROM messages WHERE recipient_email = 'admin@ue.edu.ph'"; // Adjust the query as per your needs
$result = $mysqli->query($query);

if (isset($_POST['resolve_report_id'])) {
    $report_id = intval($_POST['resolve_report_id']);
    
    // Update the status to "resolved"
    $update_query = "UPDATE messages SET status = 'resolved' WHERE id = ?";
    $stmt = $mysqli->prepare($update_query);
    $stmt->bind_param("i", $report_id);

    if ($stmt->execute()) {
        $_SESSION['message_resolved'] = "Report resolved successfully!";
    } else {
        $_SESSION['message_resolved_error'] = "Error resolving the report: " . $stmt->error;
    }

    $stmt->close();

    // Redirect to refresh the page
    header("Location: adminReport.php");
    exit();
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Reports</title>

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

        .sidebar {
            height: 96%;
            width: 250px;
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

        /* Hide the text when sidebar is active */
        .sidebar.active a span {
            display: none; /* This will hide the text */
        }

        .sidebar.active .navbar-title h2, 
        .sidebar.active .navbar-title p {
            display: none; /* This hides the University title and campus name */
        }

        .sidebar.active img {
            width: 58px; /* Keep the logo smaller when the sidebar is collapsed */
            margin-left: 10px;
        }

        .sidebar a span {
            margin-left: 10px; /* Adjust margin when text is visible */
        }

        /* Optional: Adjust icon size when the sidebar is collapsed */
        .sidebar.active a i {
            margin-left: 26px;
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
        #hamburger {
            background: none;
            border: none;
            cursor: pointer;
            font-size: 24px;
            color: #940b10;
        }
        h1 {
            color: #940b10;
            font-size: 24px;
            margin-bottom: 20px;
        }

        .widgets {
            display: flex;
            justify-content: space-around;
            margin-bottom: 20px;
        }

        .widget {
            background-color: white;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 0 20px rgba(0, 0, 0, 0.1);
            text-align: center;
            width: 20%;
        }

        .widget h2 {
            margin: 0;
            font-size: 24px;
            color: #940b10;
        }

        .widget p {
            font-size: 18px;
            color: #666;
        }

        .chart-container {
            display: flex;
            justify-content: space-around;
            flex-wrap: wrap;
        }

        .chart-box {
            background-color: white;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 0 20px rgba(0, 0, 0, 0.1);
            margin-bottom: 40px;
            width: 45%;
            min-width: 300px;
        }

        canvas {
            width: 100%;
            height: 100%;
        }

        /* Responsive styles */
        @media (max-width: 768px) {
            .chart-box {
                width: 90%;
            }
            .widget {
                width: 45%;
            }} 
    </style>
</head>
<body>

    <!-- Sidebar -->
    <div class="sidebar">
        <div class="navbar-title">
            <img src="UE logo.png" alt="Logo"> <!-- Add your image URL here -->
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
        <div class="header">
            <button id="hamburger" class="hamburger" onclick="toggleSidebar()">
                <i class="fas fa-bars"></i>
            </button>
            <h1>Reports</h1>
        </div>

        <!-- Display Success or Error Message -->
        <?php if (isset($_SESSION['message_sent'])): ?>
            <div style="color: green; padding: 10px; background-color: #dff0d8; margin-bottom: 20px;">
                <?php echo $_SESSION['message_sent']; ?>
                <?php unset($_SESSION['message_sent']); ?>
            </div>
        <?php endif; ?>

        <?php if (isset($_SESSION['message_sent_error'])): ?>
            <div style="color: red; padding: 10px; background-color: #f2dede; margin-bottom: 20px;">
                <?php echo $_SESSION['message_sent_error']; ?>
                <?php unset($_SESSION['message_sent_error']); ?>
            </div>
        <?php endif; ?>
<!-- Display Reports -->
<div class="reports">
    <?php if ($result->num_rows > 0): ?>
        <table border="1" cellpadding="10" cellspacing="0" style="width: 100%; margin-top: 20px; background-color: white;">
            <thead>
                <tr style="background-color: #940b10; color: white;">
                    <th>Sender Email</th>
                    <th>Subject</th>
                    <th>Issue</th>
                    <th>Message</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = $result->fetch_assoc()): ?>
                    <tr>
                        <td style=" color: black;"><?php echo $row['sender_email']; ?></td>
                        <td style=" color: black;"><?php echo $row['subject']; ?></td>
                        <td><?php echo nl2br(htmlspecialchars($row['issues'])); ?></td>
                        <td><?php echo nl2br(htmlspecialchars($row['message_text'])); ?></td>
                        <td><?php echo $row['status']; ?></td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    <?php else: ?>
        <p>No reports found.</p>
    <?php endif; ?>
</div>
    </div>

    <script>
        function toggleSidebar() {
            var sidebar = document.querySelector('.sidebar');
            sidebar.classList.toggle('active');
            var mainContent = document.querySelector('.main-content');
            mainContent.style.marginLeft = sidebar.classList.contains('active') ? '80px' : '250px'; // Adjust margin based on the collapsed state
        }
    </script>

</body>
</html>

