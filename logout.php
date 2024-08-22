<?php
session_start();

// Unset all of the session variables
$_SESSION = array();

// Destroy the session
session_destroy();

// Redirect to the homepage or any other page after logout
header("Location: homepage.php");
exit();
?>
