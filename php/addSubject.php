<?php
    @include 'config.php';

    $subject = $_POST["subject"];

    $query = mysqli_query($conn, "SELECT subject FROM subjects WHERE subject = '$subject';");
    $row = array();

    while($r = mysqli_fetch_assoc($query))
    {
        $row[] = $r;
    }

    if(empty($row))
    {
        $query = "INSERT INTO subjects VALUES ('$subject');";

        if(mysqli_query($conn, $query)) { echo "OK"; }
        else { echo mysqli_error(); }
    }
    else
    {
        echo "Subject already in database.";
    }
?>