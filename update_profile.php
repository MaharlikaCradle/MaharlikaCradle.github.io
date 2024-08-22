<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['email'])) {
    header('Location: login.html');
    exit;
}

// Database connection
$conn = new mysqli('localhost', 'root', '', 'maharlikascradledb');

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$email = $_SESSION['email'];
$fullName = $_POST['fullName'];
$password = $_POST['password'];
$confirm_password = $_POST['confirm_password'];

// Update fullName if provided
if (!empty($fullName) && $fullName !== $_SESSION['fullName']) {
    $sql_update_fullName = "UPDATE users SET fullName='$fullName' WHERE email='$email'";
    if ($conn->query($sql_update_fullName) === TRUE) {
        $_SESSION['fullName'] = $fullName; // Update session variable
    } else {
        echo "Error updating full name: " . $conn->error;
        exit;
    }
} 

// Validate and update password if provided and valid
if (!empty($password) && $password === $confirm_password) {
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);
    $sql_update_password = "UPDATE users SET passwordHash='$hashed_password' WHERE email='$email'";
    if ($conn->query($sql_update_password) !== TRUE) {
        echo "Error updating password: " . $conn->error;
        exit;
    }
}

echo '<script>alert("Profile updated successfully");</script>';
echo '<script>window.location.href = "userprofile.php";</script>';


$conn->close();
?>