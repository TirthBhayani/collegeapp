<?php
include 'db_connect.php';

header("Content-Type: application/json");
header("Access-Control-Allow-Origin: *");

$query = "SELECT DISTINCT sem FROM users";
$result = $conn->query($query);

$semesters = array();
while ($row = $result->fetch_assoc()) {
    $semesters[] = $row;
}
echo json_encode($semesters);
$conn->close();
?>
