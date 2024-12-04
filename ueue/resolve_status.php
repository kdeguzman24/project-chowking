<?php
require 'config.php'; // Use your existing database connection setup

if (isset($_POST['id'])) {
    $id = intval($_POST['id']); // Sanitize input

    // Update the status
    $sql = "UPDATE messages SET status = 'resolved' WHERE id = ?";
    $stmt = $mysqli->prepare($sql);

    if ($stmt) {
        $stmt->bind_param('i', $id);
        if ($stmt->execute()) {
            // Success: Status updated
        } else {
            echo "Error updating status: " . $stmt->error;
        }
        $stmt->close();
    } else {
        echo "Error preparing statement: " . $conn->error;
    }
}
header('Location: inbox.php'); // Redirect to refresh the table
exit;
?>
