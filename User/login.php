<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: *");
header("Content-Type: application/json");
include "../config/config.php";

// Decode the JSON input
$data = json_decode(file_get_contents("php://input"), true);

if ($_SERVER["REQUEST_METHOD"] == 'POST') {
    // Access email and password from the decoded JSON data
    $email = $data['email'] ?? null; // Use null coalescing to avoid undefined index notice
    $password = $data['password'] ?? null;

    // Check if email and password are provided
    if ($email && $password) {
        // Check if the email exists
        $stmt = $conn->prepare('SELECT * FROM users WHERE email = ? and isdelete = "false"');
        $stmt->bind_param('s', $email);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $user = $result->fetch_assoc();
            
            // Verify the password
            if (password_verify($password, $user['password'])) {
                // Set the cookies and session
                setcookie('user_id', $user['id'], time() + 86400, '/');
                setcookie('email', $user['email'], time() + 86400, '/');
                setcookie('role', $user['role'], time() + 86400, '/');
                
                session_start();
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['role'] = $user['role'];
                $_SESSION['email'] = $user['email'];

                // Prepare response data
                $response = [
                    "message" => "Login Successfully!",
                    "user" => [
                        "id" => $user['id'],
                        "name" => $user['name'],
                        "email" => $user['email'],
                        "role" => $user['role']
                    ]
                ];

                // Send the JSON response
                echo json_encode($response);
            } else {
                echo json_encode(["message" => "Invalid Password"]);
            }
        } else {
            echo json_encode(["message" => "Email does not exist."]);
        }
    } else {
        echo json_encode(["message" => "Email and Password are required"]);
    }
}
$conn->close();
?>
