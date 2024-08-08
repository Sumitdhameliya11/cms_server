<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: *");
header("Content-Type: application/json");

include("../config/config.php"); // Include your database configuration
$data = json_decode(file_get_contents("php://input"), true);
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = $conn->real_escape_string($data['email']);

    if (empty($email)) {
        echo json_encode([
            "success" => false,
            "message" => "Email is required.",
        ]);
        exit;
    }

    // Check if the email exists in the database
    $result = $conn->query("SELECT * FROM users WHERE email = '$email' AND is_deleted = 'FALSE'");
    
    if ($result->num_rows === 0) {
        echo json_encode([
            "success" => false,
            "message" => "Invalid email address.",
        ]);
        exit;
    }

    $user = $result->fetch_assoc();
    $user_id = $user['id'];

    // Generate a unique reset token
    $reset_token = bin2hex(random_bytes(16));
    $expires = time() + 3600; // Token valid for 1 hour

    // Create password reset URL
    $resetPasswordUrl = getenv("BASE_URL") . "/reset-password.php?id=$user_id&token=$reset_token&expires=$expires";
    $message = "Please click on the following link to reset your password:\n\n$resetPasswordUrl";

    // Send email
    if (mail($email, "Reset Password", $message)) {
        echo json_encode([
            "success" => true,
            "message" => "Reset password link sent successfully."
        ]);
    } else {
        echo json_encode([
            "success" => false,
            "message" => "Error sending reset password email."
        ]);
    }
} else {
    echo json_encode([
        "success" => false,
        "message" => "Invalid request method."
    ]);
}

$conn->close();
?>
