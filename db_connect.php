<?php
$servername = "localhost"; // Change to your server name
$username = "root";        // Default username for phpMyAdmin
$password = "";            // Default is blank, change if your server has a password
$database = "TravelAgencyDbFinal"; // Name of your database

// Create connection
$conn = new mysqli($servername, $username, $password, $database);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
