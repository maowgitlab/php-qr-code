<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>QR Code Generator</title>
</head>
<body>
    <h1>QR Code Generator</h1>
    <form action="" method="post">
        <label for="content">Content:</label>
        <input type="text" name="content" id="content" required>
        <label for="errorCorrectionLevel">Error Correction Level:</label>
        <select name="errorCorrectionLevel" id="errorCorrectionLevel">
            <option value="L">Low</option>
            <option value="M">Medium</option>
            <option value="Q">Quartile</option>
            <option value="H">High</option>
        </select>
        <label for="matrixPointSize">Matrix Point Size:</label>
        <input type="number" name="matrixPointSize" id="matrixPointSize" min="1" max="10" required>
        <button type="submit" name="submit">Generate</button>
    </form>
    <br>
    <div>
        <?php if(isset($_GET['success'])) : ?>
            <img src="<?= $_GET['success'] ?>" alt="QR Code">
        <?php endif; ?>
    </div>
</body>
</html>

<?php
    include 'phpqrcode/qrlib.php';
    if (isset($_POST['submit'])) {
        $fileLocation = 'generated/qrcode.png';
        $content = $_POST['content'];
        $errorCorrectionLevel = $_POST['errorCorrectionLevel'];
        $matrixPointSize = $_POST['matrixPointSize']; 
        
        QRcode::png($content, $fileLocation, $errorCorrectionLevel, $matrixPointSize);
        header('Location: index.php?success=' . $fileLocation);
        exit();
    }
?>