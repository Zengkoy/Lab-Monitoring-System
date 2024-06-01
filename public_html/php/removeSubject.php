<?php
    if($_POST)
    {
        @include 'config.php';

        $subject = $_POST["subject"];

        $query = "DELETE FROM subjects WHERE subject='$subject';";
        if(mysqli_query($conn, $query))
        {
            $query = "DELETE FROM pc_assignment WHERE subject='$subject';";

            if(mysqli_query($conn, $query)) { echo "OK"; }
            else { echo mysqli_error($conn); }
        }
        else
        {
            echo mysqli_error($conn);
        }
    }
?>