<?php
    session_start();
    @include 'config.php';

    if($_POST)
    {
        $userId = $_SESSION['id'];
        $username = $_POST['formUsername'];
        $email = $_POST['formEmail'];
        $currentPassword = $_POST['formCurrentPassword'];
        $newPassowrd = $_POST['formNewPassword'];

        $query = mysqli_query($conn, "SELECT password FROM admin WHERE admin_id = $userId;");
        $admin = mysqli_fetch_assoc($query);

        if(password_verify($currentPassword, $admin['password']))
        {
            $update = "";

            if(!empty($newPassowrd)) 
            {
                $hash = password_hash($newPassowrd, PASSWORD_DEFAULT);
                $update = "UPDATE admin SET username = '$username', email = '$email', password = '$hash' WHERE admin_id = '$userId';";
            }
            else
            {
                $update = "UPDATE admin SET username = '$username', email = '$email' WHERE admin_id = '$userId';";
            }
            
            $query = mysqli_query($conn, $update);

            if($query)
            {
                echo "OK";
            }
            else
            {
                echo mysqli_error($conn);
            }
        }
        else
        {
            echo "Incorrect Password";
        }
    }
?>