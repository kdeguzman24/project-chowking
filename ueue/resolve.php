<?php
require_once "config.php";

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['report_id'])) {
    $report_id = intval($_POST['report_id']); // Ensure the ID is an integer

    // Update the status of the report to "Resolved"
    $query = "UPDATE messages SET status = 'Resolved' WHERE id = ?";
    $stmt = $mysqli->prepare($query);
    $stmt->bind_param("i", $report_id);

    if ($stmt->execute()) {
        // Redirect back to the "View Reports" page
        header("Location: viewReport.php");
        exit();
    } else {
        // Log error if the query fails
        echo "Error: Could not update the status. " . $stmt->error;
    }

    $stmt->close();
}
$mysqli->close();
?>
