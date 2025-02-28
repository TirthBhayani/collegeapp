<?php
include 'db_connect.php';

header("Content-Type: application/json");
header("Access-Control-Allow-Origin: *");
$faculty_id = $_POST['faculty_id'] ?? null; // Get faculty ID from request

if (!$faculty_id) {
    echo json_encode(["error" => "Missing faculty ID"]);
    exit();
}

$sql = "SELECT s.subject_id, s.subject_name 
        FROM subjects s
        JOIN faculty_subjects fs ON s.subject_id = fs.subject_id
        WHERE fs.faculty_id = ?";
        
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
