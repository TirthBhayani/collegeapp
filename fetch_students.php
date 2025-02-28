<?php
include 'db_connect.php';

header("Content-Type: application/json");
header("Access-Control-Allow-Origin: *");

$semester = $_GET['semester'];
$query = "SELECT user_id, name, enrollment_number FROM users WHERE sem = '$semester'";
$result = $conn->query($query);

$students = array();
while ($row = $result->fetch_assoc()) {
    $students[] = $row;
}
echo json_encode($students);
?>

$conn->close();
?>
