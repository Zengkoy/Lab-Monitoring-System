<?php

    if($_POST)
    {
        @include 'config.php';

        $id = $_POST["key"];

        $query = "UPDATE reports SET status='pending' WHERE report_id='$id';";
        if(mysqli_query($conn, $query))
        {
            header("Location: ../admin/pages/table.html");
            exit;
        }
        else
        {
            echo mysqli_error();
        }
    }
?>