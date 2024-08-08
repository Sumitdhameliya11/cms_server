<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: *");
header("Content-Type: application/json");
include ("../config/config.php");
$data = json_decode(file_get_contents("php://input"), true);
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = $conn->real_escape_string($data['name']);
    $email = $conn->real_escape_string($data['email']);
    $password = password_hash($data['password'], PASSWORD_DEFAULT);
    $role = $conn->real_escape_string($data['role']);
    //check the email in database or not 
    $result = $conn->query('select email from users where email = "$email"');

    if ($result->num_rows > 0) {
        echo json_encode(["message" => "Email already exists!"]);
    } else {
        $sql = "INSERT INTO users (name, email, password,role) VALUES ('$name', '$email', '$password','$role')";

        if ($conn->query($sql) === TRUE) {
            echo json_encode(["message" => "Registration successful!"]);
            //send the email with its creditional
            $password = $data['password'];
            $to = $email;
            $subject = "Registration Successful";
            $message = `Dear $name,\n\nYour registration is successful.\n\nHere are your credentials:\nEmail: $email\nPassword:$password `;
            $headers = "From: no-reply@yourdomain.com";
            if(mail($to, $subject, $message, $headers) === TRUE) {
                echo json_encode(["message" => "Registration successful and email sent!"]);
            }else{
                echo json_encode(["message" => "Registration successful but email could not be sent."]);
            }
        } else {
            echo json_encode(["message" => "Error: " . $conn->error]);
        }
    }
}

$conn->close();

?>