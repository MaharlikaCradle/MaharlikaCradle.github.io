<?php
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = $_POST['email'];

    // Database connection parameters
    $host = 'localhost';
    $dbname = 'maharlikascradledb';
    $username = 'root';
    $password = ''; // By default, XAMPP's MySQL has no password for root user

    try {
        $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $username, $password);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        // Generate a reset token
        $resetToken = bin2hex(random_bytes(16));
        $sql = "UPDATE users SET reset_token = :reset_token WHERE email = :email";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':reset_token', $resetToken, PDO::PARAM_STR);
        $stmt->bindParam(':email', $email, PDO::PARAM_STR);
        $stmt->execute();

        // Send reset email (for simplicity, we just display the link)
        $resetLink = "http://yourdomain.com/reset_password.php?email=$email&token=$resetToken";
        echo "Password reset link: <a href='$resetLink'>$resetLink</a>";
    } catch (PDOException $e) {
        echo "Database connection failed: " . $e->getMessage();
    }
}
?>