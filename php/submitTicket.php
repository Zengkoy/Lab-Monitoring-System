<?php
  session_start();
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
  $reporter = $_SESSION['student_id'];
  $lab = trim(stripslashes($_POST['lab']));
  $pc = trim(stripslashes($_POST['pc']));
  $issue = trim(stripslashes($_POST['issue']));
  $radio = trim(stripcslashes($_POST['desc-radio']));
  $usability = trim(stripslashes($_POST['usability']));
  $date = date("Y-m-d");

  $computer_id = $lab . $pc;

  $query = mysqli_query($conn,"SELECT * FROM computers WHERE computer_id = '$computer_id';");
  $row = array();
  $table = "";

  while($r = mysqli_fetch_assoc($query))
  {
      $row[] = $r;
  }

  if(!empty($row))
  {
    $query = "UPDATE computers SET status = '$usability' WHERE computer_id = '$computer_id';";
    if(mysqli_query($conn, $query))
    {
      $query = "INSERT INTO reports(submitted_by, computer_id, issue, status, date) VALUES('$reporter', '$computer_id', '$radio, $issue', 'pending', '$date');";
      if (mysqli_query($conn, $query)) { echo "OK"; }
      else { echo mysqli_error($conn); }
    }
    else { echo mysqli_error($conn); }
  }
  else { echo "This computer does not exist"; }
}

?>