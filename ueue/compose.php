<?php
session_start();

// Assuming $data is the array where 'sender' and 'message' keys are expected.
$sender = isset($data['sender']) ? $data['sender'] : 'Unknown Sender';
$message = isset($data['message']) ? $data['message'] : 'No Message';

// Check if user is logged in by verifying session variable (e.g., $_SESSION['loggedin'] or similar)

// Retrieve and sanitize recipient email from POST data
$recipientEmail = isset($_POST['recipient_email']) ? htmlspecialchars($_POST['recipient_email']) : '';
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inbox</title>
    <!-- FontAwesome Icons -->
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

        .sidebar.active {
            width: 90px;
        }

        .sidebar.active+.main-content {
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

        /* Hide the text when sidebar is active */
        .sidebar.active a span {
            display: none;
            /* This will hide the text */
        }

        .sidebar.active .navbar-title h2,
        .sidebar.active .navbar-title p {
            display: none;
            /* This hides the University title and campus name */
        }

        .sidebar.active img {
            width: 58px;
            /* Keep the logo smaller when the sidebar is collapsed */
            margin-left: 10px;
        }

        .sidebar a span {
            margin-left: 10px;
            /* Adjust margin when text is visible */
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
            }
        }

        .inbox-container {
            background-color: white;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            max-height: 70vh;
            /* Adjust height as needed */
            overflow-y: auto;
            /* Enable vertical scrolling */
            margin-bottom: 20px;
        }

        /* Message box styling */
        .message-box {
            padding: 15px;
            border: 1px solid #ddd;
            border-radius: 5px;
            margin-bottom: 10px;
            background-color: #f9f9f9;
        }

        .message-box h3 {
            margin: 0 0 10px;
            font-size: 18px;
            color: #940b10;
        }

        .message-box p {
            margin: 5px 0;
            font-size: 14px;
        }

        .message-box .date {
            font-size: 12px;
            color: #888;
            text-align: right;
        }
        .header {
    display: flex;
    align-items: center;
    justify-content: space-between; /* Space between elements */
    padding: 10px 20px;
    background-color: #f4f4f4;
    border-bottom: 2px solid #940b10;
    margin-bottom: 20px;
    position: relative; /* Make it the parent for absolute positioning */
}

#composeButton {
    padding: 10px 20px;
    background-color: #940b10;
    color: white;
    border: none;
    border-radius: 5px;
    cursor: pointer;
    font-size: 14px;
    transition: background-color 0.3s ease;
    position: absolute; /* Absolute positioning inside header */
    right: 20px; /* Distance from the right */
    top: 50%; /* Align vertically */
    transform: translateY(-50%); /* Center vertically */
}

#composeButton:hover {
    background-color: #b13333; /* Darker shade on hover */
}

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
        <a href="inbox.php"><i class="fa-solid fa-inbox"></i> <span>Inbox</span></a>
        <a href="settings.php"><i class="fas fa-sliders-h"></i> <span>Settings</span></a>
        <a href="index.php"><i class="fas fa-sign-out-alt"></i> <span>Logout</span></a>
    </div>

    <!-- Main Content -->
    <div class="main-content">
    <div class="header">
        <button id="hamburger" class="hamburger" onclick="toggleSidebar()">
            <i class="fas fa-bars"></i>
        </button>
        <h1>Inbox</h1>
        <!-- Compose Message Button -->
        <button id="composeButton" style="padding: 10px 20px; background-color: #940b10; color: white; border: none; border-radius: 5px; cursor: pointer; float: right;">
            Compose Message
        </button>
    </div>

    <div class="inbox-container">
        <?php if (empty($messages)): ?>
            <p>No messages found.</p>
        <?php else: ?>
            <?php foreach ($messages as $message): ?>
                <div class="message-box">
                    <h3><?php echo htmlspecialchars($message['subject']); ?></h3>
                    <p><strong>From:</strong> <?php echo htmlspecialchars($message['sender']); ?></p>
                    <p><strong>Message:</strong> <?php echo htmlspecialchars($message['message']); ?></p>
                    <p class="date"><?php echo htmlspecialchars($message['date']); ?></p>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>
</div>

<!-- Modal for Compose Message -->
<!-- Compose Message Modal -->
<div id="composeModal" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0, 0, 0, 0.5); z-index: 1000; align-items: center; justify-content: center;">
    <div style="background: white; padding: 20px; border-radius: 10px; width: 50%; max-width: 500px; box-shadow: 0 0 10px rgba(0, 0, 0, 0.5);">
        <h2 style="color: #940b10;">Compose Message</h2>
        <form action="send_message.php" method="POST">
            <!-- To Field Pre-filled -->
            <label for="recipient">To:</label><br>
            <input 
                type="email" 
                id="recipient" 
                name="recipient" 
                value="<?php echo $recipientEmail; ?>" 
                required 
                style="width: 95%; padding: 10px; margin: 10px 0; border: 1px solid #ddd; border-radius: 5px;"
            ><br>

            <!-- Subject -->
            <label for="subject">Subject:</label><br>
            <input 
                type="text" 
                id="subject" 
                name="subject" 
                required 
                style="width: 95%; padding: 10px; margin: 10px 0; border: 1px solid #ddd; border-radius: 5px;"
            ><br>

            <!-- Message -->
            <label for="message">Message:</label><br>
            <textarea 
                id="message" 
                name="message" 
                required 
                rows="5" 
                style="width: 95%; padding: 10px; margin: 10px 0; border: 1px solid #ddd; border-radius: 5px;"
            ></textarea><br>

            <!-- Submit and Cancel Buttons -->
            <button 
                type="submit" 
                style="padding: 10px 20px; background-color: #940b10; color: white; border: none; border-radius: 5px; cursor: pointer;"
            >
                Send
            </button>
            <button 
                type="button" 
                onclick="closeModal()" 
                style="padding: 10px 20px; background-color: #888; color: white; border: none; border-radius: 5px; cursor: pointer; margin-left: 10px;"
            >
                Cancel
            </button>
        </form>
    </div>
</div>

<script>
    // Open Compose Modal
    document.getElementById('composeButton').addEventListener('click', function () {
        document.getElementById('composeModal').style.display = 'flex';
    });

    window.onload = function () {
        const recipientEmail = "<?php echo $recipientEmail; ?>";
        if (recipientEmail) {
            document.getElementById('composeModal').style.display = 'flex';
        }
    };

    // Function to close the Compose Modal
    function closeModal() {
        document.getElementById('composeModal').style.display = 'none';
        document.getElementById('recipient').value = '';
        document.getElementById('subject').value = '';
        document.getElementById('message').value = '';
    }
    function toggleSidebar() {
            var sidebar = document.querySelector('.sidebar');
            sidebar.classList.toggle('active');
            var mainContent = document.querySelector('.main-content');
            mainContent.style.marginLeft = sidebar.classList.contains('active') ? '80px' : '250px'; // Adjust margin based on the collapsed state
        }
</script>

</body>

</html>