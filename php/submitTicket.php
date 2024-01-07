<?php
  @include 'config.php';

/* function url(){
  return sprintf(
    "%s://%s",
    isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] != 'off' ? 'https' : 'http',
    $_SERVER['SERVER_NAME']
  );
} */

if($_POST) 
{
  $lab = trim(stripslashes($_POST['lab']));
  $pc = trim(stripslashes($_POST['pc']));
  $issue = trim(stripslashes($_POST['issue']));
  $date = date("Y-m-d");

  $computer_id = $lab . $pc;

  $query = "INSERT INTO reports(computer_id, issue, status, date) VALUES('$computer_id', '$issue', 'pending', '$date');";
	if (mysqli_query($conn, $query)) { echo "OK"; }
  else { echo mysqli_error(); }
}

?>