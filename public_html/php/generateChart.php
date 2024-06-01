<?php
    @include 'config.php';

    $data = array("labels" => array(), "dates" => array(), "reports" => array());

    for ($i=6; $i>=0; $i--)
    {
        $date = date("Y-m-d", strtotime("-$i days"));
        $data["labels"][] = date("M d", strtotime("-$i days"));
        $data["dates"][] = $date;

        $query = mysqli_query($conn,"SELECT COUNT(*) AS num FROM reports WHERE DATE_FORMAT(date, '%Y-%m-%d') = '$date';");
        $reports = mysqli_fetch_row($query);

        $data["reports"][] =  $reports[0];
    }
    

    echo json_encode($data);
?>