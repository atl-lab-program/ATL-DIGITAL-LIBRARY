<?php

require __DIR__ . '/vendor/autoload.php';

use PhpOffice\PhpSpreadsheet\IOFactory;


// --------------------------------------------------
// 1. Check that the request is a POST request
// --------------------------------------------------

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    die('Invalid request.');
}


// --------------------------------------------------
// 2. Check that both files were uploaded
// --------------------------------------------------

if (!isset($_FILES['template'])) {
    die('Certificate template was not uploaded.');
}

if (!isset($_FILES['spreadsheet'])) {
    die('Excel spreadsheet was not uploaded.');
}


// --------------------------------------------------
// 3. Check for upload errors
// --------------------------------------------------

if ($_FILES['template']['error'] !== UPLOAD_ERR_OK) {
    die('There was an error uploading the certificate template.');
}

if ($_FILES['spreadsheet']['error'] !== UPLOAD_ERR_OK) {
    die('There was an error uploading the Excel spreadsheet.');
}


// --------------------------------------------------
// 4. Create upload directory if it doesn't exist
// --------------------------------------------------

$uploadDirectory = __DIR__ . '/uploads';

if (!is_dir($uploadDirectory)) {
    mkdir($uploadDirectory, 0777, true);
}


// --------------------------------------------------
// 5. Validate the certificate image
// --------------------------------------------------

$templateOriginalName = $_FILES['template']['name'];

$templateExtension = strtolower(
    pathinfo($templateOriginalName, PATHINFO_EXTENSION)
);

$allowedImageExtensions = [
    'jpg',
    'jpeg',
    'png'
];

if (!in_array($templateExtension, $allowedImageExtensions, true)) {
    die('Invalid certificate image. Please upload JPG or PNG.');
}


// --------------------------------------------------
// 6. Validate the Excel file
// --------------------------------------------------

$spreadsheetOriginalName = $_FILES['spreadsheet']['name'];

$spreadsheetExtension = strtolower(
    pathinfo($spreadsheetOriginalName, PATHINFO_EXTENSION)
);

if ($spreadsheetExtension !== 'xlsx') {
    die('Invalid spreadsheet. Please upload an .xlsx Excel file.');
}


// --------------------------------------------------
// 7. Create safe unique filenames
// --------------------------------------------------

$templateFilename =
    'template_' . time() . '_' . bin2hex(random_bytes(4))
    . '.' . $templateExtension;

$spreadsheetFilename =
    'spreadsheet_' . time() . '_' . bin2hex(random_bytes(4))
    . '.xlsx';


// --------------------------------------------------
// 8. Create complete file paths
// --------------------------------------------------

$templatePath =
    $uploadDirectory . '/' . $templateFilename;

$spreadsheetPath =
    $uploadDirectory . '/' . $spreadsheetFilename;


// --------------------------------------------------
// 9. Move uploaded files
// --------------------------------------------------

if (!move_uploaded_file(
    $_FILES['template']['tmp_name'],
    $templatePath
)) {
    die('Could not save the certificate template.');
}

if (!move_uploaded_file(
    $_FILES['spreadsheet']['tmp_name'],
    $spreadsheetPath
)) {
    die('Could not save the Excel spreadsheet.');
}


// --------------------------------------------------
// 10. Read the Excel spreadsheet
// --------------------------------------------------

try {

    $spreadsheet = IOFactory::load($spreadsheetPath);

    $worksheet = $spreadsheet->getActiveSheet();

    $rows = $worksheet->toArray(
        null,
        true,
        true,
        true
    );

} catch (Exception $e) {

    die(
        'Could not read the Excel file: '
        . htmlspecialchars($e->getMessage())
    );
}


// --------------------------------------------------
// 11. Make sure the spreadsheet isn't empty
// --------------------------------------------------

if (empty($rows)) {
    die('The Excel spreadsheet appears to be empty.');
}


// --------------------------------------------------
// 12. Get the first row as column headers
// --------------------------------------------------

$headers = array_shift($rows);

?>

<!DOCTYPE html>

<html lang="en">

<head>

    <meta charset="UTF-8">

    <title>Excel Data</title>

    <style>

        body {
            font-family: Arial, sans-serif;
            background: #f4f6f8;
            padding: 40px;
        }

        .container {
            max-width: 1000px;
            margin: auto;
            background: white;
            padding: 35px;
            border-radius: 12px;
            box-shadow: 0 5px 20px rgba(0,0,0,0.1);
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 25px;
        }

        th,
        td {
            border: 1px solid #ddd;
            padding: 12px;
            text-align: left;
        }

        th {
            background: #2563eb;
            color: white;
        }

        tr:nth-child(even) {
            background: #f8fafc;
        }

        .success {
            background: #dcfce7;
            color: #166534;
            padding: 15px;
            border-radius: 8px;
        }

    </style>

</head>

<body>

<div class="container">

    <h1>Excel Upload Successful!</h1>

    <div class="success">

        Your Excel file was successfully uploaded
        and read by PhpSpreadsheet.

    </div>

    <h2>Detected Columns</h2>

    <table>

        <tr>

            <th>Excel Column</th>

            <th>Column Name</th>

        </tr>

        <?php foreach ($headers as $column => $header): ?>

            <tr>

                <td>
                    <?php echo htmlspecialchars($column); ?>
                </td>

                <td>
                    <?php echo htmlspecialchars((string)$header); ?>
                </td>

            </tr>

        <?php endforeach; ?>

    </table>


    <h2>Student Data</h2>

    <table>

        <tr>

            <?php foreach ($headers as $header): ?>

                <th>
                    <?php echo htmlspecialchars((string)$header); ?>
                </th>

            <?php endforeach; ?>

        </tr>


        <?php foreach ($rows as $row): ?>

            <tr>

                <?php foreach ($headers as $column => $header): ?>

                    <td>

                        <?php
                        echo htmlspecialchars(
                            (string)($row[$column] ?? '')
                        );
                        ?>

                    </td>

                <?php endforeach; ?>

            </tr>

        <?php endforeach; ?>

    </table>

</div>

</body>

</html>
