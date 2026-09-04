<?php

require __DIR__ . '/vendor/autoload.php';

use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Cell\Coordinate;


/*
|--------------------------------------------------------------------------
| 1. CHECK REQUEST
|--------------------------------------------------------------------------
*/

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {

    header('Location: index.php');
    exit;

}


/*
|--------------------------------------------------------------------------
| 2. GET SUBMITTED DATA
|--------------------------------------------------------------------------
|
| These names match editor.php:
|
| template_path
| excel_path
| mappings
|
|--------------------------------------------------------------------------
*/

$templatePath = $_POST['template_path'] ?? '';
$excelPath    = $_POST['excel_path'] ?? '';
$mappingsJson = $_POST['mappings'] ?? '';


/*
|--------------------------------------------------------------------------
| 3. VALIDATE SUBMITTED DATA
|--------------------------------------------------------------------------
*/

if ($templatePath === '') {

    die('Error: Missing certificate template.');

}

if ($excelPath === '') {

    die('Error: Missing Excel file.');

}

if ($mappingsJson === '') {

    die('Error: No field mappings were submitted.');

}


/*
|--------------------------------------------------------------------------
| 4. DECODE FIELD MAPPINGS
|--------------------------------------------------------------------------
*/

$mappings = json_decode(
    $mappingsJson,
    true
);

if (
    !is_array($mappings) ||
    empty($mappings)
) {

    die('Error: Invalid field mappings.');

}


/*
|--------------------------------------------------------------------------
| 5. VALIDATE MAPPINGS
|--------------------------------------------------------------------------
*/

foreach ($mappings as $index => $mapping) {

    if (!is_array($mapping)) {

        die(
            'Error: Invalid mapping for field ' .
            ($index + 1) .
            '.'
        );

    }

    if (
        !isset($mapping['column']) ||
        $mapping['column'] === ''
    ) {

        die(
            'Error: No Excel column selected for field ' .
            ($index + 1) .
            '.'
        );

    }

}


/*
|--------------------------------------------------------------------------
| 6. RESOLVE FILE PATHS
|--------------------------------------------------------------------------
|
| editor.php currently sends absolute filesystem paths.
|
| We only allow files inside the expected upload directories.
|
|--------------------------------------------------------------------------
*/

$baseDirectory =
    realpath(__DIR__);

if ($baseDirectory === false) {

    die('Error: Could not determine application directory.');

}


/*
|--------------------------------------------------------------------------
| 7. RESOLVE TEMPLATE PATH
|--------------------------------------------------------------------------
*/

$templateRealPath =
    realpath($templatePath);

if ($templateRealPath === false) {

    die(
        'Error: Certificate template file could not be found.'
    );

}

$templateDirectory =
    realpath(
        __DIR__ . '/uploads/templates'
    );

if ($templateDirectory === false) {

    die(
        'Error: Template upload directory could not be found.'
    );

}


/*
|--------------------------------------------------------------------------
| 8. RESOLVE EXCEL PATH
|--------------------------------------------------------------------------
*/

$excelRealPath =
    realpath($excelPath);

if ($excelRealPath === false) {

    die(
        'Error: Excel file could not be found.'
    );

}

$spreadsheetDirectory =
    realpath(
        __DIR__ . '/uploads/spreadsheets'
    );

if ($spreadsheetDirectory === false) {

    die(
        'Error: Spreadsheet upload directory could not be found.'
    );

}


/*
|--------------------------------------------------------------------------
| 9. SECURITY CHECK
|--------------------------------------------------------------------------
|
| Make sure uploaded files are actually inside their
| respective upload directories.
|
|--------------------------------------------------------------------------
*/

$templatePrefix =
    rtrim($templateDirectory, DIRECTORY_SEPARATOR) .
    DIRECTORY_SEPARATOR;

$spreadsheetPrefix =
    rtrim($spreadsheetDirectory, DIRECTORY_SEPARATOR) .
    DIRECTORY_SEPARATOR;


if (
    strpos(
        $templateRealPath,
        $templatePrefix
    ) !== 0
) {

    die(
        'Error: Invalid certificate template path.'
    );

}


if (
    strpos(
        $excelRealPath,
        $spreadsheetPrefix
    ) !== 0
) {

    die(
        'Error: Invalid Excel file path.'
    );

}


/*
|--------------------------------------------------------------------------
| 10. CHECK TEMPLATE EXTENSION
|--------------------------------------------------------------------------
*/

$templateExtension =
    strtolower(
        pathinfo(
            $templateRealPath,
            PATHINFO_EXTENSION
        )
    );


$allowedImageExtensions = [
    'jpg',
    'jpeg',
    'png'
];


if (
    !in_array(
        $templateExtension,
        $allowedImageExtensions,
        true
    )
) {

    die(
        'Error: Certificate template must be JPG, JPEG or PNG.'
    );

}


/*
|--------------------------------------------------------------------------
| 11. READ EXCEL FILE
|--------------------------------------------------------------------------
*/

try {

    $spreadsheet =
        IOFactory::load(
            $excelRealPath
        );

    $worksheet =
        $spreadsheet->getActiveSheet();

    /*
    |--------------------------------------------------------------------------
    | Use numeric indexes:
    |
    | A = 0
    | B = 1
    | C = 2
    | etc.
    |--------------------------------------------------------------------------
    */

    $rows =
        $worksheet->toArray(
            null,
            true,
            true,
            false
        );

} catch (Throwable $e) {

    die(
        'Error reading Excel file: ' .
        htmlspecialchars(
            $e->getMessage(),
            ENT_QUOTES,
            'UTF-8'
        )
    );

}


/*
|--------------------------------------------------------------------------
| 12. CHECK EXCEL
|--------------------------------------------------------------------------
*/

if (empty($rows)) {

    die(
        'Error: The Excel file is empty.'
    );

}

if (count($rows) < 2) {

    die(
        'Error: Your Excel file must contain a header row and at least one data row.'
    );

}


/*
|--------------------------------------------------------------------------
| 13. REMOVE HEADER ROW
|--------------------------------------------------------------------------
*/

$headers =
    array_shift($rows);


/*
|--------------------------------------------------------------------------
| 14. DETERMINE IMAGE TYPE
|--------------------------------------------------------------------------
*/

if (
    $templateExtension === 'jpg' ||
    $templateExtension === 'jpeg'
) {

    $imageLoader = 'jpeg';

} elseif (
    $templateExtension === 'png'
) {

    $imageLoader = 'png';

} else {

    die(
        'Error: Unsupported certificate image format.'
    );

}


/*
|--------------------------------------------------------------------------
| 15. GET IMAGE DIMENSIONS
|--------------------------------------------------------------------------
*/

$imageInfo =
    getimagesize(
        $templateRealPath
    );

if ($imageInfo === false) {

    die(
        'Error: Could not read certificate image dimensions.'
    );

}


$imageWidth =
    (int)$imageInfo[0];

$imageHeight =
    (int)$imageInfo[1];


if (
    $imageWidth <= 0 ||
    $imageHeight <= 0
) {

    die(
        'Error: Invalid certificate image dimensions.'
    );

}


/*
|--------------------------------------------------------------------------
| 16. CREATE OUTPUT DIRECTORY
|--------------------------------------------------------------------------
*/

$outputDir =
    __DIR__ . '/generated';


if (!is_dir($outputDir)) {

    if (
        !mkdir(
            $outputDir,
            0777,
            true
        )
    ) {

        die(
            'Error: Could not create generated folder.'
        );

    }

}


/*
|--------------------------------------------------------------------------
| 17. CREATE UNIQUE BATCH ID
|--------------------------------------------------------------------------
*/

try {

    $randomPart =
        bin2hex(
            random_bytes(4)
        );

} catch (Throwable $e) {

    $randomPart =
        uniqid();

}


$batchId =
    date('Ymd_His') .
    '_' .
    $randomPart;


$batchDir =
    $outputDir .
    '/' .
    $batchId;


if (
    !mkdir(
        $batchDir,
        0777,
        true
    )
) {

    die(
        'Error: Could not create certificate batch folder.'
    );

}


/*
|--------------------------------------------------------------------------
| 18. FPDF
|--------------------------------------------------------------------------
*/

require_once
    __DIR__ .
    '/vendor/setasign/fpdf/fpdf.php';


/*
|--------------------------------------------------------------------------
| 19. CREATE ONE PDF OBJECT
|--------------------------------------------------------------------------
|
| IMPORTANT:
|
| One PDF object is used for the entire batch.
|
| Output() is called ONLY after every certificate/page
| has been added.
|
|--------------------------------------------------------------------------
*/

$pdf =
    new FPDF(
        'L',
        'pt',
        [
            $imageWidth,
            $imageHeight
        ]
    );


$pdf->SetAutoPageBreak(
    false
);


/*
|--------------------------------------------------------------------------
| 20. FONT HELPER
|--------------------------------------------------------------------------
*/

function getFontPath($fontName)
{

    $fontName =
        basename(
            (string)$fontName
        );

    $fontsDirectory =
        __DIR__ . '/fonts';


    $path =
        $fontsDirectory .
        '/' .
        $fontName;


    if (
        file_exists($path) &&
        is_file($path)
    ) {

        return $path;

    }


    return null;

}


/*
|--------------------------------------------------------------------------
| 21. HEX COLOR TO RGB
|--------------------------------------------------------------------------
*/

function hexToRgb($hex)
{

    $hex =
        trim(
            (string)$hex
        );


    $hex =
        ltrim(
            $hex,
            '#'
        );


    /*
    |--------------------------------------------------------------------------
    | Convert #RGB to #RRGGBB
    |--------------------------------------------------------------------------
    */

    if (strlen($hex) === 3) {

        $hex =
            $hex[0] . $hex[0] .
            $hex[1] . $hex[1] .
            $hex[2] . $hex[2];

    }


    /*
    |--------------------------------------------------------------------------
    | Invalid color
    |--------------------------------------------------------------------------
    */

    if (
        strlen($hex) !== 6 ||
        !ctype_xdigit($hex)
    ) {

        return [
            0,
            0,
            0
        ];

    }


    return [

        hexdec(
            substr(
                $hex,
                0,
                2
            )
        ),

        hexdec(
            substr(
                $hex,
                2,
                2
            )
        ),

        hexdec(
            substr(
                $hex,
                4,
                2
            )
        )

    ];

}


/*
|--------------------------------------------------------------------------
| 22. NORMALIZE EXCEL COLUMN
|--------------------------------------------------------------------------
|
| Converts:
|
| A -> 0
| B -> 1
| C -> 2
|
| Also supports numeric column indexes if supplied.
|
|--------------------------------------------------------------------------
*/

function getExcelColumnIndex($column)
{

    $column =
        trim(
            (string)$column
        );


    if ($column === '') {

        return null;

    }


    /*
    |--------------------------------------------------------------------------
    | If numeric:
    |
    | Editor currently sends letters, but numeric values are
    | supported too.
    |--------------------------------------------------------------------------
    */

    if (
        ctype_digit($column)
    ) {

        $index =
            (int)$column;


        if ($index >= 0) {

            return $index;

        }

    }


    /*
    |--------------------------------------------------------------------------
    | Excel column letters
    |--------------------------------------------------------------------------
    */

    $column =
        strtoupper(
            $column
        );


    try {

        /*
        |--------------------------------------------------------------------------
        | PhpSpreadsheet returns:
        |
        | A = 1
        | B = 2
        |
        | Our PHP row arrays are zero-indexed:
        |
        | A = 0
        | B = 1
        |--------------------------------------------------------------------------
        */

        return
            Coordinate::columnIndexFromString(
                $column
            ) - 1;

    } catch (Throwable $e) {

        return null;

    }

}


/*
|--------------------------------------------------------------------------
| 23. CLAMP VALUE
|--------------------------------------------------------------------------
*/

function clamp($value, $min, $max)
{

    return max(
        $min,
        min(
            $max,
            $value
        )
    );

}


/*
|--------------------------------------------------------------------------
| 24. GENERATE CERTIFICATES
|--------------------------------------------------------------------------
*/

$certificateCount = 0;


/*
|--------------------------------------------------------------------------
| Process every Excel row
|--------------------------------------------------------------------------
*/

foreach (
    $rows as $rowIndex => $row
) {


    /*
    |--------------------------------------------------------------------------
    | Check whether row contains any data
    |--------------------------------------------------------------------------
    */

    $hasData = false;


    foreach (
        $row as $cell
    ) {

        if (
            $cell !== null &&
            trim(
                (string)$cell
            ) !== ''
        ) {

            $hasData = true;

            break;

        }

    }


    /*
    |--------------------------------------------------------------------------
    | Skip completely empty rows
    |--------------------------------------------------------------------------
    */

    if (!$hasData) {

        continue;

    }


    /*
    |--------------------------------------------------------------------------
    | LOAD CERTIFICATE IMAGE
    |--------------------------------------------------------------------------
    */

    if (
        $imageLoader === 'png'
    ) {

        $image =
            imagecreatefrompng(
                $templateRealPath
            );

    } else {

        $image =
            imagecreatefromjpeg(
                $templateRealPath
            );

    }


    if (!$image) {

        die(
            'Error: Could not load certificate template.'
        );

    }


    /*
    |--------------------------------------------------------------------------
    | PNG TRANSPARENCY
    |--------------------------------------------------------------------------
    */

    if (
        $imageLoader === 'png'
    ) {

        imagealphablending(
            $image,
            true
        );

        imagesavealpha(
            $image,
            true
        );

    }


    /*
    |--------------------------------------------------------------------------
    | DRAW ALL MAPPED FIELDS
    |--------------------------------------------------------------------------
    */

    foreach (
        $mappings as $mappingIndex => $mapping
    ) {


        /*
        |--------------------------------------------------------------------------
        | Excel column
        |--------------------------------------------------------------------------
        */

        $column =
            $mapping['column'] ?? 'A';


        $columnIndex =
            getExcelColumnIndex(
                $column
            );


        if (
            $columnIndex === null
        ) {

            continue;

        }


        /*
        |--------------------------------------------------------------------------
        | Get text from Excel
        |--------------------------------------------------------------------------
        */

        $text = '';


        if (
            isset(
                $row[$columnIndex]
            )
        ) {

            $value =
                $row[$columnIndex];


            if (
                $value !== null
            ) {

                $text =
                    trim(
                        (string)$value
                    );

            }

        }


        /*
        |--------------------------------------------------------------------------
        | Skip blank cells
        |--------------------------------------------------------------------------
        */

        if ($text === '') {

            continue;

        }


        /*
        |--------------------------------------------------------------------------
        | X POSITION
        |--------------------------------------------------------------------------
        */

        $x =
            isset($mapping['x'])
            ? (int)$mapping['x']
            : 0;


        /*
        |--------------------------------------------------------------------------
        | Y POSITION
        |--------------------------------------------------------------------------
        */

        $y =
            isset($mapping['y'])
            ? (int)$mapping['y']
            : 0;


        /*
        |--------------------------------------------------------------------------
        | Keep coordinates inside image
        |--------------------------------------------------------------------------
        */

        $x =
            clamp(
                $x,
                0,
                $imageWidth
            );


        $y =
            clamp(
                $y,
                0,
                $imageHeight
            );


        /*
        |--------------------------------------------------------------------------
        | FONT SIZE
        |--------------------------------------------------------------------------
        */

        $fontSize =
            isset($mapping['size'])
            ? (int)$mapping['size']
            : 32;


        if (
            $fontSize <= 0
        ) {

            $fontSize = 32;

        }


        /*
        |--------------------------------------------------------------------------
        | Limit font size
        |--------------------------------------------------------------------------
        */

        if (
            $fontSize > 500
        ) {

            $fontSize = 500;

        }


        /*
        |--------------------------------------------------------------------------
        | FONT
        |--------------------------------------------------------------------------
        */

        $fontName =
            $mapping['font'] ?? 'arial.ttf';


        $fontPath =
            getFontPath(
                $fontName
            );


        /*
        |--------------------------------------------------------------------------
        | COLOR
        |--------------------------------------------------------------------------
        */

        $colorHex =
            $mapping['color'] ?? '#000000';


        [
            $red,
            $green,
            $blue
        ] =
            hexToRgb(
                $colorHex
            );


        $textColor =
            imagecolorallocate(
                $image,
                $red,
                $green,
                $blue
            );


        /*
        |--------------------------------------------------------------------------
        | DRAW TEXT
        |--------------------------------------------------------------------------
        */

        if (
            $fontPath !== null &&
            function_exists('imagettftext')
        ) {


            /*
            |--------------------------------------------------------------------------
            | IMPORTANT:
            |
            | The editor's Y coordinate represents the TOP of the
            | text field.
            |
            | imagettftext() expects the Y coordinate to be the
            | BASELINE.
            |
            | Therefore we add approximately the font height.
            |--------------------------------------------------------------------------
            */

            $baselineY =
                $y + $fontSize;


            /*
            |--------------------------------------------------------------------------
            | Prevent baseline from going too far outside image.
            |--------------------------------------------------------------------------
            */

            if (
                $baselineY > $imageHeight
            ) {

                $baselineY =
                    $imageHeight;

            }


            imagettftext(
                $image,
                $fontSize,
                0,
                $x,
                $baselineY,
                $textColor,
                $fontPath,
                $text
            );


        } else {


            /*
            |--------------------------------------------------------------------------
            | FALLBACK FONT
            |--------------------------------------------------------------------------
            |
            | GD's built-in imagestring() does not use the same
            | font sizes as TTF.
            |--------------------------------------------------------------------------
            */

            imagestring(
                $image,
                5,
                $x,
                $y,
                $text,
                $textColor
            );

        }

    }


    /*
    |--------------------------------------------------------------------------
    | 25. ADD PDF PAGE
    |--------------------------------------------------------------------------
    */

    $pdf->AddPage(
        'L',
        [
            $imageWidth,
            $imageHeight
        ]
    );


    /*
    |--------------------------------------------------------------------------
    | 26. TEMPORARY CERTIFICATE IMAGE
    |--------------------------------------------------------------------------
    */

    $certificateNumber =
        $certificateCount + 1;


    $tempImageName =
        'certificate_' .
        $certificateNumber .
        '.jpg';


    $tempImagePath =
        $batchDir .
        '/' .
        $tempImageName;


    /*
    |--------------------------------------------------------------------------
    | Convert final certificate to JPG.
    |
    | FPDF can then embed it reliably.
    |--------------------------------------------------------------------------
    */

    if (
        !imagejpeg(
            $image,
            $tempImagePath,
            95
        )
    ) {

        imagedestroy($image);

        die(
            'Error: Could not create certificate image.'
        );

    }


    /*
    |--------------------------------------------------------------------------
    | 27. ADD IMAGE TO PDF
    |--------------------------------------------------------------------------
    */

    $pdf->Image(
        $tempImagePath,
        0,
        0,
        $imageWidth,
        $imageHeight
    );


    /*
    |--------------------------------------------------------------------------
    | 28. FREE GD MEMORY
    |--------------------------------------------------------------------------
    */

    imagedestroy(
        $image
    );


    /*
    |--------------------------------------------------------------------------
    | 29. INCREASE CERTIFICATE COUNT
    |--------------------------------------------------------------------------
    */

    $certificateCount++;

}


/*
|--------------------------------------------------------------------------
| 30. CHECK CERTIFICATE COUNT
|--------------------------------------------------------------------------
*/

if (
    $certificateCount === 0
) {

    die(
        'Error: No student rows were found in the Excel file.'
    );

}


/*
|--------------------------------------------------------------------------
| 31. CREATE PDF FILE
|--------------------------------------------------------------------------
*/

$pdfFileName =
    'certificates_' .
    $batchId .
    '.pdf';


$pdfFilePath =
    $batchDir .
    '/' .
    $pdfFileName;


/*
|--------------------------------------------------------------------------
| 32. SAVE PDF
|--------------------------------------------------------------------------
|
| Output() MUST happen only once.
|--------------------------------------------------------------------------
*/

try {

    $pdf->Output(
        'F',
        $pdfFilePath
    );

} catch (Throwable $e) {

    die(
        'Error creating PDF: ' .
        htmlspecialchars(
            $e->getMessage(),
            ENT_QUOTES,
            'UTF-8'
        )
    );

}


/*
|--------------------------------------------------------------------------
| 33. CHECK PDF
|--------------------------------------------------------------------------
*/

if (
    !file_exists($pdfFilePath)
) {

    die(
        'Error: PDF file was not created.'
    );

}


/*
|--------------------------------------------------------------------------
| 34. CREATE ZIP
|--------------------------------------------------------------------------
*/

$zipFileName =
    'certificates_' .
    $batchId .
    '.zip';


$zipFilePath =
    $outputDir .
    '/' .
    $zipFileName;


$zipCreated = false;


if (
    class_exists('ZipArchive')
) {

    $zip =
        new ZipArchive();


    $zipResult =
        $zip->open(
            $zipFilePath,
            ZipArchive::CREATE |
            ZipArchive::OVERWRITE
        );


    if (
        $zipResult === true
    ) {

        /*
        |--------------------------------------------------------------------------
        | Add PDF
        |--------------------------------------------------------------------------
        */

        $zip->addFile(
            $pdfFilePath,
            $pdfFileName
        );


        /*
        |--------------------------------------------------------------------------
        | Optional:
        | Add individual certificate JPG files.
        |
        | This makes the ZIP useful if the user wants the
        | certificates individually as well.
        |--------------------------------------------------------------------------
        */

        for (
            $i = 1;
            $i <= $certificateCount;
            $i++
        ) {

            $certificatePath =
                $batchDir .
                '/certificate_' .
                $i .
                '.jpg';


            if (
                file_exists(
                    $certificatePath
                )
            ) {

                $zip->addFile(
                    $certificatePath,
                    'images/certificate_' .
                    $i .
                    '.jpg'
                );

            }

        }


        $zip->close();

        $zipCreated = true;

    }

}


/*
|--------------------------------------------------------------------------
| 35. CREATE DOWNLOAD URLS
|--------------------------------------------------------------------------
*/

$pdfDownloadUrl =
    'generated/' .
    rawurlencode(
        $batchId
    ) .
    '/' .
    rawurlencode(
        $pdfFileName
    );


$zipDownloadUrl =
    'generated/' .
    rawurlencode(
        $zipFileName
    );


/*
|--------------------------------------------------------------------------
| 36. SUCCESS PAGE
|--------------------------------------------------------------------------
*/

?>
<!DOCTYPE html>

<html lang="en">

<head>

<meta charset="UTF-8">

<meta
    name="viewport"
    content="width=device-width, initial-scale=1.0"
>

<title>
    Certificates Generated
</title>


<style>

* {
    box-sizing: border-box;
}

body {

    margin: 0;

    padding: 40px 20px;

    background: #f4f6f9;

    font-family:
        Arial,
        sans-serif;

    color: #222;

}


.card {

    max-width: 650px;

    margin: 60px auto;

    background: white;

    padding: 45px;

    border-radius: 15px;

    box-shadow:
        0 10px 30px
        rgba(0,0,0,0.08);

    text-align: center;

}


.success {

    width: 70px;

    height: 70px;

    margin:
        0 auto 20px;

    border-radius: 50%;

    background: #28a745;

    color: white;

    font-size: 40px;

    line-height: 70px;

}


h1 {

    margin-bottom: 10px;

}


.count {

    font-size: 20px;

    margin: 20px 0;

}


.button {

    display: inline-block;

    padding: 15px 25px;

    margin: 8px;

    border-radius: 8px;

    text-decoration: none;

    font-weight: bold;

}


.primary {

    background: #007bff;

    color: white;

}


.secondary {

    background: #28a745;

    color: white;

}


.back {

    display: block;

    margin-top: 25px;

    color: #555;

    text-decoration: none;

}


.back:hover {

    text-decoration: underline;

}


.details {

    margin-top: 25px;

    padding: 15px;

    background: #f8f9fa;

    border-radius: 8px;

    color: #555;

    font-size: 14px;

}


</style>

</head>


<body>


<div class="card">


    <div class="success">
        ✓
    </div>


    <h1>
        Certificates Generated!
    </h1>


    <p class="count">

        <strong>
            <?php
            echo $certificateCount;
            ?>
        </strong>

        certificate<?php
            echo
                $certificateCount === 1
                ? ''
                : 's';
        ?>

        successfully generated.

    </p>


    <p>
        Your certificates have been combined
        into one PDF.
    </p>


    <div class="details">

        <strong>
            Batch:
        </strong>

        <?php
        echo htmlspecialchars(
            $batchId,
            ENT_QUOTES,
            'UTF-8'
        );
        ?>

        <br><br>

        <strong>
            PDF pages:
        </strong>

        <?php
        echo $certificateCount;
        ?>

    </div>


    <a
        class="button primary"
        href="<?php
            echo htmlspecialchars(
                $pdfDownloadUrl,
                ENT_QUOTES,
                'UTF-8'
            );
        ?>"
        download
    >
        Download PDF
    </a>


    <?php if ($zipCreated): ?>

        <a
            class="button secondary"
            href="<?php
                echo htmlspecialchars(
                    $zipDownloadUrl,
                    ENT_QUOTES,
                    'UTF-8'
                );
            ?>"
            download
        >
            Download ZIP
        </a>

    <?php endif; ?>


    <a
        class="back"
        href="index.php"
    >
        ← Generate another batch
    </a>


</div>


</body>

</html>
