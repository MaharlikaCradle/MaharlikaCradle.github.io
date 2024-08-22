<?php
// Database connection
$conn = new mysqli('localhost', 'root', '', 'maharlikascradledb');

if ($conn->connect_error) {
    die("Connection failed: ". $conn->connect_error);
}

// Check if room is available
$r_name = $_POST['chosenrooms'];
$checkin = $_POST['check_in_date'];
$checkout = $_POST['check_out_date'];

$sql = "SELECT * FROM booked 
        WHERE chosenrooms = '$r_name' 
        AND (
            ('$checkin' >= check_in_date AND '$checkin' < check_out_date) 
            OR ('$checkout' > check_in_date AND '$checkout' <= check_out_date)
            OR ('$checkin' <= check_in_date AND '$checkout' >= check_out_date)
        )";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    echo '<script>alert("Selected room/s is NOT available for the specified dates. Please choose different dates or room/s.");</script>';
    echo '<script>window.location.href = "booking.php";</script>';
} /*else if ($result->num_rows > 1) {
   // echo 'available';
}*/
else {
    // Insert booking data into database
    $names = $_POST['names'];
    $email = $_POST['email'];
    $emailB = $_POST['emailB'];
    $country = $_POST['country'];
    $phone = $_POST['phone'];
    $special_request = $_POST['special_request'];
    $arrival_time = $_POST['arrival_time'];
    $bookingFor = $_POST['bookingFor'];
    $travelingForWork = $_POST['travelingForWork'];

    $sql = "INSERT INTO bookings (email, chosenrooms, check_in_date, check_out_date, names, emailB, country, phone, special_request, arrival_time, bookingFor, travelingForWork) VALUES ('$email', '$r_name', '$checkin', '$checkout', '$names', '$emailB', '$country', '$phone', '$special_request', '$arrival_time', '$bookingFor', '$travelingForWork')";
    $conn->query($sql);

    // Redirect to confirmation page
    header("Location: paymentbooking.php");
    exit;
}

$conn->close();
?>