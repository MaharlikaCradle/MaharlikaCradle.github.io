<?php
// Start session if not already started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Redirect to login page if user is not logged in
if (!isset($_SESSION['email'])) {
    header("Location: login.html");
    exit; // Ensure that script execution stops after redirect
}

// Database connection
$conn = new mysqli('localhost', 'root', '', 'maharlikascradledb');

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$email = $_SESSION['email'];

// Fetch available rooms
$sql = "SELECT room_id, r_name, descriptions, price FROM rooms WHERE r_available = 1";
$result = $conn->query($sql);

$rooms = [];
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $rooms[] = $row;
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Booking</title>
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
        .hidden-input {
        display: none;
        }
    </style>
</head>
<body>
<div class="min-h-screen bg-cover bg-center" style="background-image: url('https://w0.peakpx.com/wallpaper/127/366/HD-wallpaper-books-on-bookshelf.jpg');">
    <nav class="bg-card text-card-foreground p-4 flex justify-between items-center">
        <div class="flex items-center space-x-4">
            <img src="https://ph-test-11.slatic.net/shop/52dc07fb176136ae9bf1e5c3760fd024.jpeg" alt="logo" class="h-10 w-10">
            <span class="text-xl font-semibold">Maharlika’s Cradle</span>
        </div>
        <div class="flex space-x-6">
            <a href="homepage.php" class="text-foreground hover:text-primary">Home</a>
            <a href="#" class="text-foreground hover:text-primary">Rooms</a>
            <a href="#" class="text-foreground hover:text-primary">About</a>
            <?php
            // Check if session variable is set to determine logged in status
            if (isset($_SESSION['email'])) {
                // User is logged in, show profile and logout link
                echo '<a href="userprofile.php" class="text-foreground hover:text-primary">Profile</a>';
                echo '<a href="logout.php" class="text-foreground hover:text-primary">Logout</a>';
            } else {
                // User is not logged in, show login link
                echo '<a href="login.html" class="text-foreground hover:text-primary">Sign in</a>';
            }
            ?>
            <button onclick="window.location.href = 'booking.php'" class="bg-primary text-primary-foreground px-4 py-2 rounded-full hover:bg-primary/80">Book now</button>
        </div>
    </nav>
    <main class="p-8 grid grid-cols-1 md:grid-cols-2 gap-8">
        <section class="bg-card p-6 rounded-lg shadow-md">
          <h2 class="text-2xl font-bold mb-4 bg-primary text-primary-foreground px-4 py-2 text-center">Enter your details</h2>
          <form class="space-y-4 mt-4" action="booking_process2.php" method="post">
          <input type="text" name="email" class="border border-input p-2 rounded-lg hidden-input" value="<?php echo $_SESSION['email']; ?>" readonly/>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <label for="chosenrooms">Select Room:</label>
                <select name="chosenrooms" id="chosenrooms" class="border border-input p-2 rounded-lg" required>
                    <option value="">Select a room</option>
                    <?php foreach ($rooms as $room) : ?>
                        <option value="<?php echo $room['r_name']; ?>">
                            <?php echo $room['r_name'] . ' - ' . ' (₱' . $room['price'] . '/night)'; ?>
                        </option>
                    <?php endforeach; ?>
                </select>
                <label for="check_in_date">Check-in Date:</label>
                <input type="date" id="check_in_date" name="check_in_date" class="border border-input p-2 rounded-lg" required>
                <label for="check_out_date">Check-out Date:</label>
                <input type="date" id="check_out_date" name="check_out_date" class="border border-input p-2 rounded-lg" required>
                <button type="submit" onclick="return checkAvailability()" class="btn btn-link text-blue-500 hover:underline">Check Availability</button>
            </div>
       <!-- </form>-->
            <!--<div id="availability-result"></div>-->
        <!--<form class="space-y-4 mt-4" action="booking_process2.php" method="post">-->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <p>Who are you booking for?</p>
                    <label class="block">
                        <input type="radio" name="bookingFor" value="main guest" class="mr-2" /> I'm the main guest
                    </label>
                    <label class="block">
                        <input type="radio" name="bookingFor" value="someone" class="mr-2" /> I'm booking for someone else
                    </label>
                </div>
                <div>
                    <p>Are you/they traveling for work?</p>
                    <label class="block">
                        <input type="radio" name="travelingForWork" value="Yes" class="mr-2" /> Yes
                    </label>
                    <label class="block">
                        <input type="radio" name="travelingForWork" value="No" class="mr-2" /> No
                    </label>
                </div>
            </div>
            <p class="font-bold">Fill up the requirements.</p>
                <div class="grid grid-cols-1 md:grid-cols-1 gap-4">
                    <input type="text" name="names" placeholder="Enter your full name" class="border border-input p-2 rounded-lg" required />
                </div>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <input type="email" name="emailB" placeholder="Enter your email address" class="border border-input p-2 rounded-lg" required />
                    <input type="text" name="country" placeholder="Country/Region" class="border border-input p-2 rounded-lg" required />
                </div>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <input type="text" name="phone" placeholder="Enter your phone number" class="border border-input p-2 rounded-lg" required />
                </div>
                <div>
                    <p class="font-bold">Special requests</p>
                    <p>While the hotel will do its utmost to accommodate your needs, special requests cannot always be fulfilled. A specific request can always be made after your reservation is confirmed.</p>
                    <input type="text" name="special_request" placeholder="Please write your requests in English. (optional)" class="border border-input p-2 rounded-lg w-full" />
                </div>
                <div>
                    <p class="font-bold">Your arrival time</p>
                    <label class="block">
                        <p>✔ Your room will be ready for check-in at 2:00 PM</p>
                    </label>
                    <input type="text" name="arrival_time" placeholder="Add your estimated arrival time" class="border border-input p-2 rounded-lg w-full mt-2" />
                </div>
                <button type="submit" class="bg-primary text-primary-foreground px-4 py-2 rounded-lg">Submit</button>
        </form>
<script>
    function checkAvailability() {
        var xhr = new XMLHttpRequest();
        xhr.open('POST', 'checkavailability.php', true);
        xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
        xhr.onload = function() {
            if (xhr.status === 200) {
                var response = xhr.responseText;
                if (response === 'not_available') {
                    alert("Selected room/s is NOT available for the specified dates. Please choose different dates or room/s.");
                    return false;
                } else {
                    alert("Selected room/s is available for the specified dates.");
                    return true;
                }
            }
        };
        xhr.send('chosenrooms=' + document.getElementById('chosenrooms').value + '&check_in_date=' + document.getElementById('check_in_date').value + '&check_out_date=' + document.getElementById('check_out_date').value);
        return false;
    }
</script>
        </section>
    </main>
</div>
</body>
</html>