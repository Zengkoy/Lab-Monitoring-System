<?php
include "config.php";

$query = mysqli_query($conn,"SELECT * FROM labs;");
$row = array();
$labOptions = "";

while($r = mysqli_fetch_assoc($query))
{
    $row[] = $r;
}

foreach($row as $r)
{
    $labNum = $r['lab'];
    $labOptions .= "<option value='$labNum'>Lab $labNum</option>";
}
echo $labOptions;