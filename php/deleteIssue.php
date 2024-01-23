<?php

    if($_POST)
    {
        @include 'config.php';

        $id = $_POST["key"];

        $query = "DELETE FROM reports WHERE report_id='$id';";
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