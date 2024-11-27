<?php
// Start the session
session_start();

// Check if session variables exist
if (isset($_SESSION['username'])) {
    $username = $_SESSION['username'];
} else {
    $username = "Guest"; // Default to Guest if session variable is not set
}

// Include config file
require_once "config.php";

// Count the total number of reports
$totalReportsQuery = "SELECT COUNT(*) AS report_count FROM messages";
$totalReportsResult = $mysqli->query($totalReportsQuery);
if ($totalReportsResult) {
    $totalReportsRow = $totalReportsResult->fetch_assoc();
    $totalReportCount = $totalReportsRow['report_count'];
} else {
    $totalReportCount = 0; // If there's an error, show 0
}

// Count the number of reports today (replace 'created_at' with your actual column name)
$reportsTodayQuery = "SELECT COUNT(*) AS report_count_today FROM messages WHERE DATE(timestamp) = CURDATE()";
$reportsTodayResult = $mysqli->query($reportsTodayQuery);
if ($reportsTodayResult) {
    $reportsTodayRow = $reportsTodayResult->fetch_assoc();
    $reportsTodayCount = $reportsTodayRow['report_count_today'];
} else {
    $reportsTodayCount = 0; // If there's an error, show 0
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">



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
            width: 250px;
            /* Adjusted for better responsiveness */
            position: fixed;
            top: 0;
            left: 0;
            background-color: #940b10;
            padding: 20px 0;
            display: flex;
            flex-direction: column;
            align-items: center;
            border-top-right-radius: 30px;
            /* Rounded top-right corner */
            border-bottom-right-radius: 30px;
            /* Rounded bottom-right corner */
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
            /* Centered text */
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
            padding: 15px 20px;
            /* Increased padding for better click area */
            text-decoration: none;
            font-size: 16px;
            color: white;
            display: flex;
            align-items: center;
            justify-content: flex-start;
            width: 90%;
            /* Adjusted for better spacing */
            border-radius: 5px;
            transition: background 0.3s, transform 0.2s;
            /* Added transform for hover */
            margin: 10px 0;
            /* Reduced margin */
        }

        .sidebar a i {
            margin-left: 30px;
            margin-right: 15px;
            font-size: 24px;
            /* Increased icon size for visibility */
        }

        .sidebar a:hover {
            background-color: #b13333;
            transform: scale(1.05);
            /* Added scaling effect on hover */
        }

        .main-content {
            margin-left: 250px;
            /* Adjusted margin */
            padding: 20px;
            flex-grow: 1;
            background-color: #f4f4f4;
            height: 100vh;
            overflow-y: auto;
            transition: margin-left 0.3s;
        }

        .sidebar a span {
            margin-left: 10px;
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
            transition: background 0.3s, transform 0.2s;
            /* Added transition for buttons */
        }

        .quick-actions button:hover {
            background-color: #b13333;
            transform: scale(1.05);
            /* Scale effect on hover */
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

        /* Calendar Wrapper */
        #calendar {
            display: grid;
            grid-template-columns: repeat(7, 1fr);
            gap: 5px;
            /* Reduced gap between cells */
            padding: 20px;
            /* Slightly increased padding for better spacing */
            background: #f8f9fa;
            border: 1px solid #ddd;
            border-radius: 8px;
            width: 95%;
            /* Take full width of the parent container */
            max-width: none;
            /* Remove width constraints */
            max-height: 800px;
            /* Keep height constraint if necessary */
            margin: 10px auto;
            /* Center the calendar */
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            overflow-y: auto;
            /* Add scroll for content if it exceeds height */
        }


        /* Header */
        .calendar-header {
            grid-column: span 7;
            display: flex;
            justify-content: space-between;
            align-items: center;
            background: #940b10;
            color: #fff;
            padding: 10px;
            /* Reduced padding */
            border-radius: 8px;
            font-size: 16px;
            /* Slightly smaller font size */
        }

        /* Navigation Buttons and Select */
        .calendar-nav {
            display: flex;
            align-items: center;
            gap: 5px;
            /* Reduced gap between navigation items */
        }

        .calendar-nav button {
            background-color: #721610;
            color: #fff;
            border: none;
            padding: 6px 12px;
            /* Reduced padding */
            font-size: 14px;
            /* Slightly smaller font size */
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        .calendar-nav button:hover {
            background-color: #5a0c0f;
        }

        .calendar-nav select,
        .calendar-nav input[type="number"] {
            padding: 4px 8px;
            /* Reduced padding */
            font-size: 14px;
            /* Slightly smaller font size */
            border: 1px solid #ddd;
            border-radius: 5px;
        }

        /* Days of the Week */
        .calendar-day {
            text-align: center;
            padding: 10px 0;
            /* Reduced padding */
            font-weight: bold;
            color: #333;
            background: #f1f1f1;
            border-radius: 3px;
            /* Reduced border radius */
            font-size: 14px;
            /* Slightly smaller font size */
        }

        .calendar-day:nth-child(7n) {
            color: #c0392b;
        }

        .calendar-day:nth-child(7n-6) {
            color: #2980b9;
        }

        /* Blank Days (Before 1st Day of Month) */
        .calendar-blank {
            background: none;
        }

        /* Calendar Dates */
        .calendar-date {
            text-align: center;
            padding: 8px;
            /* Reduced padding */
            font-size: 14px;
            /* Slightly smaller font size */
            color: #555;
            background: #fff;
            border: 1px solid #ddd;
            border-radius: 3px;
            transition: transform 0.2s ease, background-color 0.3s ease;
            cursor: pointer;
        }

        .calendar-date:hover {
            background: #e6e6e6;
            transform: scale(1.05);
        }

        /* Today's Date */
        .calendar-date.today {
            background: #940b10;
            color: #fff;
            font-weight: bold;
        }

        .calendar-date.today {
            background: #940b10;
            /* Deep red */
            color: #fff;
            font-weight: bold;
            border: 2px solid #fff;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.3);
            /* Adds a glow effect */
        }
    </style>
</head>

<body>

    <!-- Sidebar -->
    <nav class="sidebar" role="navigation">
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
    </nav>

    <!-- Main Content -->
    <div class="main-content" role="main">
        <div class="fixed-header">
            <img src="ProfilePic.png" alt="Profile Picture of <?php echo htmlspecialchars($_SESSION['username']); ?>">
            <div class="name-position">
                <h2><?php echo htmlspecialchars($_SESSION['username']); ?></h2>

                <!-- Check if the user's email is 'admin@ue.edu.ph' -->
                <?php if ($_SESSION['email'] == 'admin@ue.edu.ph'): ?>
                    <p>Admin</p>
                <?php else: ?>
                    <p>Student</p>
                <?php endif; ?>
            </div>
        </div>

        <h1>Welcome to Your Dashboard!</h1>

        <!-- Widgets Section -->
        <div class="widgets">
            <div class="widget">
                <h3>Total Reports</h3>
                <p><?php echo $totalReportCount; ?></p>
            </div>
            <div class="widget">
                <h3>Reports Today</h3>
                <p><?php echo $reportsTodayCount; ?></p>
            </div>

            <div class="widget">
                <h3>Resolve</h3>
                <p>3</p>
            </div>
        </div>

        <!-- Recent Activity -->
        <div class="recent-activity">
            <h2>Recent Reports</h2>
            <ul>
                <li>Submitted report #12345</li>
                <li>Feedback received from admin</li>
                <li>Resolved issue #67890</li>
            </ul>
        </div>

        <!-- Calendar Section -->
        <div class="calendar">
            <h2>Calendar</h2>
            <div id="calendar"></div>
        </div>


        <!-- Quick Actions -->
        <div class="quick-actions">
            <h2>Quick Actions</h2>
            <a href="viewReport.php">
                <button>View Reports</button>
            </a>

        </div>

        <!-- Footer -->
        <footer>
            <p>&copy; 2024 University of the East. All Rights Reserved.</p>
        </footer>
    </div>
    <script>
        let currentYear = new Date().getFullYear();
        let currentMonth = new Date().getMonth();
        function generateCalendar(year, month) {
            const calendar = document.getElementById("calendar");
            calendar.innerHTML = "";

            // Header
            const header = document.createElement("div");
            header.classList.add("calendar-header");

            const monthYear = document.createElement("span");
            monthYear.classList.add("month-year");
            const monthNames = [
                "January", "February", "March", "April", "May", "June",
                "July", "August", "September", "October", "November", "December"
            ];
            monthYear.textContent = `${monthNames[month]} ${year}`;
            header.appendChild(monthYear);

            // Navigation buttons and other elements (as you've already implemented)
            const navContainer = document.createElement("div");
            navContainer.classList.add("calendar-nav");

            const prevButton = document.createElement("button");
            prevButton.textContent = "<";
            prevButton.onclick = () => {
                currentMonth--;
                if (currentMonth < 0) {
                    currentMonth = 11;
                    currentYear--;
                }
                generateCalendar(currentYear, currentMonth);
            };

            const nextButton = document.createElement("button");
            nextButton.textContent = ">";
            nextButton.onclick = () => {
                currentMonth++;
                if (currentMonth > 11) {
                    currentMonth = 0;
                    currentYear++;
                }
                generateCalendar(currentYear, currentMonth);
            };

            const monthSelector = document.createElement("select");
            monthSelector.innerHTML = monthNames
                .map((m, index) => `<option value="${index}">${m}</option>`)
                .join("");
            monthSelector.value = month;
            monthSelector.onchange = () => {
                currentMonth = parseInt(monthSelector.value);
                generateCalendar(currentYear, currentMonth);
            };

            const yearSelector = document.createElement("input");
            yearSelector.type = "number";
            yearSelector.value = year;
            yearSelector.style.width = "60px";
            yearSelector.style.marginLeft = "5px";
            yearSelector.onchange = () => {
                currentYear = parseInt(yearSelector.value);
                generateCalendar(currentYear, currentMonth);
            };

            navContainer.appendChild(prevButton);
            navContainer.appendChild(nextButton);
            navContainer.appendChild(monthSelector);
            navContainer.appendChild(yearSelector);

            header.appendChild(navContainer);
            calendar.appendChild(header);

            // Days of the week
            const daysOfWeek = ["Sun", "Mon", "Tue", "Wed", "Thu", "Fri", "Sat"];
            daysOfWeek.forEach(day => {
                const dayElement = document.createElement("div");
                dayElement.classList.add("calendar-day");
                dayElement.style.fontWeight = "bold";
                dayElement.textContent = day;
                calendar.appendChild(dayElement);
            });

            // Get the first day and number of days in the month
            const firstDay = new Date(year, month, 1).getDay();
            const daysInMonth = new Date(year, month + 1, 0).getDate();

            // Get today's date
            const today = new Date();
            const todayYear = today.getFullYear();
            const todayMonth = today.getMonth();
            const todayDate = today.getDate();

            // Add blank spaces for the days before the first day of the month
            for (let i = 0; i < firstDay; i++) {
                const blank = document.createElement("div");
                calendar.appendChild(blank);
            }

            // Add days to the calendar
            for (let day = 1; day <= daysInMonth; day++) {
                const dayElement = document.createElement("div");
                dayElement.classList.add("calendar-date");
                dayElement.textContent = day;

                // Debug: Log to check the comparison
                console.log(`Checking day: ${day}, Today: ${todayDate}`);

                // Check if it's today's date
                if (year === todayYear && month === todayMonth && day === todayDate) {
                    dayElement.classList.add("today"); // Add the "today" class to highlight today's date
                }

                calendar.appendChild(dayElement);
            }
        }


        // Initialize
        generateCalendar(currentYear, currentMonth);

    </script>

</body>

</html>