<?php
include 'db_connect.php';

header('Content-Type: application/json');
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST, GET, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization");

// Read JSON input
$inputJSON = file_get_contents("php://input");
$input = json_decode($inputJSON, true);

// Validate input
if (!isset($input['email']) || !isset($input['password'])) {
    echo json_encode(["status" => "error", "message" => "Missing email or password"]);
    exit();
}

$email = $input['email'];
$password = $input['password'];

// Check if user exists
$sql = "SELECT user_id, name, role, enrollment_number FROM users WHERE email=? AND password=?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ss", $email, $password);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $user = $result->fetch_assoc();
    echo json_encode([
        "status" => "success",
        "user_id" => $user['user_id'],
        "name" => $user['name'],
        "role" => $user['role'],
        "enrollment_number" => $user['enrollment_number'] // ✅ Added enrollment number
    ]);
} else {
    echo json_encode(["status" => "error", "message" => "Invalid credentials"]);
}

$stmt->close();
$conn->close();
?>
