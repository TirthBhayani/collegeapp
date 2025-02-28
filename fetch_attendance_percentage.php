<?php
include 'db_connect.php';
header("Content-Type: application/json");
header("Access-Control-Allow-Origin: *");
if (isset($_GET['user_id']) && isset($_GET['subject'])) {
    $user_id = $_GET['user_id'];
    $subject = $_GET['subject'];

    $stmt = $conn->prepare("SELECT 
        (SUM(CASE WHEN status = 'Present' THEN 1 ELSE 0 END) * 100 / COUNT(*)) AS percentage 
        FROM attendance 
        WHERE user_id = ? AND subject = ?");
    $stmt->bind_param("is", $user_id, $subject);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($row = $result->fetch_assoc()) {
        echo json_encode(["attendance_percentage" => $row['percentage']]);
    } else {
        echo json_encode(["attendance_percentage" => 0]);
    }
} else {
    echo json_encode(["error" => "Invalid request"]);
}
?>
