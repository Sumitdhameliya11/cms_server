<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: *");
header("Content-Type: application/json");

include ("../config/config.php");
$data = json_decode(file_get_contents("php://input"), true);

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Get the data from the request
    $userId = (int)$data['id']; // Cast ID to integer
    $currentPassword = $data['oldpassword'];
    $newPassword = $data['newpassword'];

    // Retrieve the user's current password from the database
    $sql = "SELECT password FROM users WHERE id = $userId AND isDeleted = 'false'"; // Ensure the user is not marked as deleted
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();
        
        // Verify the current password
        if (password_verify($currentPassword, $user['password'])) {
            // Hash the new password
            $hashedNewPassword = password_hash($newPassword, PASSWORD_DEFAULT);
            
            // Update the password in the database
            $updateSql = "UPDATE users SET password = '$hashedNewPassword' WHERE id = $userId";
            if ($conn->query($updateSql) === TRUE) {
                echo json_encode(["status" => "success", "message" => "Password changed successfully!"]);
            } else {
                echo json_encode([
                    "status" => "error",
                    "error_code" => $conn->errno,
                    "error_message" => $conn->error,
                    "message"=>"Changepassword Update Error"
                ]);
            }
        } else {
            echo json_encode(["status" => "error", "message" => "Current password is incorrect."]);
        }
    } else {
        echo json_encode(["status" => "error", "message" => "User not found."]);
    }
}

$conn->close();
?>
