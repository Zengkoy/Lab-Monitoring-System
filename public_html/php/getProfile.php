<?php
    session_start();

    @include 'config.php';

    $userId = $_SESSION['id'];
    $data = array("username" => "", "email" => "", "notif" => "");

    $query = mysqli_query($conn,"SELECT * FROM admin WHERE admin_id = $userId;");
    $admin = mysqli_fetch_assoc($query);

    $data["username"] =  $admin['username'];
    $data["email"] = $admin['email'];
    $data["notif"] = $admin['notification'];
    

    echo json_encode($data);
?>