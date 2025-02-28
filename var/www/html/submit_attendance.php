<?php
include 'db_connect.php'; // Database connection

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $data = json_decode($_POST['attendance_data'], true);
    
    foreach ($data as $attendance) {
        $user_id = $attendance['user_id'];
        $enrollment = $attendance['enrollment_number'];
        $subject = $attendance['subject']; // Store subject-wise
        $semester = $attendance['semester'];
        $faculty_id = $attendance['faculty_id'];
        $status = $attendance['status'];

        // Insert attendance record for the subject
        $stmt = $conn->prepare("INSERT INTO attendance (user_id, enrollment_number, subject, semester, faculty_id, status, date) VALUES (?, ?, ?, ?, ?, ?, NOW())");
        $stmt->bind_param("isssis", $user_id, $enrollment, $subject, $semester, $faculty_id, $status);
        $stmt->execute();
    }

    // Calculate subject-wise attendance percentage
    $stmt = $conn->prepare("UPDATE users u 
        JOIN (
            SELECT user_id, subject, 
                   (SUM(CASE WHEN status = 'Present' THEN 1 ELSE 0 END) * 100 / COUNT(*)) AS percentage 
            FROM attendance 
            GROUP BY user_id, subject
        ) AS attendance_data 
        ON u.id = attendance_data.user_id 
        SET u.attendance_percentage = attendance_data.percentage 
        WHERE u.id = attendance_data.user_id AND attendance_data.subject = ?");
    $stmt->bind_param("s", $subject);
    $stmt->execute();

    echo json_encode(["message" => "Attendance submitted successfully"]);
} else {
    echo json_encode(["error" => "Invalid request"]);
}
?>
