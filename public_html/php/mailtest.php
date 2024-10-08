<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use chillerlan\QRCode\{QRCode, QROptions};

require_once __DIR__.'/vendor/autoload.php';
require $_SERVER['DOCUMENT_ROOT'] . '/lab_monitoring_system/php/mail/Exception.php';
require $_SERVER['DOCUMENT_ROOT'] . '/lab_monitoring_system/php/mail/PHPMailer.php';
require $_SERVER['DOCUMENT_ROOT'] . '/lab_monitoring_system/php/mail/SMTP.php';
include 'QRImageWithLogo.php';

$qrcode = new QRCode($options);
$qrcode->addByteSegment('YOYOYOYOYOY');

$qrOutputInterface = new QRImageWithLogo($options, $qrcode->getQRMatrix());

$qrOutputInterface->dump('qrcode.png', '../images/aclc-logo.png');

$mail = new PHPMailer;
$mail->isSMTP(); 
$mail->SMTPDebug = 2; // 0 = off (for production use) - 1 = client messages - 2 = client and server messages
$mail->Host = "smtp.gmail.com"; // use $mail->Host = gethostbyname('smtp.gmail.com'); // if your network does not support SMTP over IPv6
$mail->Port = 587; // TLS only
$mail->SMTPSecure = 'tls'; // ssl is deprecated
$mail->SMTPAuth = true;
$mail->Username = 'aclcapalitlabs@gmail.com'; // email
$mail->Password = 'wgsk xipl nkgq noho'; // password
$mail->setFrom('aclcapalitlabs@gmail.com', 'ACLC Apalit Labs'); // From email and name
$mail->addAddress('justnabong17@gmail.com', 'Mr. Brown'); // to email and name
$mail->Subject = 'PHPMailer GMail SMTP test';
$mail->AddEmbeddedImage("qrcode.png", "my-attach", "qr-code");
$mail->msgHTML("<img src='cid:my-attach' />"); //$mail->msgHTML(file_get_contents('contents.html'), __DIR__); //Read an HTML message body from an external file, convert referenced images to embedded,
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