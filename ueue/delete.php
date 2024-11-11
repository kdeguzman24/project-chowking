<?php
// Process delete operation after confirmation
if (isset($_POST["email"]) && !empty($_POST["email"])) {
    // Include config file
    require_once "config.php";

    // Check if the email exists in the database
    $check_sql = "SELECT * FROM users WHERE LOWER(email) = LOWER(?)";
    if ($stmt_check = $mysqli->prepare($check_sql)) {
        $stmt_check->bind_param("s", $param_email);
        $param_email = trim($_POST["email"]);

        if ($stmt_check->execute()) {
            $result = $stmt_check->get_result();
            if ($result->num_rows == 0) {
                echo "No account found with that email.";
            } else {
                // Proceed to delete the account
                $sql = "DELETE FROM users WHERE email = ?";
                if ($stmt = $mysqli->prepare($sql)) {
                    $stmt->bind_param("s", $param_email);

                    if ($stmt->execute()) {
                        // Success: Log out and redirect to homepage
                        session_start();
                        session_unset();
                        session_destroy();
                        header("location: index.php");
                        exit();
                    } else {
                        echo "Oops! Something went wrong. Please try again later.";
                    }
                    $stmt->close();
                }
            }
        } else {
            echo "Error executing query. Please try again later.";
        }
        $stmt_check->close();
    } else {
        echo "Error preparing the query: " . $mysqli->error; // Show SQL preparation error
    }

    // Close the database connection
    $mysqli->close();
} else {
    // If 'email' parameter is not set in POST, display the confirmation prompt
    if (empty(trim($_GET["email"]))) {
        header("location: students_sett.php");
        exit();
    } else {
        // Confirmation prompt in HTML format
        $email = htmlspecialchars(trim($_GET["email"])); // Sanitize and trim the email value
        echo '<form action="delete.php" method="post">
                <input type="hidden" name="email" value="' . $email . '" />
                <p>Are you sure you want to delete your account?</p>
                <p>
                    <input type="submit" value="Yes" class="btn btn-danger">
                    <a href="students_sett.php" class="btn btn-secondary ml-2">No</a>
                </p>
              </form>';
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Delete Record</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        .wrapper {
            width: 600px;
            margin: 0 auto;
        }
    </style>
</head>
<body>
    <div class="wrapper">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <h2 class="mt-5 mb-3">Delete Record</h2>
                    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
                        <div class="alert alert-danger">
                            <input type="hidden" name="email" value="<?php echo htmlspecialchars(trim($_GET["email"])); ?>" />
                            <p>Are you sure you want to delete your account?</p>
                            <p>
                                <input type="submit" value="Yes" class="btn btn-danger">
                                <a href="students_sett.php" class="btn btn-secondary ml-2">No</a>
                            </p>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
