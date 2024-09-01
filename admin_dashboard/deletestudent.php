<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: *");
header("Access-Control-Allow-Methods: GET, POST, DELETE, PUT, OPTIONS");
header("Content-Type: application/json");

include ("../config/config.php");

if ($_SERVER['REQUEST_METHOD'] == 'DELETE') {
    // Get the student ID from the URL parameter
    $studentId = isset($_GET['id']) ? (int)$_GET['id'] : 0; // Default to 0 if not set

    if ($studentId > 0) {
        // SQL query to update the isdelete field for the student
        $sql = "UPDATE users SET isdelete = 'true' WHERE id = $studentId AND role = 'student'";

        if ($conn->query($sql) === TRUE) {
            echo json_encode(["message" => "Student deleted successfully!"]);
        } else {
            echo json_encode([
                "message" => "Error Deleting Student",
                "error_message" => $conn->error, // Error message
            ]);
        }
    } else {
        echo json_encode(["message" => "Invalid Student ID"]);
    }
}
$conn->close();
?>
