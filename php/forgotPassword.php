<?php
include('config.php');
include('mail/mailConfig.php');

date_default_timezone_set("Asia/Taipei");

if(isset($_POST["email"]) && (!empty($_POST["email"])))
{
    $error = "";
    $email = $_POST["email"];
    $email = filter_var($email, FILTER_SANITIZE_EMAIL);
    $email = filter_var($email, FILTER_VALIDATE_EMAIL);
    if (!$email) 
    {
        $error .="<p>Invalid email address please type a valid email address!</p>";
    }
    else
    {
        $sel_query = "SELECT * FROM admin WHERE email='$email';";
        $results = mysqli_query($conn,$sel_query);
        $row = mysqli_num_rows($results);
        if ($row == 0)
        {
            $error .= "<p>No user is registered with this email address!</p>";
        }
    }
    if($error!="")
    {
        echo $error;
    }
    else
    {
        $expFormat = mktime(
        date("H"), date("i"), date("s"), date("m") ,date("d")+1, date("Y")
        );
        $expDate = date("Y-m-d H:i:s",$expFormat);
        $key = md5(2418*2 . $email);
        $addKey = substr(md5(uniqid(rand(),1)),3,10);
        $key = $key . $addKey;
        // Insert Temp Table
        $query = "INSERT INTO `password_reset_temp` (`email`, `keycode`, `exp_date`)
        VALUES ('$email', '$key', '$expDate');";

        if(mysqli_query($conn, $query))
        {
            $output='<p>Dear user,</p>';
            $output.='<p>Please click on the following link to reset your password.</p>';
            $output.='<p>-------------------------------------------------------------</p>';
            $output.='<p><a href="https://aclcapalitlabs.000webhostapp.com/admin/pages/reset-password.php?key='.$key.'&email='.$email.'&action=reset" target="_blank">
            https://aclcapalitlabs.000webhostapp.com/admin/pages/reset-password.php?key='.$key.'&email='.$email.'&action=reset</a></p>';		
            $output.='<p>-------------------------------------------------------------</p>';
            $output.='<p>Please be sure to copy the entire link into your browser.
            The link will expire after 1 day for security reason.</p>';
            $output.='<p>If you did not request this forgotten password email, no action 
            is needed, your password will not be reset. However, you may want to log into 
            your account and change your security password as someone may have guessed it.</p>';   	
            $output.='<p>Thanks,</p>';
            $output.='<p>ACLC Apalit Labs</p>';

            $body = $output; 
            $subject = "Password Recovery - ACLC Apalit";
            $emailTo = $email;

            $mail->addAddress($emailTo, ''); // to email and name
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
        else
        {
            echo mysqli_error($conn);
        }
    }
}
?>