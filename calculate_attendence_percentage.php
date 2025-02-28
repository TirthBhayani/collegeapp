<?php
include 'db_connect.php'; 

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $student_id = $_POST['student_id'];
    $subject_id = $_POST['subject_id'];

    // Total classes conducted for the subject
    $queryTotal = "SELECT COUNT(*) AS total_classes FROM attendance WHERE subject_id = ?";
    $stmtTotal = $conn->prepare($queryTotal);
    $stmtTotal->bind_param("i", $subject_id);
    $stmtTotal->execute();
    $resultTotal = $stmtTotal->get_result()->fetch_assoc();
    $total_classes = $resultTotal['total_classes'];

    // Total present count for the student
    $queryPresent = "SELECT COUNT(*) AS present_count FROM attendance WHERE student_id = ? AND subject_id = ? AND status = 'present'";
    $stmtPresent = $conn->prepare($queryPresent);
    $stmtPresent->bind_param("ii", $student_id, $subject_id);
    $stmtPresent->execute();
    $resultPresent = $stmtPresent->get_result()->fetch_assoc();
    $present_count = $resultPresent['present_count'];

    $attendance_percentage = $total_classes > 0 ? ($present_count / $total_classes) * 100 : 0;

    echo json_encode(["attendance_percentage" => round($attendance_percentage, 2)]);
}
?>