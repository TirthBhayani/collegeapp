<?php
$host = 'sql211.infinityfree.com'; // Change 'localhost' to this
$user = 'if0_38415429';
$password = 'kZUA1Dkr0dt';
$database = 'if0_38415429_db';

// Create connection
$conn = new mysqli($host, $user, $password, $database);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
