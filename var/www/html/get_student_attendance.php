<?php
include 'db_connect.php';

error_reporting(E_ALL);
ini_set('display_errors', 1);

header("Content-Type: application/json");

$response = [];

if ($_SERVER['REQUEST_METHOD'] == 'GET') {
    if (!isset($_GET['enrollment']) || empty($_GET['enrollment'])) {
        echo json_encode(["error" => "Enrollment number is required"]);
        exit;
    }

    $enrollment = trim($_GET['enrollment']); // Trim spaces

    // ✅ Debugging: Store received enrollment in response (instead of separate echo)
    error_log("Received Enrollment: " . $enrollment);
    $response["debug"] = "Received Enrollment: " . $enrollment;

    // Check if enrollment exists
    $checkStmt = $conn->prepare("SELECT COUNT(*) as count FROM attendance WHERE enrollment_number = ?");
    $checkStmt->bind_param("s", $enrollment);
    $checkStmt->execute();
    $checkResult = $checkStmt->get_result();
    $row = $checkResult->fetch_assoc();

    if ($row['count'] == 0) {
        echo json_encode(["error" => "Enrollment number not found in database"]);
        exit;
    }

    // Fetch attendance data
    $stmt = $conn->prepare("
        SELECT subject, 
               (SUM(CASE WHEN status = 'Present' THEN 1 ELSE 0 END) * 100 / COUNT(*)) AS percentage 
        FROM attendance 
        WHERE enrollment_number = ? 
        GROUP BY subject
    ");

    $stmt->bind_param("s", $enrollment);
    $stmt->execute();
    $result = $stmt->get_result();

    $attendanceData = [];
    while ($row = $result->fetch_assoc()) {
        $attendanceData[] = $row;
    }

    if (empty($attendanceData)) {
        $response["message"] = "No attendance records found";
    } else {
        $response["attendance"] = $attendanceData;
    }

    echo json_encode($response);
}
?>
