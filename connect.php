<?php
// Database connection for SQL Server with Windows Authentication
$serverName = "DESKTOP-L4C2A99\SQLEXPRESS"; // or your SQL Server instance name
$connectionInfo = array("Database"=>"MaharlikasCradleDB");

try {
    $conn = sqlsrv_connect($serverName, $connectionInfo);
    
    if ($conn === false) {
        die(print_r(sqlsrv_errors(), true));
    }
} catch(Exception $e) {
    die("Connection failed: " . $e->getMessage());
}

// User registration
function registerUser($fullname, $email, $password, $dateOfBirth, $validIdPicture) {
    global $conn;
    
    // Check if email already exists
    $sql = "SELECT * FROM Users WHERE email = ?";
    $params = array($email);
    $stmt = sqlsrv_query($conn, $sql, $params);
    
    if (sqlsrv_has_rows($stmt)) {
        return "Email already registered";
    }
    
    // Hash password
    $passwordHash = password_hash($password, PASSWORD_DEFAULT);
    
    // Insert new user
    $sql = "INSERT INTO Users (fullname, email, password, dateOfBirth, validIdPicture) VALUES (?, ?, ?, ?, ?)";
    $params = array($fullname, $email, $password, $dateOfBirth, $validIdPicture);
    $stmt = sqlsrv_query($conn, $sql, $params);
    
    if ($stmt === false) {
        die(print_r(sqlsrv_errors(), true));
    }
    
    return "Registration successful";
}

// User login
function loginUser($email, $password) {
    global $pdo;
    
    $stmt = $pdo->prepare("SELECT * FROM Users WHERE email = ?");
    $stmt->execute([$email]);
    $user = $stmt->fetch();
    
    if ($user && password_verify($password, $user['password'])) {
        // Start session and set user data
        session_start();
        $_SESSION['user_id'] = $user['ID'];
        $_SESSION['user_email'] = $user['email'];
        return "Login successful";
    } else {
        return "Invalid credentials";
    }
}

// Password reset (simplified)
function resetPassword($email) {
    global $pdo;
    
    $stmt = $pdo->prepare("SELECT * FROM Users WHERE email = ?");
    $stmt->execute([$email]);
    if ($stmt->rowCount() > 0) {
        // Generate and save reset token
        $resetToken = bin2hex(random_bytes(16));
        // In a real application, you'd save this token to the database
        // and send an email with a reset link
        return "Password reset email sent";
    } else {
        return "User not found";
    }
}

// Example usage:
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['action'])) {
        switch ($_POST['action']) {
            case 'register':
                $fullname = $_POST['fullname'];
                $email = $_POST['email'];
                $password = $_POST['password'];
                $dateOfBirth = $_POST['dateOfBirth'];
                $validIdPicture = file_get_contents($_FILES['validIdPicture']['tmp_name']);
                echo registerUser($fullname, $email, $password, $dateOfBirth, $validIdPicture);
                break;
            case 'login':
                $email = $_POST['email'];
                $password = $_POST['password'];
                echo loginUser($email, $password);
                break;
            case 'reset':
                $email = $_POST['email'];
                echo resetPassword($email);
                break;
        }
    }
}
?>