<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: *");
header("Content-Type: application/json");
include "../config/config.php";

$data = json_decode(file_get_contents("php://input"), true);

if ($_SERVER["REQUEST_METHOD"] == 'POST') {
    $email = $_POST['email'];
    $password = $_POST['password'];

    //check the email is exists or not 
    $result = $conn->query('select * from users where email = "$email"');
    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();
        //verify the password
        if (password_verify($password, $user['password'])) {
            //set the cookies 
            setcookie('user_id', $user['id'], time() + 86400, '/');
            setcookie('email', $user['email'], time() + 86400, '/');
            setcookie('role', $user['role'], time() + 86400, '/');
            //start session
            session_start();
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['role'] = $user['role'];
            $_SESSION['email'] = $user['email'];
            echo json_encode(["message" => "Login Sucessfully!"]);

            //send the mail
            $name = $user['name'];
            $to = $email;
            $subject = "Login Alert message";
            $message = `Dear $name <br/> Your account login If You Not Then Reset The Password Otherwise Ingnor That Email`;
            $headers = "From: no-reply@yourdomain.com";
            if (mail($to, $subject, $message, $headers) === TRUE) {
                echo json_encode(["message" => "Login successful and email sent"]);
            } else {
                echo json_encode(["message" => "Login successful but email could not be sent."]);
            }
        } else {
            echo json_encode(["message" => "Invalied Password"]);
        }
    } else {
        echo json_encode(["message" => "Email does not exist."]);
    }
}
$conn->close();
?>