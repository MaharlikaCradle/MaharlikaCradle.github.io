<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Redirect to login page if user is not logged in
if (!isset($_SESSION['email'])) {
    header("Location: login.html");
    exit; // Ensure that script execution stops after redirect
}

$conn = new mysqli('localhost', 'root', '', 'maharlikascradledb');

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$email = $_SESSION['email'];

// Assuming you have these values from session
/*$r_name = $_SESSION['chosenrooms']; // The chosen rooms (e.g., "Luna, Soleil")
$check_in = $_SESSION['check_in_date']; // The check-in date
$check_out = $_SESSION['check_out_date']; // The check-out date*/

// Fetch booking details based on the email
if (isset($_SESSION['email'])) {
    $email = $_SESSION['email']; // Assuming you have the user's email stored in the session

    // Prepare the SQL statement
    //$sql = "SELECT chosenrooms, check_in_date, check_out_date, totalPrice FROM bookings WHERE email = ?";
    $sql = "SELECT * FROM bookings WHERE email = ?";
    $stmt = $conn->prepare($sql);

    // Bind the email parameter
    $stmt->bind_param("s", $email);

    // Execute the statement
    $stmt->execute();

    // Get the result
    $result = $stmt->get_result();

    // Fetch the booking details
    if ($row = $result->fetch_assoc()) {
        $email = $row['email'];
        $room_name = $row['chosenrooms'];
        $check_in = $row['check_in_date'];
        $check_out = $row['check_out_date'];
        $total_price = $row['totalPrice'];
        $names = $row['names'];
        $emailB = $row['emailB'];
        $phone = $row['phone'];
        $country = $row['country'];
        $special_request = $row['special_request'];
        $arrival_time = $row['arrival_time'];
        $bookingFor = $row['bookingFor'];
        $travelingForWork = $row['travelingForWork'];
        // Calculate the total price based on the fetched results
    }

    // Close the statement
    $stmt->close();
} else {
    die("User email not set in session.");
}

// Close the database connection
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
        input[readonly] {
        border: none; /* Remove the border */
        outline: none; /* Optional: Remove the outline */
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
    <form class="space-y-4 mt-4" action="paymentbooking_process.php" method="post">
    <input type="text" name="email" class="border border-input p-2 rounded-lg hidden-input" value="<?php echo $_SESSION['email']; ?>" readonly/>
    <h2 class="text-2xl font-bold mb-4 bg-primary text-primary-foreground px-4 py-2 text-center">Selected Room/s</h2>
    <?php
    $chosen_rooms_array = explode(", ", $room_name);
    foreach ($chosen_rooms_array as $room_name) {
        echo "<h2 class='text-2xl font-bold'>" . htmlspecialchars($room_name) . "</h2>";
    }
    ?>
    <input type="text" name="chosenrooms" value="<?php echo htmlspecialchars($room_name); ?>" class="border border-input p-2 rounded-lg hidden-input" readonly/>
    <div class="mt-6">
        <h3 class="text-xl font-semibold">Your booking details</h3>
        <div class="mt-2">
            <label for="check_in_date"><strong>Check In:</strong></label>
            <input type="date" name="check_in_date" value="<?php echo htmlspecialchars($check_in); ?>" readonly>
        </div>
        <div class="mt-2">
            <label for="check_out_date"><strong>Check Out:</strong></label>
            <input type="date" name="check_out_date" value="<?php echo htmlspecialchars($check_out); ?>" readonly>
        </div>
        <p>--------------------------------------------------------------------------------------</p>
        <div class="mt-2">
            <label for="names"><strong>Names:</strong></label>
            <input name="names"  id="names" value="<?php echo htmlspecialchars($names); ?>" readonly> 
        </div>
        <div class="mt-2">
            <label for="emailB"><strong>Email:</strong></label>
            <input name="emailB" id="emailB" value="<?php echo htmlspecialchars($emailB); ?>" readonly> 
        </div>
        <div class="mt-2">
            <label for="phone"><strong>Phone Number:</strong></label>
            <input name="phone" id="phone" value="<?php echo htmlspecialchars($phone); ?>" readonly>
        </div>   
        <div class="mt-2">
            <label for="country"><strong>Country:</strong></label>
            <input name="country" id="country" value="<?php echo htmlspecialchars($country); ?>" readonly>
        </div>   
        <div class="mt-2">
            <label for="special_request"><strong>Request:</strong></label>
            <input name="special_request" id="special_request" value="<?php echo htmlspecialchars($special_request); ?>" readonly>
        </div> 
        <div class="mt-2">
            <label for="arrival_time"><strong>Arrival:</strong></label>
            <input name="arrival_time" id="arrival_time" value="<?php echo htmlspecialchars($arrival_time); ?>" readonly>
            <input type="text" name="bookingFor" class="border border-input p-2 rounded-lg hidden-input" value="<?php echo htmlspecialchars($bookingFor); ?>" readonly/>
            <input type="text" name="travelingForWork" class="border border-input p-2 rounded-lg hidden-input" value="<?php echo htmlspecialchars($travelingForWork); ?>" readonly/>
        </div>
        <p>--------------------------------------------------------------------------------------</p>
        <div class="mt-2">
            <a href="booking.php" class="text-blue-500 hover:underline">Change your selection</a>
        </div>
        <h3 class="text-lg font-semibold">Parking fee:</h3> + ₱ 120 per night
        <div class="flex space-x-4 mt-2">
          <label class="flex items-center space-x-2">
            <input type="radio" name="payment-time" value="Pay at the hotel" />
            <span>Pay at the hotel</span>
          </label>
          <label class="flex items-center space-x-2">
            <input type="radio" name="payment-time" value="Pay now" />
            <span>Pay now</span>
          </label>
        </div>
        <div class="mt-2">
            <h3 class="text-xl font-semibold">Price information</h3>
            <label for="totalPrice"><strong>Total Price:</strong> ₱ </label>
            <input type="text" name="totalPrice" id="totalPrice" value="<?php echo htmlspecialchars($total_price); ?>" readonly>
            <p>Because of the discount this property provides, you will receive a lower rate.</p>
        </div>
    </div>
</section>

    <section class="bg-card p-4 border border-border rounded-lg">
      <h1 class="text-xl font-semibold">Payment</h1>
      <p>Please complete the payment to complete and secure this booking.</p>
      <div class="mt-4">
        <h3 class="text-lg font-semibold">How do you want to pay?</h3>
        <div class="flex space-x-4 mt-2">
          <label class="flex items-center space-x-2">
            <input type="radio" name="methodofPayment" value="GCash" onclick="updateImage()" required/>
            <img src="https://seeklogo.com/images/G/gcash-logo-E93133FDA5-seeklogo.com.png" width="100px" height="50px" alt="GCash" />
          </label>
          <label class="flex items-center space-x-2">
            <input type="radio" name="methodofPayment" value="Maya" onclick="updateImage()" required/>
            <img src="https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcTfvZCOOWlpBShDT3AeV6jdcdiKqNj5keRleQ&s" width="100px" height="50px" alt="Maya" />
          </label>
        </div>
        <!-- Image to display the selected payment method -->
        <div class="mt-4">
            <img id="selected_image" src="" alt="Selected Payment Method" class="hidden" />
        </div>
        <!--<div class="mt-4">
          <input type="text" placeholder="Enter your account name" class="w-full p-2 border border-input rounded-lg mb-2" />
          <input type="text" placeholder="Enter your account number" class="w-full p-2 border border-input rounded-lg mb-2" />
          <button class="bg-primary text-primary-foreground px-4 py-2 rounded-lg">Pay</button>
        </div>
      </div>-->
      <div class="mb-4">
        <label for="proofPay" class="block mb-2">Insert a proof of payment:</label>
        <input type="file" name="proofPay" id="proofPay" accept="image/*" class="w-full p-2 border border-input rounded-lg bg-input text-foreground" required>
      </div>

      <div class="mt-4">
        <p class="mt-2">Please be aware that there are no returns after this booking has been made and paid for. Maharlika’s Cradle does not retain refunds.</p>
        <div class="flex items-center mt-2">
          <input type="checkbox" id="terms" class="mr-2" required/>
          <label for="terms">I agree to the terms and conditions</label>
        </div>
        <button type="submit" onclick="return checkAvailability()" class="bg-primary text-primary-foreground px-4 py-2 rounded-lg mt-4">Book</button>
      </div>
    </form>
    <script>
            function updateImage() {
                const selectedPaymentMethod = document.querySelector('input[name="methodofPayment"]:checked');
                const selectedImage = document.getElementById('selected_image');

                if (selectedPaymentMethod) {
                    const paymentValue = selectedPaymentMethod.value;
                if (paymentValue === 'GCash') {
                    selectedImage.src = 'https://businessmaker-academy.com/cms/wp-content/uploads/2022/04/Gcash-BMA-QRcode.jpg'; // Replace with actual GCash image URL
                } else if (paymentValue === 'Maya') {
                    selectedImage.src = 'https://www.maya.ph/hubfs/Maya/Maya%20Business/Offline%20QR/Maya%20Standee-1.png'; // Replace with actual Maya image URL
                }
                    selectedImage.classList.remove('hidden'); // Show the image
                    selectedImage.style.width = '200px'; // Set the desired width
                    selectedImage.style.height = 'auto'; // Set the height to 'auto' to 
                }
            } 
    </script>
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
                    //alert("Selected room/s is available for the specified dates.");
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