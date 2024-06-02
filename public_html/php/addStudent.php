<?php
@include 'config.php';

use chillerlan\QRCode\QRCode;

require_once __DIR__.'/vendor/autoload.php';
include 'mail/mailConfig.php';
include 'QRImageWithLogo.php';

function send_mail($name, $password, $emailTo, $mail)
{
  $body = "<p>Dear $name,</p>";
  $body .= "<p>Your Account for the ACLC Apalit Laboratories has been successfully created</p>";
  $body .= "<p>Your password is '$password'</p>";
  $body .= "<p>Or you can use the QR Code below to login instead</p>";
  $body .= "<p><img src='cid:my-attach' /></p>";
  $body .= "<p>Login at the <a href='https://aclcapalitlabs.000webhostapp.com/'>ACLC Apalit Labs Website</a></p>";
  $body .= "<p>Enjoy!</p>";
  $body .= "<p>ACLC Apalit Laboratories</p>";

  $mail->addAddress($emailTo, $name); // to email and name
  $mail->Subject = 'Account Activated - ACLC Apalit Laboratories';
  $mail->AddEmbeddedImage("qrcode.png", "my-attach", "qr-code");
  $mail->msgHTML($body);
  $mail->AltBody = 'HTML messaging not supported';

  if(!$mail->send()){
    return "Mailer Error: " . $mail->ErrorInfo;
  }else{
      return true;
  }
}

$qrcode = new QRCode($options);
if ($_POST and !$_FILES) 
{
  $usn = trim(stripslashes($_POST['usn']));
  $name = trim(stripslashes($_POST['name']));
  $course = trim(stripslashes($_POST['course']));
  $email = trim(stripslashes($_POST['email']));
  $email = filter_var($email, FILTER_SANITIZE_EMAIL);
  $email = filter_var($email, FILTER_VALIDATE_EMAIL);
  $bytes = openssl_random_pseudo_bytes(4);
  $password = bin2hex($bytes);

  $hash = password_hash($password, PASSWORD_DEFAULT);

  $qrcode->addByteSegment($usn."|".$hash);
  $qrOutputInterface = new QRImageWithLogo($options, $qrcode->getQRMatrix());
  $out = $qrOutputInterface->dump('qrcode.png', '../images/aclc-logo.png');

  $qrResult = "<img src='qrcode.png' />";

  $select = "SELECT * FROM students WHERE student_id = '$usn';";
  $usedUSN = mysqli_query($conn, $select);
  
  $select = "SELECT email FROM students WHERE email='$email' AND student_id!='$usn';";
  $usedEmail = mysqli_query($conn, $select);

  if ((mysqli_num_rows($usedUSN) > 0)) 
  {
    echo "USN already Registered.";
  } 
  else if(!$email)
  {
    echo "Invalid email address.";
  }
  else if(mysqli_num_rows($usedEmail) > 0)
  {
    echo "Email already in use.";
  }
  else 
  {
    $query = "INSERT INTO students VALUES('$usn', '$hash', '$name', '$course', '$email');";
    if (mysqli_query($conn, $query)) 
    {
      $sent = send_mail($name, $password, $email, $mail);
      if($sent)
      {
        echo "OK";
      }
      else
      {
        echo $sent;
      }
    } 
    else 
    {
      echo mysqli_error($conn);
    }
  }
} 
else if ($_FILES) 
{
  $csv = array_map('str_getcsv', file($_FILES['file-0']['tmp_name']));
  $usn = "";
  $name = "";
  $course = "";
  $email = "";

  $successCount = 0;
  $failCount = 0;

  $error = "";

  foreach ($csv as $key => $row) 
  {
    if($key != 0)
    {
      $bytes = openssl_random_pseudo_bytes(4);
      $password = bin2hex($bytes);

      $hash = password_hash($password, PASSWORD_DEFAULT);
      $usn = $row[0];
      $name = $row[1];
      $course = $row[2];
      $email = $row[3];
      $email = filter_var($email, FILTER_SANITIZE_EMAIL);
      $email = filter_var($email, FILTER_VALIDATE_EMAIL);

      $qrcode->addByteSegment($usn."|".$hash);
      $qrOutputInterface = new QRImageWithLogo($options, $qrcode->getQRMatrix());
      $out = $qrOutputInterface->dump('qrcode.png', '../images/aclc-logo.png');

      $select = "SELECT * FROM students WHERE student_id = '$usn';";
      $result = mysqli_query($conn, $select);

      $select = "SELECT email FROM students WHERE email='$email' AND student_id!='$usn';";
      $usedEmail = mysqli_query($conn, $select);

      if ((mysqli_num_rows($result) > 0)) 
      {
        $error .= "USN already Registered: $usn <br>";
        $failCount++;
      }
      else if (!$email) 
      {
        $error .= "Invalid email address for usn: $usn <br>";
        $failCount++;
      }
      else if(mysqli_num_rows($usedEmail) > 0)
      {
        $error .= "Email already in use for usn: $usn";
        $failCount++;
      }
      else 
      {
        $query = "INSERT INTO students VALUES('$usn', '$hash', '$name', '$course', '$email');";
        if (!mysqli_query($conn, $query)) 
        {
          $error .= mysqli_error($conn)." for USN: $usn<br>";
          $failCount++;
        } 
        else 
        {
          $sent = send_mail($name, $password, $email, $mail);
          if($sent)
          {
            $successCount++;
          }
          else
          {
            $error .= $sent." for USN: $usn<br>";
            $failCount++;
          }
        }
      }
      $qrcode->clearSegments();
    }
  }

  if($failCount == 0)
  {
    echo "OK";
  }
  else
  {
    $error .= "Failed entries: $failCount.";
    $error .= "Successfuly Added $successCount entries.<br>";
    echo $error;
  }
}
