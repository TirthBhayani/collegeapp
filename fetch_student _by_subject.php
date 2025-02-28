<?php
include 'db_connect.php'; // Database connection file

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $batch = $_POST['batch'];
    $subject_id = $_POST['subject_id'];

    $query = "SELECT id, name, enrollment_number FROM users WHERE batch = ? AND role = 'student'";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("s", $batch);
    $stmt->execute();
    $result = $stmt->get_result();

    $students = [];
    while ($row = $result->fetch_assoc()) {
        $students[] = $row;
    }

    echo json_encode($students);
}
?>
