<?php 
    include 'phpqrcode/qrlib.php';

    // Level 1
    $fileLocation = 'generated/qrcode.png';
    $content = 'Hello World';
    $errorCorrectionLevel = 'L';
    $matrixPointSize = 4; 
    
    QRcode::png($content, $fileLocation, $errorCorrectionLevel, $matrixPointSize);
    QRcode::png($content);
