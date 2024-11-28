<?php
session_start();
require_once "config.php";

// Ensure user is logged in
if (!isset($_SESSION['email'])) {
    echo "User not logged in.";
    exit();
}

if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['report_id'], $_POST['recipient_email'])) {
    $report_id = intval($_POST['report_id']);
    $recipient_email = $_POST['recipient_email'];
} else {
    header("Location: adminReport.php");
    exit();
}

if (isset($_POST['send_reply'])) {
    $reply_message = trim($_POST['reply_message']);

    // Ensure reply message is not empty
    if (!empty($reply_message)) {
        $query = "INSERT INTO replies (report_id, recipient_email, reply_message) VALUES (?, ?, ?)";
        $stmt = $mysqli->prepare($query);

        if ($stmt) {
            $stmt->bind_param("iss", $report_id, $recipient_email, $reply_message);

            if ($stmt->execute()) {
                $_SESSION['message_sent'] = "Reply sent successfully!";
            } else {
                $_SESSION['message_sent_error'] = "Failed to send reply: " . $stmt->error;
            }
            $stmt->close();
        } else {
            $_SESSION['message_sent_error'] = "Failed to prepare query: " . $mysqli->error;
        }
        header("Location: adminReport.php");
        exit();
    } else {
        $_SESSION['message_sent_error'] = "Reply message cannot be empty.";
    }
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reply to Report</title>
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
            width: 250px;
            background-color: #940b10;
            height: 96%;
            padding: 20px 0;
            position: fixed;
            top: 0;
            left: 0;
            display: flex;
            flex-direction: column;
            align-items: center;
            border-top-right-radius: 30px;
            border-bottom-right-radius: 30px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        .sidebar a {
            color: white;
            padding: 15px;
            text-decoration: none;
            font-size: 16px;
            display: flex;
            align-items: center;
            justify-content: flex-start;
            width: 90%;
            border-radius: 5px;
            transition: background-color 0.3s ease;
        }

        .sidebar a:hover {
            background-color: #b13333;
        }

        .sidebar a i {
            margin-right: 10px;
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
        }

        .reply-box {
            background-color: white;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            margin: 20px 0;
        }

        .reply-box form {
            display: flex;
            flex-direction: column;
        }

        .reply-box textarea {
            padding: 10px;
            margin-bottom: 15px;
            border: 1px solid #ddd;
            border-radius: 5px;
            width: 100%;
            height: 150px;
        }

        .reply-box button {
            padding: 10px 20px;
            background-color: #940b10;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            transition: background 0.3s;
        }

        .reply-box button:hover {
            background-color: #b13333;
        }
    </style>
</head>
<body>
    <div class="sidebar">
    <a href="dashboard.php"><i class="fa-solid fa-chalkboard"></i> <span>Dashboard</span></a>
        <a href="students.php"><i class="fa-regular fa-user"></i> <span>Students</span></a>
        <a href="adminReport.php"><i class="fa-solid fa-magnifying-glass"></i> <span>View Reports</span></a>
        <a href="report.php"><i class="fa-solid fa-envelope"></i> <span>Report</span></a>
        <a href="inbox.php"><i class="fa-solid fa-inbox"></i> <span>Inbox</span></a>
        <a href="settings.php"><i class="fas fa-sliders-h"></i> <span>Settings</span></a>
        <a href="index.php"><i class="fas fa-sign-out-alt"></i> <span>Logout</span></a>
    </div>

    <div class="main-content">
        <h1>Reply to Report</h1>
        <div class="reply-box">
            <form action="replyToReport.php" method="POST">
                <input type="hidden" name="report_id" value="<?php echo htmlspecialchars($report_id); ?>">
                <input type="hidden" name="recipient_email" value="<?php echo htmlspecialchars($recipient_email); ?>">
                <label for="reply_message">Reply Message:</label>
                <textarea name="reply_message" id="reply_message" required></textarea>
                <button type="submit" name="send_reply">Send Reply</button>
            </form>
        </div>
    </div>
</body>
</html>
