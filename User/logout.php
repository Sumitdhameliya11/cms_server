<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: Content-Type, Authorization, X-Requested-With");
header("Access-Control-Allow-Methods: PUT, GET, POST, DELETE, OPTIONS");
header("Content-Type: application/json");

session_start(); // Start the session

// Handle preflight requests
if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
    http_response_code(204); // No Content
    exit;
}

// Unset all session variables
$_SESSION = [];

// Destroy the session
session_destroy();

// Clear cookies if they were set
setcookie('user_id', '', time() - 3600, '/'); // Expire the cookie
setcookie('email', '', time() - 3600, '/');   // Expire the cookie
setcookie('role', '', time() - 3600, '/');    // Expire the cookie

// Return a JSON response indicating logout success
echo json_encode(["status" => "success", "message" => "Logged out successfully."]);
exit();
?>