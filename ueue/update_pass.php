<?php
// update_password.php
session_start();

// Ensure the user is logged in
if (!isset($_SESSION['email'])) {
    echo json_encode(['success' => false, 'error' => 'User not logged in']);
    exit();
}

$email = $_SESSION['email'];
$newPassword = $_POST['password'];

// Sanitize and validate the password
if (empty($newPassword)) {
    echo json_encode(['success' => false, 'error' => 'Password cannot be empty']);
    exit();
}

// Update the password in the database (you'll need to use prepared statements here to avoid SQL injection)
$hashedPassword = password_hash($newPassword, PASSWORD_BCRYPT);

// Assume you have a database connection stored in $pdo
try {
    $stmt = $pdo->prepare("UPDATE users SET password = :password WHERE email = :email");
    $stmt->bindParam(':password', $hashedPassword);
    $stmt->bindParam(':email', $email);
    $stmt->execute();

    echo json_encode(['success' => true]);
} catch (PDOException $e) {
    echo json_encode(['success' => false, 'error' => 'Failed to update password: ' . $e->getMessage()]);
}
?>
