<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: *");
header("Content-Type: application/json");

include ("../config/config.php");
$data = json_decode(file_get_contents("php://input"), true);

if ($_SERVER['REQUEST_METHOD'] == 'PUT') {
    // Get the admin ID and new data from the request
    $studentId = (int)($data['id']);
    $name = $conn->real_escape_string($data['name']);
    $email = $conn->real_escape_string($data['email']);
    $password = $conn->real_escape_string($data['password']);
    // SQL query to update the admin details
    $sql = "UPDATE users SET name = '$name', email = '$email',password = '$password' WHERE id = '$studentId' AND role = 'student'";

    if ($conn->query($sql) === TRUE) {
        echo json_encode(["message" => "Student updated successfully!"]);
    } else {
        echo json_encode(["message" => "Error updating Student","error_message" => $conn->error]);
    }
}

$conn->close();
?>
