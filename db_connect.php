<?php
$host = 'sql12.freesqldatabase.com'; // Change 'localhost' to this
$user = 'sql12765247';
$password = 'Z2RUZPhaTr';
$database = 'sql12765247';

// Create connection
$conn = new mysqli($host, $user, $password, $database);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
