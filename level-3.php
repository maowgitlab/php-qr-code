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
                        <div class="row">
                            <div class="col-12 col-md-8">
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
                                        <label for="matrixPointSize" class="form-label">Matrix Point Size</label>
                                        <input type="number" class="form-control" name="matrixPointSize" id="matrixPointSize" min="1" max="10" value="4" required>
                                    </div>
                                    <button type="submit" name="submitText" class="btn btn-primary">Generate</button>
                                </form>
                            </div>
                            <div class="col-12 col-md-4 text-center my-md-3">
                                <h3>Result:</h3>
                                <?php if (isset($_GET['success-text'])) : ?>
                                    <img src="<?= $_GET['success-text'] ?>" alt="QR Code" class="my-2">
                                    <div class="d-flex justify-content-center">
                                        <div class="btn-group">
                                            <a href="<?= $_GET['success-text'] ?>" class="btn btn-sm btn-primary" download>Download</a>
                                            <a href="<?= $_SERVER['PHP_SELF'] ?>?clearText=true&success-text=<?= $_GET['success-text'] ?>" class="btn btn-sm btn-danger">Clear</a>
                                        </div>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                    <div class="tab-pane fade" id="image-tab-pane" role="tabpanel" aria-labelledby="image-tab" tabindex="0">
                        <div class="row">
                            <div class="col-12 col-md-8">
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
                                        <label for="matrixPointSize" class="form-label">Matrix Point Size</label>
                                        <input type="number" class="form-control" name="matrixPointSize" id="matrixPointSize" min="1" max="10" value="4" required>
                                    </div>
                                    <button type="submit" name="submitImage" class="btn btn-primary">Generate</button>
                                </form>
                            </div>
                            <div class="col-12 col-md-4 text-center my-md-3">
                                <h3>Result:</h3>
                                <?php if (isset($_GET['success-image'])) : ?>
                                    <img src="<?= $_GET['success-image'] ?>" alt="QR Code" class="my-2">
                                    <div class="d-flex justify-content-center">
                                        <div class="btn-group">
                                            <a href="<?= $_GET['image'] ?>" class="btn btn-sm btn-primary" download>Download</a>
                                            <a href="<?= $_SERVER['PHP_SELF'] ?>?clearImage=true&image=<?= $_GET['image'] ?>&success-image=<?= $_GET['success-image'] ?>" class="btn btn-sm btn-danger">Clear</a>
                                        </div>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-kenU1KFdBIe4zVF0s0G1M5b4hcpxyD9F7jL+jjXkk+Q2h455rYXK/7HAuoJl+0I4" crossorigin="anonymous"></script>
</body>

</html>

<?php
include 'phpqrcode/qrlib.php';

if (isset($_GET['clearImage']) && isset($_GET['image']) && isset($_GET['success-image'])) {
    unlink($_GET['image']);
    unlink($_GET['success-image']);
    echo "<meta http-equiv='refresh' content='0; url=http://localhost:8000/level-3.php'>";
}

if (isset($_GET['clearText']) && isset($_GET['success-text'])) {
    unlink($_GET['success-text']);
    echo "<meta http-equiv='refresh' content='0; url=http://localhost:8000/level-3.php'>";
}

if (isset($_POST['submitImage'])) {
    $image = $_FILES['image']['tmp_name'];
    $imageName = $_FILES['image']['name'];
    $imageExtension = pathinfo($imageName, PATHINFO_EXTENSION);
    $pathImage = 'image/' . time() . '.' . strtolower($imageExtension);
    if (!move_uploaded_file($image, $pathImage)) {
        die('Failed to upload image');
    }
    $data = 'http://' . $_SERVER['HTTP_HOST'] . '/' . $pathImage;

    $fileLocation = 'generated/qrcode-image.png';
    $errorCorrectionLevel = $_POST['errorCorrectionLevel'];
    $matrixPointSize = $_POST['matrixPointSize'];

    QRcode::png($data, $fileLocation, $errorCorrectionLevel, $matrixPointSize);
    echo "<meta http-equiv='refresh' content='0; url=http://localhost:8000/level-3.php?success-image=$fileLocation&image=$pathImage'>";
}

if (isset($_POST['submitText'])) {
    $fileLocation = 'generated/qrcode.png';
    $content = $_POST['text'];
    $errorCorrectionLevel = $_POST['errorCorrectionLevel'];
    $matrixPointSize = $_POST['matrixPointSize'];

    QRcode::png($content, $fileLocation, $errorCorrectionLevel, $matrixPointSize);
    echo "<meta http-equiv='refresh' content='0; url=http://localhost:8000/level-3.php?success-text=$fileLocation'>";
}
