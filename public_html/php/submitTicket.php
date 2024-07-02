<?php
  session_start();
  @include 'config.php';
  include 'mail/mailConfig.php';

  date_default_timezone_set("Asia/Taipei");

if($_POST) 
{
  $reporter = "";
  if(isset($_POST['is-admin'])){ $reporter = "admin".$_SESSION['id']; }
  else { $reporter = $_SESSION['student_id']; }
  
  $form = $_POST['form'];
  $lab = $form['lab'];
  $pc = $form['pc'];
  $computer_id = $lab . $pc;
  $usability = $form['usability'];
  $issueCheck = $_POST['checks'];
  $hardwareCheck = "";
  $hardwareName = "";
  $softwareCheck = "";
  $softwareName = "";
  $issue = $form['issue'];
  $type = "";
  $date = date("Y-m-d");

  if($issueCheck != "")
  {
    $type = "hardware";
  }
  
  if(isset($form['hardwareCheck']))
  {
    $type = "hardware";
    $hardwareCheck = $form['hardwareCheck'];
    $hardwareName = $form['hardwareName'].", ";
  }

  if(isset($form['softwareCheck']))
  {
    if($type != ""){ $type = "both"; }
    else { $type = "software"; }
    $softwareCheck = $form['softwareCheck'];
    $softwareName = $form['softwareName'].", ";
  }

  $computer_id = $lab . $pc;

  $query = mysqli_query($conn,"SELECT * FROM computers WHERE computer_id = '$computer_id';");
  $row = array();

  while($r = mysqli_fetch_assoc($query))
  {
      $row[] = $r;
  }

  if(empty($row))
  {
    echo "This computer does not exist";
  }
  else 
  { 
    $query = "UPDATE computers SET status = '$usability' WHERE computer_id = '$computer_id';";
    if(!mysqli_query($conn, $query))
    {
      echo mysqli_error($conn);
    }
    else 
    { 
      $query = "INSERT INTO reports(submitted_by, computer_id, issue, prob_type, status, date) 
      VALUES('$reporter', '$computer_id', '$issueCheck$softwareCheck$softwareName$hardwareCheck$hardwareName$issue', 
      '$type', 'pending', '$date');";
      if (!mysqli_query($conn, $query)) { echo mysqli_error($conn); }
      else 
      {
        $admin = array();
        $query = mysqli_query($conn, "SELECT * FROM admin WHERE email != '' AND notification = 'on';");
        while($r = mysqli_fetch_assoc($query))
        {
          $admin[] = $r;
        }

        if(empty($admin))
        {
          echo "OK";
        }
        else
        {
          $body = "<p>Dear Admin,</p>";
          $body .= "<p>A new report has been submitted to the System</p>";
          $body .= "<p>----------------------------------------------------</p>";
          $body .= "<p>Submitted By: $reporter</p>";
          $body .= "<p>Laboratory: $lab</p>";
          $body .= "<p>PC Number: $pc</p>";
          $body .= "<p>Issue(s): $issueCheck$hardwareCheck$hardwareName$softwareCheck$softwareName</p>";
          $body .= "<p>Description: $issue</p>";
          $body .= "<p>----------------------------------------------------</p>";
          $body .= "<p>ACLC Apalit Lab</p>";
          $subject = "New Report - ACLC Apalit Labs";

          foreach($admin as $r) 
          {
            $mail->addAddress($r['email']);
          }

          $mail->Subject = $subject;
          $mail->msgHTML($body); //$mail->msgHTML(file_get_contents('contents.html'), __DIR__); //Read an HTML message body from an external file, convert referenced images to embedded,
          $mail->AltBody = 'HTML messaging not supported'; // If html emails is not supported by the receiver, show this body
          // $mail->addAttachment('images/phpmailer_mini.png'); //Attach an image file
          
          if(!$mail->send()){
            echo "Mailer Error: " . $mail->ErrorInfo;
          }else{
            echo "OK";
          }
        }
      }
    }
  }
}

?>