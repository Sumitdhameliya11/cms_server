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

    // Prepared statement to check if the email exists
    $stmt = $conn->prepare('SELECT email FROM users WHERE email = ?');
    $stmt->bind_param('s', $email);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        echo json_encode(["message" => "Email already exists!"]);
    } else {
        // Prepared statement to insert the new user
        $stmt = $conn->prepare('INSERT INTO users (name, email, password, role) VALUES (?, ?, ?, ?)');
        $stmt->bind_param('ssss', $name, $email, $password, $role);

        if ($stmt->execute()) {
            echo json_encode(["message" => "Registration successful!"]);

            // Send email with credentials
            // $plainPassword = $data['password'];
            // $to = $email;
            // $subject = "Registration Successful";
            // $message = "Dear $name,\n\nYour registration is successful.\n\nHere are your credentials:\nEmail: $email\nPassword: $plainPassword";
            // $headers = "From: sumitdhameliya002@gmail.com\r\n";

            // if (mail($to, $subject, $message, $headers)) {
            //     echo json_encode(["message" => "Registration successful and email sent!"]);
            // } else {
            //     echo json_encode(["message" => "Registration successful but email could not be sent."]);
            // }
        } else {
            echo json_encode(["message" => "Error: " . $conn->error]);
        }
    }

    $stmt->close();
}

$conn->close();
?>
