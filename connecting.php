<?php
$serverName = "DESKTOP-L4C2A99\SQLEXPRESS";  // Replace with your SQL Server instance name
$connectionOptions = array(
    "Database" => "MaharlikasCradleDB",
    "ReturnDatesAsStrings" => true,  // To handle date formats properly
);

// Establishes the connection
$conn = sqlsrv_connect($serverName, $connectionOptions);

if ($conn === false) {
    die(print_r(sqlsrv_errors(), true));
}

// Query to select data
$sql = "SELECT TOP (1000) [ID]
      ,[fullname]
      ,[email]
      ,[password]
      ,[dateOfBirth]
      ,[validIdPicture]
  FROM [MaharlikasCradleDB].[dbo].[Users]";

// Execute query
$stmt = sqlsrv_query($conn, $sql);

if ($stmt === false) {
    die(print_r(sqlsrv_errors(), true));
}

// Fetch and display results
echo "<table border='1'>";
echo "<tr><th>ID</th><th>Full Name</th><th>Email</th><th>Date of Birth</th><th>Valid ID Picture</th></tr>";

while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
    echo "<tr>";
    echo "<td>" . $row['ID'] . "</td>";
    echo "<td>" . $row['fullname'] . "</td>";
    echo "<td>" . $row['email'] . "</td>";
    echo "<td>" . $row['dateOfBirth']->format('Y-m-d') . "</td>";  // Format dateOfBirth as needed
    echo "<td><img src='data:image/jpeg;base64," . base64_encode($row['validIdPicture']) . "' width='100' height='100' /></td>";  // Display image, assuming validIdPicture is stored as binary
    echo "</tr>";
}

echo "</table>";

// Close statement and connection
sqlsrv_free_stmt($stmt);
sqlsrv_close($conn);
?>
