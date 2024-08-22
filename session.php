<?php
session_start();

function isUserLoggedIn() {
    return isset($_SESSION['email']);
}

function requireLogin() {
    if (!isUserLoggedIn()) {
        header("Location: login.html");
        exit();
    }
}
?>