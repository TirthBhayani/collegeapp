<?php
header("Content-Type: application/json");
header("Access-Control-Allow-Origin: *");

$conn = new mysqli("localhost", "root", "", "department");

if ($conn->connect_error) {
    die(json_encode(["error" => "Database Connection Failed"]));
}

// Get subject ID
$subject_id = isset($_GET['subject_id']) ? intval($_GET['subject_id']) : 0;

if ($subject_id === 0) {
    echo json_encode(["error" => "Invalid Subject ID"]);
    exit;
}

// Fetch students enrolled in this subject
$query = "SELECT u.enrollment_number, u.name FROM users u WHERE u.role = 'student'";
$result = $conn->query($query);

$students = [];
while ($row = $result->fetch_assoc()) {
    $students[] = $row;
}

echo json_encode($students);
$conn->close();
?>
