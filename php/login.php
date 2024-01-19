<?php 
    @include "config.php";

    if($_POST)
    {
        $userid = $_POST["username"];
        $password = $_POST["password"];

        if(isset($_POST["rememberMe"]))
        {
            $login_string = hash('sha512', $userid . $_SERVER['HTTP_USER_AGENT'] . time());
            $cookie_name  = 'user_str_session';  
            $cookie_value = $login_string;
            setcookie($cookie_name, $cookie_value, time() + (86400 * 1), "/");
        }

        echo "OK";
    }
?>