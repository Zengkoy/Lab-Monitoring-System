<?php
    $msg = "Hello world";

    $send = mail("i.am.justin151412@gmail.com", "Hello", $msg);

    if($send)
    {
        echo "OK";
    }
    else
    {
        echo "NOkay";
    }
?>