<?php
include 'db_connect.php';
header("Content-Type: application/json");
header("Access-Control-Allow-Origin: *");
if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    echo json_encode(["error" => "Invalid request method"]);
    exit();
}

$faculty_id = $_POST['faculty_id'] ?? null;

if (!$faculty_id) {
    echo json_encode(["error" => "Missing faculty ID"]);
    exit();
}

$sql = "SELECT subjects.subject_id, subjects.subject_name 
        FROM faculty_subjects 
        JOIN subjects ON faculty_subjects.subject_id = subjects.subject_id 
        WHERE faculty_subjects.faculty_id = ?";

$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $faculty_id);
$stmt->execute();
$result = $stmt->get_result();

$subjects = [];
while ($row = $result->fetch_assoc()) {
    $subjects[] = $row;
}

echo json_encode($subjects);
$stmt->close();
$conn->close();
?>
