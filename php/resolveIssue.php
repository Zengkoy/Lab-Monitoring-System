<?php

    if($_POST)
    {
        @include 'config.php';

        $id = $_POST["key"];

        $query = "UPDATE reports SET status='resolved' WHERE report_id='$id';";
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