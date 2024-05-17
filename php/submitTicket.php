<?php
  session_start();
  @include 'config.php';

  date_default_timezone_set("Asia/Taipei");

if($_POST) 
{
  $reporter = $_SESSION['student_id'];
  
  $form = $_POST['form'];
  $lab = $form['lab'];
  $pc = $form['pc'];
  $computer_id = $lab . $pc;
  $usability = $form['usability'];
  $issueCheck = $_POST['checks'];
  $issue = $form['issue'];
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
      $query = "INSERT INTO reports(submitted_by, computer_id, issue, status, date) VALUES('$reporter', '$computer_id', '$issueCheck$issue', 'pending', '$date');";
      if (mysqli_query($conn, $query)) { echo "OK"; }
      else { echo mysqli_error($conn); }
    }
    else { echo mysqli_error($conn); }
  }
  else { echo "This computer does not exist"; }
}

?>