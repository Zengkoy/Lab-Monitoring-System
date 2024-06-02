<?php
    session_start();
    @include 'config.php';
    require_once __DIR__.'/vendor/autoload.php';
    include 'mail/mailConfig.php';
    include 'QRImageWithLogo.php';
    use chillerlan\QRCode\QRCode;

    if($_POST)
    {
        $userId = $_SESSION['student_id'];

        $currentPassword = $_POST['formCurrentPassword'];
        $newPassword = $_POST['formNewPassword'];
        $email = $_POST['formEmail'];
        $email = filter_var($email, FILTER_SANITIZE_EMAIL);
        $email = filter_var($email, FILTER_VALIDATE_EMAIL);

        $query = mysqli_query($conn, "SELECT password FROM students WHERE student_id = $userId;");
        $student = mysqli_fetch_assoc($query);

        $error = "";

        if(!$email)
        {
            $error .= "Invalid Email Address<br>";
        }
        else
        {
            $query = mysqli_query($conn, "SELECT email FROM students WHERE email='$email' AND student_id!=$userId;");
            $usedEmail = mysqli_fetch_assoc($query);
            if(!empty($usedEmail))
            {
                $error .= "Email already in use<br>";
            }
        }

        if(!password_verify($currentPassword, $student['password']))
        {
            $error .= "Incorrect Password<br>";
        }

        if($error != "")
        {
            echo $error;
        }
        else
        {
            $update = "";

            if(!empty($newPassword)) 
            {
                $hash = password_hash($newPassword, PASSWORD_DEFAULT);
                $update = "UPDATE students SET email = '$email', password = '$hash' WHERE student_id = '$userId';";
                $qrcode = new QRCode($options);
                $qrcode->addByteSegment($userId."|".$hash);
                $qrOutputInterface = new QRImageWithLogo($options, $qrcode->getQRMatrix());
                $out = $qrOutputInterface->dump('qrcode.png', '../images/aclc-logo.png');
            }
            else
            {
                $update = "UPDATE students SET email = '$email' WHERE student_id = '$userId';";
            }
            
            $query = mysqli_query($conn, $update);

            if($query)
            {
                $body = "<p>Dear Student,</p>";
                $body .= "<p>Your password for the ACLC Apalit Laboratories have been changed.</p>";
                $body .= "<p>Your password is '$newPassword'.</p>";
                $body .= "<p>Please use the newly generated QR Code below.</p>";
                $body .= "<p><img src='cid:my-attach' /></p>";
                $body .= "<p>Login at the <a href='https://aclcapalitlabs.000webhostapp.com/'>ACLC Apalit Labs Website</a></p>";
                $body .= "<p>Enjoy!</p>";
                $body .= "<p>ACLC Apalit Laboratories</p>";

                $mail->addAddress($email); // to email and name
                $mail->Subject = 'Password Changed - ACLC Apalit Laboratories';
                $mail->AddEmbeddedImage("qrcode.png", "my-attach", "qr-code");
                $mail->msgHTML($body);
                $mail->AltBody = 'HTML messaging not supported';

                if(!$mail->send()){
                    return "Mailer Error: " . $mail->ErrorInfo;
                }else{
                    echo "OK";
                }
            }
            else
            {
                echo mysqli_error($conn);
            }
        }
    }
?>