<?php
include 'db_connect.php';

header("Content-Type: application/json");
header("Access-Control-Allow-Origin: *");

if (isset($_GET['subject']) && isset($_GET['semester'])) {
    $subject = $_GET['subject'];
    $semester = $_GET['semester'];

    $stmt = $conn->prepare("
        SELECT u.user_id AS user_id, u.name, u.enrollment_number,
               (SUM(CASE WHEN a.status = 'Present' THEN 1 ELSE 0 END) * 100 / COUNT(*)) AS percentage
        FROM users u
        JOIN attendance a ON u.user_id = a.user_id
        WHERE a.subject = ? AND a.semester = ?
        GROUP BY u.user_id, u.name, u.enrollment_number
        ORDER BY u.enrollment_number ASC
    ");

    if (!$stmt) {
        echo json_encode(["error" => "SQL error: " . $conn->error]);
        exit;
    }

    $stmt->bind_param("ss", $subject, $semester);
    $stmt->execute();
    $result = $stmt->get_result();

    $students = [];
    while ($row = $result->fetch_assoc()) {
        $students[] = $row;
    }

    if (empty($students)) {
        echo json_encode(["error" => "No attendance records found"]);
    } else {
        echo json_encode($students);
    }
} else {
    echo json_encode(["error" => "Invalid request: Missing parameters"]);
}
?>
