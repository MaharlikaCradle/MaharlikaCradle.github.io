<?php
session_start();
// Database connection
$conn = new mysqli('localhost', 'root', '', 'maharlikascradledb');

if ($conn->connect_error) {
    echo "Connection failed: " . $conn->connect_error;
}

// Handle OTP verification
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $userEnteredOTP = $_POST['otp'];

    // Verify OTP
    if (isset($_SESSION['email']) && isset($_SESSION['otp'])) {
        $email = $_SESSION['email'];
        $otp = $_SESSION['otp'];

        if ($userEnteredOTP == $otp) {
            // OTP matched, proceed with further actions (e.g., account activation)
            // You can redirect the user to a success page or perform any necessary actions
            //echo "OTP Verified successfully. You can proceed with account activation.";
            // Clear OTP from session
            unset($_SESSION['otp']);
        } else {
			echo '<script>alert("Invalid OTP. Please try again.");</script>';
            echo '<script>window.location.href = "emailOTP.php";</script>';
        }
    } else {
		echo '<script>alert("Session expired or invalid request.");</script>';
        echo '<script>window.location.href = "emailOTP.php";</script>';
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

  <body style="background-image: url('https://images.pexels.com/photos/12914725/pexels-photo-12914725.jpeg'); background-repeat: no-repeat;" class="w-full h-screen bg-cover bg-center">
    
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
      <button onclick="window.location.href = 'booking.php'" class="bg-primary text-primary-foreground px-4 py-2 rounded-full hover:bg-primary/80">Book now</button>
    </div>
  </nav>

<div class="relative">
  <div class="absolute top-0 right-0 bg-card text-card-foreground p-8 m-8 rounded-lg max-w-md w-full">
    <h2 class="text-2xl font-bold mb-2">Create New Account</h2>
    <p class="mb-4">Already Registered? <a href="login.html" class="text-blue-600 hover:underline">Login</a></p>
	<?php if (isset($_SESSION['otp'])) : ?>
        <p class="mb-4">Your OTP: <span class="font-bold text-indigo-600"><?php echo $_SESSION['otp']; ?></span></p>
    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
        <div class="mb-4">
        	<label for="otp" class="block text-sm font-medium text-gray-700">Enter OTP:</label>
            <input type="text" id="otp" name="otp" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" required>
        </div>
        <div class="flex items-center justify-between">
            <button type="submit" class="bg-indigo-500 text-white py-2 px-4 rounded hover:bg-indigo-600 focus:outline-none focus:bg-indigo-600">Verify OTP</button>
        </div>
    </form>
	<?php else : ?>
        <p>Successfully registered!</p>
		<div class="flex items-center justify-between">
		<a href="login.html" class="block bg-indigo-500 text-white py-2 px-4 rounded hover:bg-indigo-600 focus:outline-none focus:bg-indigo-600">Sign In</a>
        </div>
    <?php endif; ?>
  </div>
</div>
  </body>
</html>