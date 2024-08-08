<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: *");
header("Content-Type: application/json");

include ("../config/config.php");
$data = json_decode(file_get_contents("php://input"), true);

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Get the data from the request
    $email = $conn->real_escape_string($data['email']);
    $mobilenumber = $conn->real_escape_string($data['mobilenumber']);
    $sutno = $conn->real_escape_string($data['sutno']);
    $category = $conn->real_escape_string($data['category']);
    $subcategory = $conn->real_escape_string($data['subcategory']);
    $priority = $conn->real_escape_string($data['priority']);
    $problem_description = $conn->real_escape_string($data['problem_description']);
    $create_date = date('Y-m-d'); // Current date in YYYY-MM-DD format
    $computer_ip = $conn->real_escape_string($_SERVER['REMOTE_ADDR']); // Get the user's IP address

    // Mobile number validation: Check if it is exactly 10 digits
    if (!preg_match('/^[0-9]{10}$/', $mobile_number)) {
        echo json_encode(["status" => "error", "message" => "Invalid mobile number. It must be exactly 10 digits."]);
        exit; // Stop further execution
    }
    // SQL query to insert the complaint
    $sql = "INSERT INTO complaints (email,Mobile_number, sutno, category, subcategory, priority, problem, create_date, computer_ip) 
            VALUES ('$email','$mobilenumber','$sutno', '$category', '$subcategory', '$priority', '$problem_description', '$create_date', '$computer_ip')";

    if ($conn->query($sql) === TRUE) {
        echo json_encode(["status" => "success", "message" => "Complaint submitted successfully!"]);
    } else {
        echo json_encode([
            "status" => "error",
            "error_code" => $conn->errno,
            "error_message" => $conn->error,
        ]);
    }
}

$conn->close();
?>
