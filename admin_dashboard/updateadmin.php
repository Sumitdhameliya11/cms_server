<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: *");
header("Content-Type: application/json");
header("Access-Control-Allow-Methods: GET, POST, DELETE, PUT, OPTIONS");
include ("../config/config.php");
$data = json_decode(file_get_contents("php://input"), true);

if ($_SERVER['REQUEST_METHOD'] == 'PUT') {
    // Get the admin ID and new data from the request
    $adminId = (int)($data['id']);
    $name = $conn->real_escape_string($data['name']);
    $email = $conn->real_escape_string($data['email']);
    $password = $conn->real_escape_string($data['password']);
    $role = $conn->real_escape_string($data['role']);
    $hashedNewPassword = password_hash($password, PASSWORD_DEFAULT);
    // SQL query to update the admin details
    $sql = "UPDATE users SET name = '$name', email = '$email',password = '$hashedNewPassword', role = '$role' WHERE id = '$adminId' and isdelete = 'false' ";

    if ($conn->query($sql) === TRUE) {
        echo json_encode(["message" => "Admin updated successfully!"]);
    } else {
        echo json_encode(["message" => "Error updating admin","error_message" => $conn->error]);
    }
}

$conn->close();
?>
