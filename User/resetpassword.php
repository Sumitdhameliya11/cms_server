<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: *");
header("Content-Type: application/json");

include("../config/config.php"); // Include your database configuration
$data = json_decode(file_get_contents("php://input"), true);
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $user_id = $conn->real_escape_string($data['id']);
    $reset_token = $conn->real_escape_string($data['token']);
    $expires = $conn->real_escape_string($data['expires']);
    $new_password = $conn->real_escape_string($data['password']);   

    // Validate the new password
    if (empty($new_password)) {
        echo json_encode([
            "success" => false,
            "message" => "New password is required."
        ]);
        exit;
    }

    // Check if the token is valid and has not expired
    if (time() > $expires) {
        echo json_encode([
            "success" => false,
            "message" => "Token has expired."
        ]);
        exit;
    }

    // Update the user's password
    $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);
    $stmt = $conn->prepare("UPDATE users SET password = ? WHERE id = ?");
    $stmt->bind_param("si", $hashed_password, $user_id);
    $stmt->execute();

    echo json_encode([
        "success" => true,
        "message" => "Password has been successfully updated."
    ]);
} else {
    echo json_encode([
        "success" => false,
        "message" => "Invalid request method."
    ]);
}

$conn->close();
?>
