<?php
session_start();

// Database connection
$conn = new mysqli('localhost', 'root', '', 'maharlikascradledb');

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$otpSent = false;
$otp = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Handle sending OTP
    if (isset($_POST['email'])) {
        $email = $_POST['email'];

        // Check if email exists in the database
        $sql_check_email = "SELECT * FROM users WHERE email = ?";
        $stmt_check_email = $conn->prepare($sql_check_email);

        if (!$stmt_check_email) {
            die("Error preparing statement: " . $conn->error);
        }

        $stmt_check_email->bind_param("s", $email);
        if (!$stmt_check_email->execute()) {
            die("Error executing statement: " . $stmt_check_email->error);
        }

        $result = $stmt_check_email->get_result();
        if ($result->num_rows == 0) {
            echo '<script>alert("Email not found. Please enter a registered email address.");</script>';
            echo '<script>window.location.href = "resetPassword.php";</script>';
            exit;
        }

        // Generate OTP
        $otp = rand(100000, 999999);

        // Store OTP in session
        $_SESSION['email'] = $email;
        $_SESSION['otp'] = $otp;

        // Update OTP in database
        $sql_update_otp = "UPDATE users SET otp = ? WHERE email = ?";
        $stmt_update_otp = $conn->prepare($sql_update_otp);

        if (!$stmt_update_otp) {
            die("Error preparing statement: " . $conn->error);
        }

        $stmt_update_otp->bind_param("ss", $otp, $email);
        if (!$stmt_update_otp->execute()) {
            die("Error executing statement: " . $stmt_update_otp->error);
        }

        // Set flag to display OTP in UI
        $otpSent = true;
    }

    // Handle OTP verification and password update
    if (isset($_POST['otp'], $_POST['new-password'], $_POST['confirm-password'])) {
        $userEnteredOTP = $_POST['otp'];
        $newPassword = $_POST['new-password'];
        $confirmPassword = $_POST['confirm-password'];

        if ($newPassword !== $confirmPassword) {
            echo '<script>alert("Passwords do not match. Please try again.");</script>';
            echo '<script>window.location.href = "resetPassword.php";</script>';
            exit;
        }

        if (isset($_SESSION['email'], $_SESSION['otp'])) {
            $email = $_SESSION['email'];
            $otp = $_SESSION['otp'];

            if ($userEnteredOTP == $otp) {
                // OTP matched, update password
                $hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);
                $sql_update_password = "UPDATE users SET passwordHash = ?, otp = NULL WHERE email = ?";
                $stmt_update_password = $conn->prepare($sql_update_password);

                if (!$stmt_update_password) {
                    die("Error preparing statement: " . $conn->error);
                }

                $stmt_update_password->bind_param("ss", $hashedPassword, $email);
                if (!$stmt_update_password->execute()) {
                    die("Error executing statement: " . $stmt_update_password->error);
                }

                // Clear OTP from session
                unset($_SESSION['otp']);

                // Redirect to login page after successful password update
                echo '<script>alert("Password has been updated successfully.");</script>';
                echo '<script>window.location.href = "login.html";</script>';
                exit;
            } else {
                echo '<script>alert("Invalid OTP. Please try again.");</script>';
            }
        } else {
            echo '<script>alert("Session expired or invalid request.");</script>';
        }
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://cdn.tailwindcss.com?plugins=forms,typography"></script>
    <script src="https://unpkg.com/unlazy@0.11.3/dist/unlazy.with-hashing.iife.js" defer init></script>
    <script type="text/javascript">
        window.tailwind.config = {
            darkMode: ['class'],
            theme: {
                extend: {
                    colors: {
                        border: 'hsl(var(--border))',
                        input: 'hsl(var(--input))',
                        ring: 'hsl(var(--ring))',
                        background: 'hsl(var(--background))',
                        foreground: 'hsl(var(--foreground))',
                        primary: {
                            DEFAULT: 'hsl(var(--primary))',
                            foreground: 'hsl(var(--primary-foreground))'
                        },
                        secondary: {
                            DEFAULT: 'hsl(var(--secondary))',
                            foreground: 'hsl(var(--secondary-foreground))'
                        },
                        destructive: {
                            DEFAULT: 'hsl(var(--destructive))',
                            foreground: 'hsl(var(--destructive-foreground))'
                        },
                        muted: {
                            DEFAULT: 'hsl(var(--muted))',
                            foreground: 'hsl(var(--muted-foreground))'
                        },
                        accent: {
                            DEFAULT: 'hsl(var(--accent))',
                            foreground: 'hsl(var(--accent-foreground))'
                        },
                        popover: {
                            DEFAULT: 'hsl(var(--popover))',
                            foreground: 'hsl(var(--popover-foreground))'
                        },
                        card: {
                            DEFAULT: 'hsl(var(--card))',
                            foreground: 'hsl(var(--card-foreground))'
                        },
                    },
                }
            }
        }
    </script>
    <style type="text/tailwindcss">
        @layer base {
            :root {
                --background: 0 0% 100%;
                --foreground: 240 10% 3.9%;
                --card: 0 0% 100%;
                --card-foreground: 240 10% 3.9%;
                --popover: 0 0% 100%;
                --popover-foreground: 240 10% 3.9%;
                --primary: 240 5.9% 10%;
                --primary-foreground: 0 0% 98%;
                --secondary: 240 4.8% 95.9%;
                --secondary-foreground: 240 5.9% 10%;
                --muted: 240 4.8% 95.9%;
                --muted-foreground: 240 3.8% 46.1%;
                --accent: 240 4.8% 95.9%;
                --accent-foreground: 240 5.9% 10%;
                --destructive: 0 84.2% 60.2%;
                --destructive-foreground: 0 0% 98%;
                --border: 240 5.9% 90%;
                --input: 240 5.9% 90%;
                --ring: 240 5.9% 10%;
                --radius: 0.5rem;
            }
            .dark {
                --background: 240 10% 3.9%;
                --foreground: 0 0% 98%;
                --card: 240 10% 3.9%;
                --card-foreground: 0 0% 98%;
                --popover: 240 10% 3.9%;
                --popover-foreground: 0 0% 98%;
                --primary: 0 0% 98%;
                --primary-foreground: 240 5.9% 10%;
                --secondary: 240 3.7% 15.9%;
                --secondary-foreground: 0 0% 98%;
                --muted: 240 3.7% 15.9%;
                --muted-foreground: 240 5% 64.9%;
                --accent: 240 3.7% 15.9%;
                --accent-foreground: 0 0% 98%;
                --destructive: 0 62.8% 30.6%;
                --destructive-foreground: 0 0% 98%;
                --border: 240 3.7% 15.9%;
                --input: 240 3.7% 15.9%;
                --ring: 240 4.9% 83.9%;
            }
        }
    </style>
</head>
<body style="background-image: url('https://www.theexodoers.com/wp-content/uploads/2023/09/Drop-By-the-Bell-Church.png'); background-repeat: no-repeat;" class="w-full h-screen bg-cover bg-center">
    <nav class="bg-card text-card-foreground p-4 flex justify-between items-center">
        <div class="flex items-center space-x-4">
        <img src="https://ph-test-11.slatic.net/shop/52dc07fb176136ae9bf1e5c3760fd024.jpeg" alt="logo" class="h-10 w-10">
            <span class="text-xl font-semibold">Maharlika’s Cradle</span>
        </div>
        <div class="flex space-x-6">
            <a href="homepage.php" class="text-foreground hover:text-primary">Home</a>
            <a href="#" class="text-foreground hover:text-primary">Rooms</a>
            <a href="#" class="text-foreground hover:text-primary">About</a>
            <a href="login.html" class="text-foreground hover:text-primary">Sign in</a>
            <button class="bg-primary text-primary-foreground px-4 py-2 rounded-full hover:bg-primary/80">Book now</button>
        </div>
    </nav>

    <div class="relative">
        <div class="absolute top-0 right-0 bg-card text-card-foreground p-8 m-8 rounded-lg max-w-md w-full">
            <h2 class="text-2xl font-bold mb-2">Forgot Password</h2>
            <p class="mb-4">Already Registered? <a href="login.html" class="text-blue-600 hover:underline">Login</a></p>
            <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
                <?php if (!$otpSent) { ?>
                    <div class="mb-4">
                        <label for="email" class="block text-sm font-medium text-gray-700">Email Address:</label>
                        <input type="email" id="email" name="email" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" required>
                    </div>
                    <div class="flex items-center justify-between">
                        <button type="submit" class="bg-indigo-500 text-white py-2 px-4 rounded hover:bg-indigo-600 focus:outline-none focus:bg-indigo-600">Send OTP</button>
                    </div>
                <?php } else { ?>
                    <p class="mb-4">Your OTP: <span class="font-bold text-indigo-600"><?php echo $otp; ?></span></p>
                    <div class="mb-4">
                        <label for="otp" class="block text-sm font-medium text-gray-700">Enter OTP:</label>
                        <input type="text" id="otp" name="otp" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" required>
                    </div>
                    <div class="mb-4">
                        <label for="new-password" class="block text-sm font-medium text-gray-700">New Password:</label>
                        <input type="password" id="new-password" name="new-password" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" required>
                    </div>
                    <div class="mb-4">
                        <label for="confirm-password" class="block text-sm font-medium text-gray-700">Confirm Password:</label>
                        <input type="password" id="confirm-password" name="confirm-password" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" required>
                    </div>
                    <div class="flex items-center justify-between">
                        <button type="submit" class="bg-indigo-500 text-white py-2 px-4 rounded hover:bg-indigo-600 focus:outline-none focus:bg-indigo-600">Reset Password</button>
                    </div>
                <?php } ?>
            </form>
        </div>
    </div>
</body>
</html>
