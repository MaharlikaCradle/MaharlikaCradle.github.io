<?php
require_once 'session.php';
require_once 'newC.php'; // Include your database connection script

function loginUser($email, $password) {
    global $pdo;

    // Prepare SQL query
    $stmt = $pdo->prepare("SELECT * FROM users WHERE email = ?");
    $stmt->execute([$email]);
    $user = $stmt->fetch();

    // Verify password
    if ($user && password_verify($password, $user['passwordHash'])) {
        // Start session and set user data
        $_SESSION['email'] = $user['email'];
        $_SESSION['user_id'] = $user['id'];
        return true;
    } else {
        return false;
    }
}

// Handle login form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['email']) && isset($_POST['password'])) {
        $email = $_POST['email'];
        $password = $_POST['password'];
        
        if (loginUser($email, $password)) {
            header("Location: homepage.php");
            exit();
        } else {      
            echo '<script>alert("Invalid cridentials.");</script>';
            echo '<script>window.location.href = "login.html";</script>';
        }
    }
}
?>