<?php
    @include "config.php";

    session_start();
    if(!isset($_SESSION['student_id'])) 
    {
        echo "no user";
    }
    else {echo "OK";}
?>