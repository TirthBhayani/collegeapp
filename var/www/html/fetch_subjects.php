<?php
header("Content-Type: application/json");
header("Access-Control-Allow-Origin: *");
include 'db_connect.php';

$semester = $_GET['semester'];
$query = "SELECT subject_name FROM subjects WHERE semester = '$semester'";
$result = $conn->query($query);

$subjects = array();
while ($row = $result->fetch_assoc()) {
    $subjects[] = $row;
}
echo json_encode($subjects);
?>
