<?php
    use chillerlan\QRCode\{QRCode, QROptions};

    require_once __DIR__.'/vendor/autoload.php';
    
    $options = new QROptions;
$options->cachefile = "../php/qrcode.png";
$qrCode = (new QRCode)->render("20002712400", "../php/qrcode.png");
?>