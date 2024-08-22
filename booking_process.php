<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}



// Validate and sanitize input data
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Initialize variables to store form data
    $names = $room_n = $checkin_date = $checkout_date = $emailB = $country = $phone = $bookingFor = $travelingForWork = '';

    // Check if each form field is set and not empty
    if (isset($_POST['chosenrooms'])) {
        $room_n = htmlspecialchars($_POST['chosenrooms']);
    }
    if (isset($_POST['check_in_date'])) {
        $checkin_date = htmlspecialchars($_POST['check_in_date']);
    }
    if (isset($_POST['check_out_date'])) {
        $checkout_date = htmlspecialchars($_POST['check_out_date']);
    }
    if (isset($_POST['names'])) {
        $names = htmlspecialchars($_POST['names']);
    }
    if (isset($_POST['emailB'])) {
        $emailB = htmlspecialchars($_POST['emailB']);
    }
    if (isset($_POST['phone'])) {
        $phone = htmlspecialchars($_POST['phone']);
    }
    if (isset($_POST['country'])) {
        $country = htmlspecialchars($_POST['country']);
    }
    if (isset($_POST['special_request'])) {
        $special_request = htmlspecialchars($_POST['special_request']);
    }
    if (isset($_POST['arrival_time'])) {
        $arrival_time = htmlspecialchars($_POST['arrival_time']);
    }
    if (isset($_POST['bookingFor'])) {
        $bookingFor = htmlspecialchars($_POST['bookingFor']);
    }
    if (isset($_POST['travelingForWork'])) {
        $travelingForWork = htmlspecialchars($_POST['travelingForWork']);
    }

    // Database connection
    $conn = new mysqli('localhost', 'root', '', 'maharlikascradledb');

    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Prepare and bind SQL statement
    $stmt = $conn->prepare("INSERT INTO bookings (chosenrooms, check_in_date, check_out_date, names, emailB, phone, country, special_request, arrival_time, bookingFor, travelingForWork) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");

    // Check if prepare() succeeded
    if (!$stmt) {
        echo "Error preparing statement: " . $conn->error;
        exit();
    }

    // Bind parameters
    $stmt->bind_param("sssssssssss", $room_n, $checkin_date, $checkout_date, $names, $emailB, $phone, $country, $special_request, $arrival_time, $bookingFor, $travelingForWork);

    // Execute the statement
    if ($stmt->execute()) {
        echo '<script>window.location.href = "paymentbooking.php";</script>';
        exit();
    } else {
        echo "Error: " . $stmt->error;
    }

    // Close statement
    $stmt->close();
    // Close connection
    $conn->close();
}



// Check if room, checkin, and checkout parameters are set
if (isset($_POST['room'], $_POST['checkin'], $_POST['checkout'])) {
    $chosenrooms = $_POST['room'];
    $checkin = $_POST['checkin'];
    $checkout = $_POST['checkout'];

    // Prepare SQL statement to check room availability
    $sql = "SELECT * FROM bookings 
            WHERE chosenrooms = ? 
            AND ((check_in_date BETWEEN ? AND ?) OR (check_out_date BETWEEN ? AND ?))";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param("issss", $chosenrooms, $checkin, $checkout, $checkin, $checkout);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        // Room is not available for the selected dates
        echo 'not_available';
    } else {
        // Room is available
        echo 'available';
    }

    $stmt->close();
} else {
    // Handle case where parameters are not set properly
    echo 'error';
}

$conn->close(); 

// Validate and sanitize input data
/*if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Validate and sanitize input data (already handled in your existing script)

    // Extract and sanitize input parameters
    $room_id = intval($_POST['chosenrooms']);
    $checkin_date = htmlspecialchars($_POST['check_in_date']);
    $checkout_date = htmlspecialchars($_POST['check_out_date']);

    // Prepare SQL statement to check room availability
    $sql = "SELECT * FROM bookings 
            WHERE chosenrooms = ? 
            AND (
                (check_in_date < ? AND check_out_date > ?) 
                OR (check_in_date >= ? AND check_out_date <= ?)
            )";
    $stmt = $conn->prepare($sql);

    // Check if prepare() succeeded
    if (!$stmt) {
        http_response_code(500); // Internal Server Error
        echo "Error preparing statement: " . $conn->error;
        exit();
    }

    // Bind parameters and execute SQL query
    $stmt->bind_param("issss", $room_id, $checkout_date, $checkin_date, $checkin_date, $checkout_date);
    $stmt->execute();
    $result = $stmt->get_result();

    // Check if any overlapping bookings found
    if ($result->num_rows > 0) {
        // Room is not available for the selected dates
        echo 'not_available';
    } else {
        // Room is available
        echo 'available';
    }

    // Close statement and connection
    $stmt->close();
    $conn->close();
}*/

    // Validate inputs (example: check if required fields are not empty)
   if (empty($names) || empty($emailB) || empty($country) || empty($phone) || empty($checkin_date) || empty($checkout_date) || empty($room_n)) {
        // Handle empty fields as needed
        echo '<script>alert("All fields are required.");</script>';
        echo '<script>window.location.href = "booking.php";</script>';
        exit();
    }


     // Check availability for each room
   /*  for ($i = 0; $i < count($rooms); $i++) {
        $room = $conn->real_escape_string($rooms[$i]);
        $checkin = $conn->real_escape_string($checkin_dates[$i]);
        $checkout = $conn->real_escape_string($checkout_dates[$i]);

        // Check availability
        $sql_check_availability = "SELECT * FROM bookings 
                                  WHERE chosenrooms = '$room' 
                                  AND (
                                      (check_in_date < '$checkout' AND check_out_date > '$checkin') 
                                      OR (check_in_date >= '$checkin' AND check_out_date <= '$checkout')
                                  )";

        $result_availability = $conn->query($sql_check_availability);

        if ($result_availability->num_rows > 0) {
            // Room is not available for the selected dates
            $available_rooms[$i] = false;
        } else {
            // Room is available
            $available_rooms[$i] = true;
        }
    } */
?>