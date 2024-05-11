<?php
    session_start();

    @include 'config.php';

    $id = $_SESSION['id'];

    $query = "DELETE FROM admin WHERE admin_id='$id';";
    if(mysqli_query($conn, $query))
    {
        setcookie("user_str_session", "", time() - 3600, "/");  
        session_destroy();
        echo "OK";
    }
    else
    {
        echo mysqli_error($conn);
    }
    
?>