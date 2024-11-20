<?php
// update_password.php
session_start();

// Ensure the user is logged in
if (!isset($_SESSION['email'])) {
    echo json_encode(['success' => false, 'error' => 'User not logged in']);
    exit();
}

// Read the raw POST data (JSON)
$data = json_decode(file_get_contents('php://input'), true);

if (!isset($data['password'])) {
    echo json_encode(['success' => false, 'error' => 'Password not provided']);
    exit();
}

$email = $_SESSION['email'];
$newPassword = $data['password'];

// Sanitize and validate the password
if (empty($newPassword)) {
    echo json_encode(['success' => false, 'error' => 'Password cannot be empty']);
    exit();
}

// Optionally, add additional password validation here, such as length or character requirements

// Hash the new password
$hashedPassword = password_hash($newPassword, PASSWORD_BCRYPT);

// Assuming $pdo is the PDO connection object, make sure it's included or created
try {
    // Example of PDO connection (you should already have this in your actual script)
    $pdo = new PDO('mysql:host=localhost;dbname=your_database', 'username', 'password');
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Prepare and execute the update query
    $stmt = $pdo->prepare("UPDATE users SET password = :password WHERE email = :email");
    $stmt->bindParam(':password', $hashedPassword);
    $stmt->bindParam(':email', $email);
    $stmt->execute();

    echo json_encode(['success' => true]);
} catch (PDOException $e) {
    echo json_encode(['success' => false, 'error' => 'Failed to update password: ' . $e->getMessage()]);
}
?>
