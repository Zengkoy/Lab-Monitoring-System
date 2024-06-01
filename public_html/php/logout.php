<?php
    session_start();
    /* unset($_COOKIE["user_str_session"]); */
    setcookie("user_str_session", "", time() - 3600, "/");  

    session_destroy();

    echo "OK";
?>