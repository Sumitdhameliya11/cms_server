<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: *");
header("Content-Type: application/json");
header("Access-Control-Allow-Methods: GET, POST, DELETE, PUT, OPTIONS");
include ("../config/config.php");

if ($_SERVER['REQUEST_METHOD'] == 'GET') {
    //get id form the url 
    $studentId = isset($_GET['id']) ? (int)$_GET['id'] : 0;
    // SQL query to retrieve all complaints
    $sql = "SELECT * FROM complaint WHERE user_id = $studentId and  isdelete = 'FALSE'"; // Ensure the complaint is not marked as deleted
    $result = $conn->query($sql);

    // Initialize an array to hold the complaints
    $complaints = [];

    if ($result->num_rows > 0) {
        // Fetch all complaints
        while ($row = $result->fetch_assoc()) {
            $complaints[] = $row; // Add each complaint to the array
        }
        echo json_encode(["status" => "success", "data" => $complaints]);
    } else {
        echo json_encode(["status" => "success", "message" => "No complaints found."]);
    }
} else {
    echo json_encode(["status" => "error", "message" => "Invalid request method."]);
}

$conn->close();
?>
