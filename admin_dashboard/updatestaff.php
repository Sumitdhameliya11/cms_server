<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: *");
header("Content-Type: application/json");

include ("../config/config.php");
$data = json_decode(file_get_contents("php://input"), true);

if ($_SERVER['REQUEST_METHOD'] == 'PUT') {
    // Get the admin ID and new data from the request
    $staffId = (int)($data['id']);
    $name = $conn->real_escape_string($data['name']);
    $email = $conn->real_escape_string($data['email']);
    $password = $conn->real_escape_string($data['password']);
    // SQL query to update the admin details
    $sql = "UPDATE users SET name = '$name', email = '$email',password = '$password' WHERE id = '$staffId' AND role = 'staff'";

    if ($conn->query($sql) === TRUE) {
        echo json_encode(["message" => "Staff updated successfully!"]);
    } else {
        echo json_encode(["message" => "Error updating Staff","error_message" => $conn->error]);
    }
}

$conn->close();
?>
