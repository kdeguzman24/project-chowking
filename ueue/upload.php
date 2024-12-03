<?php
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['profile-pic'])) {
    $target_dir = "uploads/";
    $target_file = $target_dir . basename($_FILES["profile-pic"]["name"]);
    $uploadOk = 1;
    $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

    // Check if the file is an image
    $check = getimagesize($_FILES["profile-pic"]["tmp_name"]);
    if ($check !== false) {
        $uploadOk = 1;
    } else {
        $_SESSION['upload_error'] = "File is not an image.";
        $uploadOk = 0;
    }

    // Check file size (5MB limit)
    if ($_FILES["profile-pic"]["size"] > 5000000) {
        $_SESSION['upload_error'] = "Sorry, your file is too large.";
        $uploadOk = 0;
    }

    // Allow certain file formats
    if (!in_array($imageFileType, ["jpg", "png", "jpeg", "gif"])) {
        $_SESSION['upload_error'] = "Sorry, only JPG, JPEG, PNG & GIF files are allowed.";
        $uploadOk = 0;
    }

    // Check if everything is ok and move the file
    if ($uploadOk === 1) {
        if (move_uploaded_file($_FILES["profile-pic"]["tmp_name"], $target_file)) {
            $_SESSION['profile-pic'] = $target_file; // Save the new file path in the session
            $_SESSION['upload_success'] = "Profile picture updated successfully!";
        } else {
            $_SESSION['upload_error'] = "Sorry, there was an error uploading your file.";
        }
    }
}

// Redirect back to the dashboard
header("Location: dashboard.php");
exit;
