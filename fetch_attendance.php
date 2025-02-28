<?php
include 'db_connect.php';
header("Content-Type: application/json");
header("Access-Control-Allow-Origin: *");

// Get the parameters from the request
$subject = $_GET['subject'];
$sem = $_GET['semester'];  // semester passed from the frontend

// Step 1: Fetch all students in the given semester
$queryStudents = "SELECT enrollment_number, name FROM users WHERE sem = ?";
$stmtStudents = $conn->prepare($queryStudents);
$stmtStudents->bind_param("i", $sem);  // Bind the semester to the query
$stmtStudents->execute();
$resultStudents = $stmtStudents->get_result();

// Initialize an array to hold the attendance data for each student
$attendanceData = array();

// Step 2: Loop through all students and fetch their total and attended lectures
while ($student = $resultStudents->fetch_assoc()) {
    $enrollment_number = $student['enrollment_number'];
    $studentName = $student['name'];

    // Get the total number of lectures for the subject and semester
    // Assuming `lecture_id` is a unique identifier for each lecture, regardless of date
    $sqlTotal = "SELECT COUNT(DISTINCT date) AS total_lectures 
                 FROM attendance 
                 WHERE subject = ? AND semester = ?";
    
    $stmtTotal = $conn->prepare($sqlTotal);
    $stmtTotal->bind_param("si", $subject, $sem);
    $stmtTotal->execute();
    $resultTotal = $stmtTotal->get_result();
    $totalLectures = $resultTotal->fetch_assoc()['total_lectures'];

    // Get the number of attended lectures for this student
    $sqlAttended = "SELECT COUNT(*) AS attended_lectures 
                    FROM attendance 
                    WHERE enrollment_number = ? AND subject = ? AND semester = ? AND status = 'Present'";
    $stmtAttended = $conn->prepare($sqlAttended);
    $stmtAttended->bind_param("ssi", $enrollment_number, $subject, $sem);
    $stmtAttended->execute();
    $resultAttended = $stmtAttended->get_result();
    $attendedLectures = $resultAttended->fetch_assoc()['attended_lectures'];

    // Calculate the attendance percentage
    $attendancePercentage = ($totalLectures == 0) ? 0 : ($attendedLectures * 100.0 / $totalLectures);

    // Add the student's attendance data to the array
    $attendanceData[] = array(
        'name' => $studentName,
        'enrollment_number' => $enrollment_number,
        'total_lectures' => $totalLectures,
        'attended_lectures' => $attendedLectures,
        'attendance_percentage' => round($attendancePercentage, 2)  // Round the percentage to 2 decimal places
    );
}

// Step 3: Return the attendance data as a JSON response
echo json_encode($attendanceData);
?>
