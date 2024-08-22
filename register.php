<?php
session_start();
// Database connection
$conn = new mysqli('localhost', 'root', '', 'maharlikascradledb');

if ($conn->connect_error) {
    echo "Connection failed: " . $conn->connect_error;
}

// Function to generate OTP
function generateOTP() {
    return rand(100000, 999999); // Generate a 6-digit OTP
}

// User registration function
function registerUser($fullName, $email, $password, $dateOfBirth, $validIdPicture) {
    global $conn;

    // Check if email already exists
    $stmt = $conn->prepare("SELECT * FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        echo '<script>alert("Email already registered");</script>';
        echo '<script>window.location.href = "register.html";</script>';
    }

    // Hash password
    $passwordHash = password_hash($password, PASSWORD_DEFAULT);

    // Generate OTP
    $otp = generateOTP();

    // File upload handling
    $validIdPicturePath = 'validID/' . basename($_FILES['validIdPicture']['name']);
    if (move_uploaded_file($_FILES['validIdPicture']['tmp_name'], $validIdPicturePath)) {
        // Insert new user with file path and OTP
        $stmt = $conn->prepare("INSERT INTO users (fullName, email, passwordHash, dateOfBirth, validIdPicture, otp) VALUES (?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("sssssi", $fullName, $email, $passwordHash, $dateOfBirth, $validIdPicturePath, $otp);
        if ($stmt->execute()) {
            $_SESSION['email'] = $email;
            $_SESSION['otp'] = $otp; // Store OTP in session for verification
            header("Location: emailOTP.php");
            exit();
        } else {
            return "Error during registration: " . $stmt->error;
        }
    } else {
        echo '<script>alert("Error moving file.");</script>';
        echo '<script>window.location.href = "register.html";</script>';
    }
}

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $fullName = $_POST['fullName'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $dateOfBirth = $_POST['dateOfBirth'];
    $validIdPicture = $_FILES['validIdPicture']['tmp_name']; // Adjust based on your form field name

    echo registerUser($fullName, $email, $password, $dateOfBirth, $validIdPicture);
}
?>