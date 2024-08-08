<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: *");
header("Content-Type: application/json");

include ("../config/config.php");
$data = json_decode(file_get_contents("php://input"), true);

if ($_SERVER['REQUEST_METHOD'] == 'PUT') {
    // Get the admin ID and new data from the request
    $complaintId = (int)($data['id']);
    $userId = (int)($data['userid']);
    $email = $conn->real_escape_string($data['email']);
    $mobilenumber = $conn->real_escape_string($data['mobilenumber']);
    $sutno = $conn->real_escape_string($data['sutno']);
    $category = $conn->real_escape_string($data['category']);
    $subcategory = $conn->real_escape_string($data['subcategory']);
    $priority = $conn->real_escape_string($data['priority']);
    $problem_description = $conn->real_escape_string($data['problem_description']);
    $create_date = date('Y-m-d'); // Current date in YYYY-MM-DD format
    $computer_ip = $conn->real_escape_string($_SERVER['REMOTE_ADDR']);
    $role = $conn->real_escape_string($data['role']);
    $resolve_date = date('Y-m-d');
    $resolver_name;
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
    }else{
        $sql = "update complaint set email = '$email',Mobile_number = '$mobilenumber',category = '$category',subcategory = '$subcategory',problem = '$problem_description',sutno ='$sutno',computer_ip = '$computer_ip',priority = '$priority' where id = $complaintId and isdelete = 'FALSE'";
        if($conn->query($sql)=== TRUE) {
            echo json_encode(["message"=> "Save The Change"]);
        }else{
            echo json_encode(["message"=> "Update Complaint Error"]);
        }
    }
}
$conn->close();
?>
