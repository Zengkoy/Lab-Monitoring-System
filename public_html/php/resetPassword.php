<?php
    include 'config.php';

    if($_POST)
    {
        $email = $_POST['email'];
        $password = trim(stripslashes($_POST['password']));
        $hash = password_hash($password, PASSWORD_DEFAULT);

        $query = "UPDATE admin SET password = '$hash' WHERE email = '$email';";

        if(mysqli_query($conn, $query))
        {
            $query = "DELETE FROM password_reset_temp WHERE email='$email';";
            if(mysqli_query($conn, $query))
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
            echo mysqli_error($conn);
        }
    }
?>