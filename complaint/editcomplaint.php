<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: *");
header("Content-Type: application/json");
header("Access-Control-Allow-Methods: PUT, GET, POST, DELETE, OPTIONS");
include ("../config/config.php");
$data = json_decode(file_get_contents("php://input"), true);

if ($_SERVER['REQUEST_METHOD'] == 'PUT') {
    // Get the admin ID and new data from the request
    $complaintId = (int)($data['id']);
    $userId = (int)($data['user_id']);
    $computer_ip = $conn->real_escape_string($_SERVER['REMOTE_ADDR']);
    $role = $conn->real_escape_string($data['role']);
    $resolve_date = date('Y-m-d');
    $status=$conn->real_escape_string($data['status']);
    $resolve_ip = $conn->real_escape_string($_SERVER['REMOTE_ADDR']);

    if($role === 'staff') {
        $sql = "select name from users where id = $userId and role = 'staff'";
        $result = $conn->query($sql);
        if($result->num_rows > 0) {
            $user = $result->fetch_assoc();
            $resolver_name = $user['name'];
            $sql = "update complaint set resolve_date = '$resolve_date',resolver_name = '$resolver_name',status = '$status',resolve_ip = '$resolve_ip' where id = $complaintId and isdelete = 'FALSE'";
            if($conn->query($sql)=== TRUE) {
                echo json_encode(["message"=>"save the change"]);
            }else{
                echo json_encode(["message"=> "Update Complaint Error"]);
            }
        }
    }
}
$conn->close();
?>
