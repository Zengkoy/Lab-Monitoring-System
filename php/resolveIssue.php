<?php

    if($_POST)
    {
        @include 'config.php';

        $id = $_POST["key"];
        $pcid = $_POST["pc"];

        $query = "UPDATE reports SET status='resolved' WHERE report_id='$id';";
        if(mysqli_query($conn, $query))
        {
            $query = mysqli_query($conn,"SELECT computer_id FROM reports WHERE computer_id='$pcid' AND status='pending';");
            $row = array();

            while($r = mysqli_fetch_assoc($query))
            {
                $row[] = $r;
            }

            if(empty($row))
            {
                $query = "UPDATE computers SET status='usable' WHERE computer_id='$pcid';";
                if(mysqli_query($conn, $query)) 
                { 
                    echo "OK";
                    /* header("Location: ../admin/pages/table.html");
                    exit; */
                }
                else { echo mysqli_error($conn); }
            }
            else {echo "OK";}
        }
        else
        {
            echo mysqli_error($conn);
        }
    }
?>