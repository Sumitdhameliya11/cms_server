<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: *");
header("Content-Type: application/json");

include ("../config/config.php");
$data = json_decode(file_get_contents("php://input"), true);

if ($_SERVER['REQUEST_METHOD'] == 'DELETE') {
    // Get the admin ID from the request
    $staffId = (int)$data['id']; // Cast ID to integer

    // SQL query to delete the admin
    $sql = "UPDATE users SET isdelete = 'true' WHERE id = $staffId AND role = 'staff'";

    if ($conn->query($sql) === TRUE) {
        echo json_encode(["message" => "Staff deleted successfully!"]);
    } else {
        echo json_encode(["message"=> "Error Delete Staff",
            "error_message" => $conn->error, // Error message
        ]);
    }
}
$conn->close();
?>
