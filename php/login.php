<?php 
    @include "config.php";

    session_start();
    if($_POST)
    {
        $userid = $_POST["username"];
        $password = $_POST["password"];
        $cookie_value = NULL;

        if(isset($_POST["rememberMe"]))
        {
            $login_string = hash('sha512', $userid . $_SERVER['HTTP_USER_AGENT'] . time());
            $cookie_name  = 'user_str_session';  
            $cookie_value = $login_string;
            setcookie($cookie_name, $cookie_value, time() + (86400 * 1), "/");
            
            $query = "INSERT INTO login_log VALUES('$userid', '$cookie_value')";
            if(mysqli_query($conn, $query))
            {
                $_SESSION["id"] = $userid;
                echo "OK";
            }
            else
            {
                echo mysqli_error($conn);
            }
        }
    }
?>