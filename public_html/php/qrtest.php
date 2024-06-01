<?php
    use chillerlan\QRCode\Data\QRMatrix;
    use chillerlan\QRCode\Common\EccLevel;
    use chillerlan\QRCode\{QRCode, QROptions};
    use chillerlan\QRCode\Output\{QRGdImagePNG, QRCodeOutputException};

    require_once __DIR__.'/vendor/autoload.php';
    include 'QRImageWithLogo.php';

    $qrcode = new QRCode($options);
    $qrcode->addByteSegment('20002712400');

    $qrOutputInterface = new QRImageWithLogo($options, $qrcode->getQRMatrix());

    // dump the output, with an additional logo
    // the logo could also be supplied via the options, see the svgWithLogo example
    $out = $qrOutputInterface->dump('qrcode.png', '../images/aclc-logo.png');


    header('Content-type: image/png');

    echo $out;

    exit;