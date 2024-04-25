<?php
    @include 'config.php';

    $usn = $_POST["usn"];

    $query = mysqli_query($conn, "SELECT computer_id FROM computers WHERE computer_id = '$computer_id';");
    $row = array();

    while($r = mysqli_fetch_assoc($query))
    {
        $row[] = $r;
    }

    if(empty($row))
    {
        $query = "INSERT INTO computers VALUES ('$computer_id', '$lab', 'usable', '');";

        if(mysqli_query($conn, $query)) { echo "OK"; }
        else { echo mysqli_error(); }
    }
    else
    {
        echo "Computer already exists.";
    }
?>