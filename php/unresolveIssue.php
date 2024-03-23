<?php

    if($_POST)
    {
        @include 'config.php';

        $id = $_POST["key"];
        $pcid = $_POST["pc"];

        $query = "UPDATE reports SET status='pending' WHERE report_id='$id';";
        if(mysqli_query($conn, $query))
        {
            $query = "UPDATE computers SET status='unusable' WHERE computer_id='$pcid';";
            if(mysqli_query($conn, $query)) { echo "OK"; }
            else { echo mysqli_error($conn); }
        }
        else
        {
            echo mysqli_error($conn);
        }
    }
?>