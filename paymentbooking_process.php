<?php
// Database connection
$conn = new mysqli('localhost', 'root', '', 'maharlikascradledb');

if ($conn->connect_error) {
    die("Connection failed: ". $conn->connect_error);
}

// Check if room is available
$room_name = $_POST['chosenrooms'];
$checkin = $_POST['check_in_date'];
$checkout = $_POST['check_out_date'];

$sql = "SELECT * FROM booked 
        WHERE chosenrooms = '$room_name' 
        AND (
            ('$checkin' >= check_in_date AND '$checkin' < check_out_date) 
            OR ('$checkout' > check_in_date AND '$checkout' <= check_out_date)
            OR ('$checkin' <= check_in_date AND '$checkout' >= check_out_date)
        )";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    echo '<script>alert("Im sorry it seems someone already occupied the selected room/s and its specified dates during your payment. Please choose different dates or room/s.");</script>';
    echo '<script>window.location.href = "paymentbooking.php";</script>';
} else {
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
    $methodofPayment = $_POST['methodofPayment'];
    $proofPay = $_POST['proofPay'];

    $sql = "INSERT INTO booked (email, chosenrooms, check_in_date, check_out_date, names, emailB, country, phone, special_request, arrival_time, bookingFor, travelingForWork, methodofPayment, proofPay) 
    VALUES ('$email', '$room_name', '$checkin', '$checkout', '$names', '$emailB', '$country', '$phone', '$special_request', '$arrival_time', '$bookingFor', '$travelingForWork', '$methodofPayment', '$proofPay')";
    if ($conn->query($sql) === TRUE) {
        // After successful insertion, delete the record
        $delete_sql = "DELETE FROM bookings WHERE email='$email' AND check_in_date='$checkin' AND check_out_date='$checkout'";
        if ($conn->query($delete_sql) === TRUE) {
            echo '<script>alert("Booking Complete, Thank you for trusting us! we will wait for your arrival! -MCB");</script>';
            // Redirect to confirmation page
            echo '<script>window.location.href = "homepage.php";</script>';
            exit;
        } else {
            echo 'Error deleting record: ' . $conn->error;
        }

        
    } else {
        echo 'Error inserting record: ' . $conn->error;
    }
}

$conn->close();
?>