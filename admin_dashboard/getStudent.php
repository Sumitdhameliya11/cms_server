<?php
    header("Access-Control-Allow-Origin: *");
    header("Access-Control-Allow-Headers: *");
    header("Content-Type: application/json");
    include "../config/config.php";

    if($_SERVER["REQUEST_METHOD"]== 'GET'){
        //fetch admin details
        $result = $conn->query('select * from users where role = "student" and isdelete = "false"');
        if($result->num_rows > 0){
            $admindata = [];
            while($row = $result->fetch_assoc()){
                $studentdata[] = $row;
            }
            echo json_encode(["student_data" => $studentdata]);
        }else{
            echo json_encode(['message'=> 'No Admin Data Found']);
        }
    }

    $conn->close();
?>