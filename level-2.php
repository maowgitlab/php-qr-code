<?php
include 'phpqrcode/qrlib.php';

// Define paths as constants for better maintainability
define('IMAGE_UPLOAD_DIR', 'image/');
define('GENERATED_QR_DIR', 'generated/');

// Function to handle image uploads
function uploadImage($image)
{
    $imageName = $image['name'];
    $imageTmpName = $image['tmp_name'];
    $imageExtension = pathinfo($imageName, PATHINFO_EXTENSION);

    // Validate image extension (allow only certain file types)
    $allowedExtensions = ['jpg', 'jpeg', 'png', 'gif'];
    if (!in_array(strtolower($imageExtension), $allowedExtensions)) {
        die('Invalid file type. Only JPG, JPEG, PNG, and GIF are allowed.');
    }

    $pathImage = IMAGE_UPLOAD_DIR . time() . '.' . strtolower($imageExtension);
    if (!move_uploaded_file($imageTmpName, $pathImage)) {
        die('Failed to upload image.');
    }

    return $pathImage;
}

// Function to generate QR code
function generateQRCode($data, $fileLocation, $errorCorrectionLevel, $matrixPointSize)
{
    QRcode::png($data, $fileLocation, $errorCorrectionLevel, $matrixPointSize);
}

// Clear images or text files based on query parameters
function clearGeneratedFiles($imagePath = null, $textFilePath = null)
{
    if ($imagePath && file_exists($imagePath)) {
        unlink($imagePath);
    }
    if ($textFilePath && file_exists($textFilePath)) {
        unlink($textFilePath);
    }
    echo "<meta http-equiv='refresh' content='0; url=http://localhost:8000/level-2.php'>";
    exit;
}

// Handle requests based on URL parameters and form submission
if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    if (isset($_GET['clearImage'], $_GET['image'], $_GET['success-image'])) {
        clearGeneratedFiles($_GET['image'], $_GET['success-image']);
    }

    if (isset($_GET['clearText'], $_GET['success-text'])) {
        clearGeneratedFiles(null, $_GET['success-text']);
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['submitImage']) && isset($_FILES['image'])) {
        // Upload image and generate QR code
        $imagePath = uploadImage($_FILES['image']);
        $data = 'http://' . $_SERVER['HTTP_HOST'] . '/' . $imagePath;

        $fileLocation = GENERATED_QR_DIR . time() . '.png';
        $errorCorrectionLevel = $_POST['errorCorrectionLevel'];
        $matrixPointSize = $_POST['matrixPointSize'];

        generateQRCode($data, $fileLocation, $errorCorrectionLevel, $matrixPointSize);
        header('Location: level-2.php?success-image=' . $fileLocation . '&image=' . $imagePath);
        exit;
    }

    if (isset($_POST['submitText'], $_POST['text'])) {
        // Generate QR code from text
        $content = $_POST['text'];
        $fileLocation = GENERATED_QR_DIR . time() . '.png';
        $errorCorrectionLevel = $_POST['errorCorrectionLevel'];
        $matrixPointSize = $_POST['matrixPointSize'];

        generateQRCode($content, $fileLocation, $errorCorrectionLevel, $matrixPointSize);
        header('Location: level-2.php?success-text=' . $fileLocation);
        exit;
    }
}
?>


<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Multiple QR Code Generator</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-rbsA2VBKQhggwzxH7pPCaAqO46MgnOM80zW1RWuH61DGLwZJEdK2Kadq2F9CUG65" crossorigin="anonymous">
</head>

<body>

    <div class="container">
        <div class="row">
            <div class="col-md-8 mx-auto my-5">
                <h1>Multiple QR Code Generator</h1>
                <div class="card my-3">
                    <div class="card-header">
                        Read Notes
                    </div>
                    <div class="card-body">
                        <div class="accordion accordion-flush" id="accordionFlushExample">
                            <div class="accordion-item">
                                <h2 class="accordion-header" id="flush-headingOne">
                                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#flush-collapseOne" aria-expanded="false" aria-controls="flush-collapseOne">
                                        About Error Correction Level
                                    </button>
                                </h2>
                                <div id="flush-collapseOne" class="accordion-collapse collapse" aria-labelledby="flush-headingOne" data-bs-parent="#accordionFlushExample">
                                    <div class="accordion-body">
                                        <p class="lead">Error correction in QR codes allows data to be restored even if part of the code is damaged.</p>
                                        <p>There are four levels of error correction in QR codes:</p>
                                        <ul class="list-group">
                                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                                <strong>L (Low)</strong>
                                                <span class="badge bg-primary">7% damage tolerance</span>
                                            </li>
                                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                                <strong>M (Medium)</strong>
                                                <span class="badge bg-secondary">15% damage tolerance</span>
                                            </li>
                                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                                <strong>Q (Quartile)</strong>
                                                <span class="badge bg-warning">25% damage tolerance</span>
                                            </li>
                                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                                <strong>H (High)</strong>
                                                <span class="badge bg-danger">30% damage tolerance</span>
                                            </li>
                                        </ul>
                                        <p class="mt-3">Higher error correction levels provide more resilience, but store less data.</p>
                                    </div>
                                </div>
                            </div>
                            <div class="accordion-item">
                                <h2 class="accordion-header" id="flush-headingTwo">
                                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#flush-collapseTwo" aria-expanded="false" aria-controls="flush-collapseTwo">
                                        About Matrix Point Size (Module Size)
                                    </button>
                                </h2>
                                <div id="flush-collapseTwo" class="accordion-collapse collapse" aria-labelledby="flush-headingTwo" data-bs-parent="#accordionFlushExample">
                                    <div class="accordion-body">
                                        <p class="lead">The matrix point size defines the size of each module (the black and white squares) in the QR code.</p>
                                        <p>The size of the modules influences the resolution of the QR code:</p>
                                        <ul class="list-group">
                                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                                <strong>Small Matrix Point Size</strong>
                                                <span class="badge bg-info">Higher data capacity</span>
                                            </li>
                                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                                <strong>Large Matrix Point Size</strong>
                                                <span class="badge bg-success">Easier to scan, but less data</span>
                                            </li>
                                        </ul>
                                        <p class="mt-3">Smaller modules store more data, but require higher resolution for scanning, while larger modules are easier to read but store less information.</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <ul class="nav nav-tabs" id="myTab" role="tablist">
                    <li class="nav-item" role="presentation">
                        <button class="nav-link active" id="text-tab" data-bs-toggle="tab" data-bs-target="#text-tab-pane" type="button" role="tab" aria-controls="text-tab-pane" aria-selected="true">Text to QRCode</button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="image-tab" data-bs-toggle="tab" data-bs-target="#image-tab-pane" type="button" role="tab" aria-controls="iamage-tab-pane" aria-selected="true">Image to QRCode</button>
                    </li>
                </ul>
                <div class="tab-content" id="myTabContent">
                    <div class="tab-pane fade show active" id="text-tab-pane" role="tabpanel" aria-labelledby="text-tab" tabindex="0">
                        <form action="" method="post" class="my-3">
                            <div class="mb-3">
                                <label for="text" class="form-label">Text</label>
                                <textarea class="form-control" name="text" id="text" rows="3" required></textarea>
                            </div>
                            <div class="mb-3">
                                <label for="errorCorrectionLevel" class="form-label">Error Correction Level</label>
                                <select class="form-select" name="errorCorrectionLevel" id="errorCorrectionLevel">
                                    <option value="L" selected>Low ( recommended )</option>
                                    <option value="M">Medium</option>
                                    <option value="Q">Quartile</option>
                                    <option value="H">High</option>
                                </select>
                            </div>
                            <div class="mb-3">
                                <label for="matrixPointSize" class="form-label">Matrix Point Size <sup class="text-danger">( max 10 | default 4 )</sup></label>
                                <input type="number" class="form-control" name="matrixPointSize" id="matrixPointSize" min="1" max="10" value="4" required>
                            </div>
                            <button type="submit" name="submitText" class="btn btn-primary">Generate</button>
                        </form>
                    </div>
                    <div class="tab-pane fade" id="image-tab-pane" role="tabpanel" aria-labelledby="image-tab" tabindex="0">

                        <form action="" method="post" class="my-3" enctype="multipart/form-data">
                            <div class="mb-3">
                                <label for="image" class="form-label">Image</label>
                                <input type="file" class="form-control" name="image" id="image">
                            </div>
                            <div class="mb-3">
                                <label for="errorCorrectionLevel" class="form-label">Error Correction Level</label>
                                <select class="form-select" name="errorCorrectionLevel" id="errorCorrectionLevel">
                                    <option value="L" selected>Low ( recommended )</option>
                                    <option value="M">Medium</option>
                                    <option value="Q">Quartile</option>
                                    <option value="H">High</option>
                                </select>
                            </div>
                            <div class="mb-3">
                                <label for="matrixPointSize" class="form-label">Matrix Point Size <sup class="text-danger">( max 10 | default 4 )</sup></label>
                                <input type="number" class="form-control" name="matrixPointSize" id="matrixPointSize" min="1" max="10" value="4" required>
                            </div>
                            <button type="submit" name="submitImage" class="btn btn-primary">Generate</button>
                        </form>
                    </div>
                </div>

                <div class="row">
                    <hr>
                    <div class="col text-center">
                        <h3>Result:</h3>
                        <?php if (isset($_GET['success-image'])) : ?>
                            <?php
                            // Get and sanitize URL from GET parameter
                            $imageUrl = htmlspecialchars($_GET['success-image'], ENT_QUOTES, 'UTF-8');
                            $imagePath = htmlspecialchars($_GET['image'], ENT_QUOTES, 'UTF-8');
                            $clearUrl = htmlspecialchars($_SERVER['PHP_SELF'] . '?clearImage=true&image=' . urlencode($imagePath) . '&success-image=' . urlencode($imageUrl), ENT_QUOTES, 'UTF-8');
                            ?>
                            <img src="<?= $imageUrl ?>" alt="QR Code" class="my-2">
                            <div class="btn-group">
                                <a href="<?= $imageUrl ?>" class="btn btn-sm btn-primary" download>Download</a>
                                <a href="<?= $clearUrl ?>" class="btn btn-sm btn-danger">Clear</a>
                            </div>
                        <?php elseif (isset($_GET['success-text'])) : ?>
                            <?php
                            // Get and sanitize URL from GET parameter for text-based QR Code
                            $imageUrl = htmlspecialchars($_GET['success-text'], ENT_QUOTES, 'UTF-8');
                            $clearUrl = htmlspecialchars($_SERVER['PHP_SELF'] . '?clearText=true&success-text=' . urlencode($imageUrl), ENT_QUOTES, 'UTF-8');
                            ?>
                            <img src="<?= $imageUrl ?>" alt="QR Code" class="my-2">
                            <div class="btn-group">
                                <a href="<?= $imageUrl ?>" class="btn btn-sm btn-primary" download>Download</a>
                                <a href="<?= $clearUrl ?>" class="btn btn-sm btn-danger">Clear</a>
                            </div>
                        <?php endif; ?>
                    </div>

                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-kenU1KFdBIe4zVF0s0G1M5b4hcpxyD9F7jL+jjXkk+Q2h455rYXK/7HAuoJl+0I4" crossorigin="anonymous"></script>
</body>

</html>