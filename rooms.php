<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Database connection
$conn = new mysqli('localhost', 'root', '', 'maharlikascradledb');

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch rooms from database
$query = "SELECT * FROM rooms";
$result = $conn->query($query);

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
    <title>Homepage</title>
    <script src="https://cdn.tailwindcss.com?plugins=forms,typography"></script>
    <script src="https://unpkg.com/unlazy@0.11.3/dist/unlazy.with-hashing.iife.js" defer></script>
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
        };
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
<body>
<div class="min-h-screen bg-cover bg-center" style="background-image: url('https://c0.wallpaperflare.com/preview/236/280/1015/philippines-baguio.jpg');">
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
     <div class="container mx-auto p-4">
        <h2 class="text-2xl font-bold my-6">Available Rooms</h2>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
            <?php foreach ($rooms as $room): ?>
                <div class="bg-white rounded-lg shadow-lg p-4">
                    <img src="<?php echo htmlspecialchars($room['imageRoom']); ?>" alt="<?php echo htmlspecialchars($room['r_name']); ?>" class="w-full h-40 object-cover rounded-lg">
                    <h3 class="text-xl font-semibold mt-2"><?php echo htmlspecialchars($room['r_name']); ?></h3>
                    <p class="text-gray-600 mt-1"><?php echo htmlspecialchars($room['descriptions']); ?></p>
                    <p class="text-gray-800 font-bold mt-2">$<?php echo number_format($room['price'], 2); ?></p>
                    <h4 class="font-medium mt-2">Amenities:</h4>
                    <p><?php echo htmlspecialchars($room['amenities']); ?></p>
                    <button onclick="window.location.href='booking.php?room_id=<?php echo $room['room_id']; ?>'" class="bg-primary text-primary-foreground px-3 py-1 rounded mt-4">Book This Room</button>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</div>
</div>
</body>
</html>