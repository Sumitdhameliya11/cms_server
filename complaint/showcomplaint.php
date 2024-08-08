<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: *");
header("Content-Type: application/json");

include ("../config/config.php");

if ($_SERVER['REQUEST_METHOD'] == 'GET') {
    // SQL query to retrieve all complaints
    $sql = "SELECT * FROM complaint WHERE isdelete = 'FALSE'"; // Ensure the complaint is not marked as deleted
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
