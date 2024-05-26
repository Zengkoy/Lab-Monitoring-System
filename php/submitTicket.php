<?php
  session_start();

  use PHPMailer\PHPMailer\PHPMailer;
  use PHPMailer\PHPMailer\Exception;

  require $_SERVER['DOCUMENT_ROOT'] . '/lab_monitoring_system/php/mail/Exception.php';
  require $_SERVER['DOCUMENT_ROOT'] . '/lab_monitoring_system/php/mail/PHPMailer.php';
  require $_SERVER['DOCUMENT_ROOT'] . '/lab_monitoring_system/php/mail/SMTP.php';

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
      $query = "INSERT INTO reports(submitted_by, computer_id, issue, status, date) VALUES('$reporter', '$computer_id', '$issueCheck$issue', 'pending', '$date');";
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
          $body .= "<p>Issue(s): $issueCheck$issue</p>";
          $body .= "<p>----------------------------------------------------</p>";
          $body .= "<p>ACLC Apalit Lab</p>";
          $subject = "New Report - ACLC Apalit Labs";

          $mail = new PHPMailer;
          $mail->isSMTP(); 
          $mail->SMTPDebug = 0; // 0 = off (for production use) - 1 = client messages - 2 = client and server messages
          $mail->Host = "smtp.gmail.com"; // use $mail->Host = gethostbyname('smtp.gmail.com'); // if your network does not support SMTP over IPv6
          $mail->Port = 587; // TLS only
          $mail->SMTPSecure = 'tls'; // ssl is deprecated
          $mail->SMTPAuth = true;
          $mail->Username = 'aclcapalitlabs@gmail.com'; // email
          $mail->Password = 'wgsk xipl nkgq noho'; // password
          $mail->setFrom('aclcapalitlabs@gmail.com', 'ACLC Apalit Labs'); // From email and name

          foreach($admin as $r) 
          {
            $mail->addAddress($r['email']);
          }

          $mail->Subject = $subject;
          $mail->msgHTML($body); //$mail->msgHTML(file_get_contents('contents.html'), __DIR__); //Read an HTML message body from an external file, convert referenced images to embedded,
          $mail->AltBody = 'HTML messaging not supported'; // If html emails is not supported by the receiver, show this body
          // $mail->addAttachment('images/phpmailer_mini.png'); //Attach an image file
          $mail->SMTPOptions = array(
                              'ssl' => array(
                                  'verify_peer' => false,
                                  'verify_peer_name' => false,
                                  'allow_self_signed' => true
                              )
                          );
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