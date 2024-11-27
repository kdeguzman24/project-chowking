<?php
session_start();

// Check if user is logged in by verifying session variable
if (!isset($_SESSION['email'])) {
    echo json_encode(['success' => false, 'error' => 'User not logged in']);
    exit();
}

// Fetch the logged-in user's email
$email = $_SESSION['email'];

// Placeholder for retrieving messages
// In a real implementation, fetch messages from the database for the logged-in user
$messages = [
    [
        'sender' => 'admin@ue.edu.ph',
        'subject' => 'Welcome',
        'message' => 'Welcome to the system!',
        'date' => '2024-11-01'
    ],
    [
        'sender' => 'john.doe@example.com',
        'subject' => 'Report Follow-Up',
        'message' => 'Your issue has been addressed. Thank you for reporting!',
        'date' => '2024-11-05'
    ]
];
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
        /* Sidebar and Main Content Styling (reused from previous code) */
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
            border-bottom-right-radius: 30px;
        }

        .sidebar a {
            padding: 15px 20px;
            text-decoration: none;
            font-size: 16px;
            color: white;
            display: flex;
            align-items: center;
            justify-content: flex-start;
            width: 90%;
            border-radius: 5px;
            transition: background 0.3s, transform 0.2s;
            margin: 10px 0;
        }

        .sidebar a:hover {
            background-color: #b13333;
            transform: scale(1.05);
        }

        .main-content {
            margin-left: 250px;
            padding: 20px;
            flex-grow: 1;
            background-color: #f4f4f4;
            height: 100vh;
            overflow-y: auto;
        }

        h1 {
            color: #940b10;
            font-size: 24px;
            margin-bottom: 20px;
        }

        .message-box {
            background-color: white;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            margin-bottom: 20px;
        }

        .message-box h3 {
            margin: 0 0 10px;
            color: #940b10;
        }

        .message-box p {
            margin: 5px 0;
        }

        .message-box .date {
            font-size: 12px;
            color: gray;
        }
    </style>
</head>
<body>
    <!-- Sidebar -->
    <div class="sidebar">
        <a href="students_db.php"><i class="fa-solid fa-chalkboard"></i> <span>Dashboard</span></a>
        <a href="viewReport.php"><i class="fa-solid fa-magnifying-glass"></i> <span>View Reports</span></a>
        <a href="students_rep.php"><i class="fa-solid fa-envelope"></i> <span>Report</span></a>
        <a href="inbox.php"><i class="fa-solid fa-inbox"></i> <span>Inbox</span></a>
        <a href="students_sett.php"><i class="fas fa-sliders-h"></i> <span>Settings</span></a>
        <a href="index.php"><i class="fas fa-sign-out-alt"></i> <span>Logout</span></a>   
    </div>

    <!-- Main Content -->
    <div class="main-content">
        <h1>Inbox</h1>
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
</body>
</html>
