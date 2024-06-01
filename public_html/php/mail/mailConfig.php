<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
require $_SERVER['DOCUMENT_ROOT'] . '/lab_monitoring_system/public_html/php/mail/Exception.php';
require $_SERVER['DOCUMENT_ROOT'] . '/lab_monitoring_system/public_html/php/mail/PHPMailer.php';
require $_SERVER['DOCUMENT_ROOT'] . '/lab_monitoring_system/public_html/php/mail/SMTP.php';

$mail = new PHPMailer;
$mail->isSMTP(); 
$mail->SMTPDebug = 0; // 0 = off (for production use) - 1 = client messages - 2 = client and server messages
$mail->Host = "smtp.gmail.com"; // use $mail->Host = gethostbyname('smtp.gmail.com'); // if your network does not support SMTP over IPv6
$mail->Port = 587; // TLS only
$mail->SMTPSecure = 'tls'; // ssl is deprecated
$mail->SMTPAuth = true;
$mail->Username = 'aclcapalitlabs@gmail.com'; // email
$mail->Password = 'wgsk xipl nkgq noho'; // password
$mail->setFrom('aclcapalitlabs@gmail.com', 'ACLC Apalit Labs');
$mail->SMTPOptions = array(
                    'ssl' => array(
                        'verify_peer' => false,
                        'verify_peer_name' => false,
                        'allow_self_signed' => true
                    )
                );