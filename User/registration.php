<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: *");
header("Content-Type: application/json");
include ("../config/config.php");
$data = json_decode(file_get_contents("php://input"),true);
if($_SERVER['REQUEST_METHOD']=='POST'){
    $name = $conn->real_escape_string($data['name']);
    $email = $conn->real_escape_string($data['email']);
    $password = password_hash($data['password'], PASSWORD_DEFAULT);
    $role =$conn->real_escape_string($data['role']);
    //check the email in database or not 
    $result = $conn->query('select email from users where email = "$email"');

    if ($result->num_rows > 0) {
        echo json_encode(["message" => "Email already exists!"]);
    } else {
        $sql = "INSERT INTO users (name, email, password,role) VALUES ('$name', '$email', '$password','$role')";

        if ($conn->query($sql) === TRUE) {
            echo json_encode(["message" => "Registration successful!"]);
        } else {
            echo json_encode(["message" => "Error: " . $conn->error]);
        }
    }
}

$conn->close();

?>