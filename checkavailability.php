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
    echo 'not_available';
} else {
    echo 'available';
}

$conn->close();
?>