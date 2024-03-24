<?php
    if($_POST)
    {
        @include 'config.php';

        $pcid = $_POST["pc"];

        $query = "DELETE FROM computers WHERE computer_id='$pcid';";
        if(mysqli_query($conn, $query))
        {
            $query = "DELETE FROM reports WHERE computer_id='$pcid';";

            if(mysqli_query($conn, $query)) { echo "OK"; }
            else { echo mysqli_error($conn); }
        }
        else
        {
            echo mysqli_error($conn);
        }
    }
?>