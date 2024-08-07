<?php 
    //create a connection
    $conn = new mysqli('localhost','root','','cms');

    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }
?>