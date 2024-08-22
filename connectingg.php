<?php
// process.php
// Database connection
$conn = new mysqli('localhost', 'root', '', 'maharlikascradledb');

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}



// User login
function loginUser($email, $password) {
    global $conn;

    $stmt = $conn->prepare("SELECT * FROM Users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();
    $user = $result->fetch_assoc();

    if ($user && password_verify($password, $user['passwordHash'])) {
        // Start session and set user data
        session_start();
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['user_email'] = $user['email'];
        return "Login successful";
    } else {
        return "Invalid credentials";
    }
}

// Handle form submissions
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['action'])) {
        switch ($_POST['action']) {
            case 'register':
                // Handle registration form data
                $fullName = $_POST['fullName'];
                $email = $_POST['email'];
                $password = $_POST['password'];
                $dateOfBirth = $_POST['dateOfBirth'];
                $validIdPicture = file_get_contents($_FILES['validIdPicture']['tmp_name']);
                echo registerUser($fullName, $email, $password, $dateOfBirth, $validIdPicture);
                break;
            case 'login':
                // Handle login form data
                $email = $_POST['email'];
                $password = $_POST['password'];
                echo loginUser($email, $password);
                break;
            case 'reset':
                // Handle reset password form data
                $email = $_POST['email'];
                echo resetPassword($email);
                break;
            default:
                echo "Invalid action";
        }
    }
}
?>
