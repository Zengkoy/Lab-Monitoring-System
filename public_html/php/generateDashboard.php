<?php
    @include 'config.php';
    
    $date = date("Y-m-d");

    $query = mysqli_query($conn,"SELECT COUNT(*) AS num FROM reports WHERE status = 'pending';");
    $reports = mysqli_fetch_row($query);

    $query = mysqli_query($conn,"SELECT COUNT(*) AS num FROM logs WHERE DATE_FORMAT(date, '%Y-%m-%d') = '$date';");
    $logs = mysqli_fetch_row($query);

    $query = mysqli_query($conn,"SELECT COUNT(*) AS num FROM computers WHERE status != 'usable';");
    $computers = mysqli_fetch_row($query);
    
    $data = array("reports" => $reports[0], "logs" => $logs[0], "computers" => $computers[0]);

    echo json_encode($data);
?>