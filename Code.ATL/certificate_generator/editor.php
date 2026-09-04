<?php

require __DIR__ . '/vendor/autoload.php';

use PhpOffice\PhpSpreadsheet\IOFactory;


// ============================================================
// CHECK REQUEST
// ============================================================

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {

    header('Location: index.php');
    exit;

}


// ============================================================
// CHECK FILES
// ============================================================

if (
    !isset($_FILES['template_image']) ||
    !isset($_FILES['excel_file'])
) {

    die('Error: Please upload both a certificate template and an Excel file.');

}


if ($_FILES['template_image']['error'] !== UPLOAD_ERR_OK) {

    die('Error uploading the certificate template.');

}


if ($_FILES['excel_file']['error'] !== UPLOAD_ERR_OK) {

    die('Error uploading the Excel file.');

}


// ============================================================
// CREATE DIRECTORIES
// ============================================================

$templateDirectory = __DIR__ . '/uploads/templates/';
$spreadsheetDirectory = __DIR__ . '/uploads/spreadsheets/';

if (!is_dir($templateDirectory)) {

    mkdir($templateDirectory, 0777, true);

}

if (!is_dir($spreadsheetDirectory)) {

    mkdir($spreadsheetDirectory, 0777, true);

}


// ============================================================
// VALIDATE TEMPLATE
// ============================================================

$templateOriginalName =
    $_FILES['template_image']['name'];

$templateExtension =
    strtolower(
        pathinfo(
            $templateOriginalName,
            PATHINFO_EXTENSION
        )
    );

$allowedTemplateExtensions = [
    'jpg',
    'jpeg',
    'png'
];

if (
    !in_array(
        $templateExtension,
        $allowedTemplateExtensions,
        true
    )
) {

    die(
        'Error: Certificate template must be JPG, JPEG or PNG.'
    );

}


// ============================================================
// SAVE TEMPLATE
// ============================================================

$templateFileName =
    'template_' . uniqid() . '.' . $templateExtension;

$templateFullPath =
    $templateDirectory . $templateFileName;

$templateWebPath =
    'uploads/templates/' . $templateFileName;


if (
    !move_uploaded_file(
        $_FILES['template_image']['tmp_name'],
        $templateFullPath
    )
) {

    die('Error: Could not save certificate template.');

}


// ============================================================
// VALIDATE EXCEL
// ============================================================

$excelOriginalName =
    $_FILES['excel_file']['name'];

$excelExtension =
    strtolower(
        pathinfo(
            $excelOriginalName,
            PATHINFO_EXTENSION
        )
    );

$allowedExcelExtensions = [
    'xlsx',
    'xls',
    'csv'
];

if (
    !in_array(
        $excelExtension,
        $allowedExcelExtensions,
        true
    )
) {

    die(
        'Error: Excel file must be XLSX, XLS or CSV.'
    );

}


// ============================================================
// SAVE EXCEL
// ============================================================

$excelFileName =
    'spreadsheet_' . uniqid() . '.' . $excelExtension;

$excelFullPath =
    $spreadsheetDirectory . $excelFileName;

$excelWebPath =
    'uploads/spreadsheets/' . $excelFileName;


if (
    !move_uploaded_file(
        $_FILES['excel_file']['tmp_name'],
        $excelFullPath
    )
) {

    die('Error: Could not save Excel file.');

}


// ============================================================
// READ EXCEL
// ============================================================

try {

    $spreadsheet =
        IOFactory::load($excelFullPath);

    $worksheet =
        $spreadsheet->getActiveSheet();

    $sheetData =
        $worksheet->toArray(
            null,
            true,
            true,
            true
        );

} catch (Throwable $e) {

    die(
        'Could not read the Excel file: ' .
        htmlspecialchars($e->getMessage())
    );

}


// ============================================================
// CHECK EXCEL
// ============================================================

if (empty($sheetData)) {

    die('The Excel file is empty.');

}


// ============================================================
// FIRST ROW = HEADERS
// ============================================================

$headerRow =
    array_shift($sheetData);

$headers = [];

foreach ($headerRow as $column => $header) {

    $header = trim((string)$header);

    if ($header !== '') {

        $headers[$column] = $header;

    }

}


if (empty($headers)) {

    die(
        'No column headers were found. ' .
        'Put column names in the first row of your Excel file.'
    );

}


// ============================================================
// DATA ROW COUNT
// ============================================================

$dataRows = [];

foreach ($sheetData as $row) {

    $hasData = false;

    foreach ($headers as $column => $header) {

        if (
            isset($row[$column]) &&
            trim((string)$row[$column]) !== ''
        ) {

            $hasData = true;
            break;

        }

    }

    if ($hasData) {

        $dataRows[] = $row;

    }

}

$rowCount = count($dataRows);

?>

<!DOCTYPE html>

<html lang="en">

<head>

<meta charset="UTF-8">

<meta name="viewport" content="width=device-width, initial-scale=1.0">

<title>Certificate Editor</title>


<style>

/* ============================================================
   GENERAL
   ============================================================ */

* {
    box-sizing: border-box;
}

body {
    margin: 0;
    font-family: Arial, sans-serif;
    background: #f1f5f9;
    color: #1e293b;
}


/* ============================================================
   CUSTOM FONTS
   ============================================================ */

@font-face {
    font-family: 'Algerian';
    src: url('fonts/ALGER.ttf') format('truetype');
    font-weight: normal;
    font-style: normal;
}

@font-face {
    font-family: 'Agency-FB';
    src: url('fonts/AGENCYR.TTF') format('truetype');
    font-weight: normal;
    font-style: normal;
}

@font-face {
    font-family: 'Agency FB';
    src: url('fonts/AGENCYB.TTF') format('truetype');
    font-weight: bold;
    font-style: normal;
}

@font-face {
    font-family: 'Book Antiqua';
    src: url('fonts/ANTQUAB.TTF') format('truetype');
    font-weight: bold;
    font-style: normal;
}


@font-face {
    font-family: 'Book-Antiqua';
    src: url('fonts/ANTQUABI.TTF') format('truetype');
    font-weight: bold;
    font-style: italic;
}

@font-face {
    font-family: 'Bok-Antiqua';
    src: url('fonts/ANTQUAI.TTF') format('truetype');
    font-weight: normal;
    font-style: italic;
}

@font-face {
    font-family: 'Ariala';
    src: url('fonts/arialbd.ttf') format('truetype');
    font-weight: bold;
    font-style: normal;
}

@font-face {
    font-family: 'Arialb';
    src: url('fonts/arialbi.ttf') format('truetype');
    font-weight: bold;
    font-style: italic;
}

@font-face {
    font-family: 'Arialc';
    src: url('fonts/ariali.ttf') format('truetype');
    font-weight: normal;
    font-style: italic;
}

@font-face {
    font-family: 'Ariald';
    src: url('fonts/ARIALN.TTF') format('truetype');
    font-weight: normal;
    font-style: normal;
}

@font-face {
    font-family: 'Ariale';
    src: url('fonts/ARIALNB.TTF') format('truetype');
    font-weight: bold;
    font-style: normal;
}

@font-face {
    font-family: 'Arialf';
    src: url('fonts/ARIALNI.TTF') format('truetype');
    font-weight: normal;
    font-style: italic;
}

/* ============================================================
   TOP BAR
   ============================================================ */

.topbar {
    background: #0f172a;
    color: white;
    padding: 18px 25px;
}

.topbar h1 {
    margin: 0;
}

.topbar p {
    margin: 5px 0 0;
    color: #cbd5e1;
}


/* ============================================================
   LAYOUT
   ============================================================ */

.layout {
    display: grid;
    grid-template-columns: minmax(500px, 1.5fr) minmax(350px, 0.8fr);
    gap: 20px;
    padding: 20px;
    max-width: 1600px;
    margin: auto;
}


/* ============================================================
   PANELS
   ============================================================ */

.panel {
    background: white;
    border-radius: 10px;
    padding: 20px;
    box-shadow: 0 3px 15px rgba(0,0,0,0.07);
}


/* ============================================================
   PREVIEW
   ============================================================ */

.preview-area {
    background: #e2e8f0;
    padding: 20px;
    border-radius: 8px;
    overflow: auto;
}

#canvasWrapper {
    position: relative;
    display: inline-block;
    line-height: 0;
}

#certificateImage {
    display: block;
    max-width: 100%;
    height: auto;
}

.text-field {
    position: absolute;
    cursor: move;
    line-height: normal;
    white-space: nowrap;
    user-select: none;
    padding: 3px;
    border: 2px dashed #2563eb;
    background: rgba(255,255,255,0.15);
}

.text-field.selected {
    border-color: #dc2626;
    background: rgba(255,255,255,0.25);
}


/* ============================================================
   INFO
   ============================================================ */

.info {
    padding: 12px;
    background: #eff6ff;
    border-left: 4px solid #2563eb;
    margin-bottom: 15px;
    line-height: 1.5;
}


/* ============================================================
   FIELD CARD
   ============================================================ */

.field-card {
    background: #f8fafc;
    border: 1px solid #cbd5e1;
    padding: 15px;
    border-radius: 8px;
    margin-bottom: 15px;
}

.field-card h3 {
    margin-top: 0;
}


/* ============================================================
   FORM
   ============================================================ */

label {
    display: block;
    font-weight: bold;
    margin-top: 10px;
    margin-bottom: 5px;
}

select,
input[type="number"],
input[type="color"] {
    width: 100%;
    padding: 9px;
    border: 1px solid #cbd5e1;
    border-radius: 5px;
}

select {
    background: white;
}

input[type="color"] {
    height: 42px;
    padding: 3px;
}


/* ============================================================
   COORDINATES
   ============================================================ */

.coordinates {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 10px;
}


/* ============================================================
   BUTTONS
   ============================================================ */

button {
    border: none;
    border-radius: 6px;
    padding: 11px 15px;
    cursor: pointer;
    font-size: 15px;
}

.add-button {
    width: 100%;
    background: #2563eb;
    color: white;
    margin-bottom: 12px;
}

.add-button:hover {
    background: #1d4ed8;
}

.remove-button {
    background: #dc2626;
    color: white;
    margin-top: 12px;
}

.remove-button:hover {
    background: #b91c1c;
}

.generate-button {
    width: 100%;
    background: #16a34a;
    color: white;
    font-size: 18px;
    padding: 15px;
}

.generate-button:hover {
    background: #15803d;
}

.field-column {
    width: 100%;
    padding: 9px;
    border: 1px solid #cbd5e1;
    border-radius: 5px;
    background: white;
}
/* ============================================================
   SMALL TEXT
   ============================================================ */

.small {
    color: #64748b;
    font-size: 13px;
}


/* ============================================================
   RESPONSIVE
   ============================================================ */

@media (max-width: 1000px) {

    .layout {
        grid-template-columns: 1fr;
    }

}

</style>

</head>


<body>


<div class="topbar">

    <h1>Certificate Generator</h1>

    <p>
        Certificate Editor
    </p>

</div>


<div class="layout">


<!-- ========================================================= -->
<!-- PREVIEW -->
<!-- ========================================================= -->

<div class="panel">

    <h2>Certificate Preview</h2>

    <div class="info">

        <strong>
            Drag the blue text fields around your certificate.
        </strong>

        <br>

        The positions you create here will be used for every
        certificate generated from your Excel spreadsheet.

    </div>


    <div class="preview-area">

        <div id="canvasWrapper">

            <img
                id="certificateImage"
                src="<?php echo htmlspecialchars($templateWebPath); ?>"
                alt="Certificate"
            >

        </div>

    </div>


    <p class="small">

        Excel data rows detected:

        <strong>
            <?php echo $rowCount; ?>
        </strong>

    </p>

</div>


<!-- ========================================================= -->
<!-- CONTROLS -->
<!-- ========================================================= -->

<div class="panel">

    <h2>Fields</h2>

    <div class="info">

        Your Excel columns:

        <br><br>

        <?php foreach ($headers as $column => $header): ?>

            <strong>
                <?php echo htmlspecialchars($column); ?>
            </strong>

            —

            <?php echo htmlspecialchars($header); ?>

            <br>

        <?php endforeach; ?>

    </div>


    <form
        id="generateForm"
        action="generate.php"
        method="POST"
    >


        <input
            type="hidden"
            name="template_path"
            value="<?php echo htmlspecialchars($templateFullPath); ?>"
        >


        <input
            type="hidden"
            name="excel_path"
            value="<?php echo htmlspecialchars($excelFullPath); ?>"
        >


        <input
            type="hidden"
            name="mappings"
            id="mappingsInput"
        >


        <div id="fieldsContainer"></div>


        <button
            type="button"
            class="add-button"
            onclick="addField()"
        >
            + Add Field
        </button>


        <button
            type="submit"
            class="generate-button"
        >
            Generate All Certificates → PDF
        </button>


    </form>

</div>

</div>


<script>


// ============================================================
// EXCEL HEADERS
// ============================================================

const headers =
    <?php echo json_encode($headers); ?>;


const firstColumn =
    Object.keys(headers)[0];


let fieldCounter = 0;


// ============================================================
// AVAILABLE FONTS
// ============================================================

const availableFonts = [

   
    {
        name: 'Agency Bold',
        value: 'Agency FB'
    },

    {
        name: 'Agency Regular',
        value: 'Agency-FB'
    },

     {
        name: 'Arial',
        value: 'Arial'
    },

    {
        name: 'Arial Bold',
        value: 'Ariala'
    },

    {
        name: 'Arial Bold Italic',
        value: 'Arialb'
    },

    {
        name: 'Arial Italic',
        value: 'Arialc'
    },

    {
        name: 'Arial Narrow',
        value: 'Ariald'
    },

    {
        name: 'Arial Narrow Bold',
        value: 'Ariale'
    },

    {
        name: 'Arial Narrow Italic',
        value: 'Arialf'
    },

    {
        name: 'Algerian',
        value: 'Algerian'
    },

   {
    name: 'Book Antiqua',
    value: 'Bok-Antiqua'
   },

    {
    name: 'Book Antiqua Bold',
    value: 'Book Antiqua'
    },

   {
    name: 'Book Antiqua Bold Italic',
    value: 'Book-Antiqua'
   },
   

];


// ============================================================
// ADD FIELD
// ============================================================

function addField() {

    fieldCounter++;

    const id =
        fieldCounter;


    const container =
        document.getElementById(
            'fieldsContainer'
        );


    const card =
        document.createElement('div');

    card.className =
        'field-card';

    card.dataset.id =
        id;


    // --------------------------------------------------------
    // COLUMN OPTIONS
    // --------------------------------------------------------

    let columnOptions = '';


    for (
        const column in headers
    ) {

        columnOptions += `

            <option value="${column}">
                ${headers[column]}
                (Column ${column})
            </option>

        `;

    }


    // --------------------------------------------------------
    // FONT OPTIONS
    // --------------------------------------------------------

    let fontOptions = '';


    availableFonts.forEach(
        function(font) {

            fontOptions += `

                <option value="${font.value}">
                    ${font.name}
                </option>

            `;

        }
    );


    // --------------------------------------------------------
    // CARD HTML
    // --------------------------------------------------------

    card.innerHTML = `

        <h3>
            Field ${id}
        </h3>


        <label>
            Excel Column
        </label>

        <select
            class="field-column"
        >

            ${columnOptions}

        </select>


        <label>
            Font
        </label>

        <select
            class="field-font"
            size="3"
        >

            ${fontOptions}

        </select>


        <label>
            Font Size
        </label>

        <input
            type="number"
            class="field-size"
            value="32"
            min="1"
            max="200"
        >


        <label>
            Text Color
        </label>

        <input
            type="color"
            class="field-color"
            value="#000000"
        >


        <div class="coordinates">

            <div>

                <label>
                    X
                </label>

                <input
                    type="number"
                    class="field-x"
                    value="100"
                    min="0"
                >

            </div>


            <div>

                <label>
                    Y
                </label>

                <input
                    type="number"
                    class="field-y"
                    value="100"
                    min="0"
                >

            </div>

        </div>


        <button
            type="button"
            class="remove-button"
            onclick="removeField(this)"
        >
            Remove Field
        </button>

    `;


    container.appendChild(card);


    // ========================================================
    // GET CONTROLS
    // ========================================================

    const columnSelect =
        card.querySelector(
            '.field-column'
        );


    const fontSelect =
        card.querySelector(
            '.field-font'
        );


    const sizeInput =
        card.querySelector(
            '.field-size'
        );


    const colorInput =
        card.querySelector(
            '.field-color'
        );


    const xInput =
        card.querySelector(
            '.field-x'
        );


    const yInput =
        card.querySelector(
            '.field-y'
        );


    // ========================================================
    // EVENTS
    // ========================================================

    columnSelect.addEventListener(
        'change',
        () => updatePreviewField(id)
    );


    fontSelect.addEventListener(
        'change',
        () => updatePreviewField(id)
    );


    sizeInput.addEventListener(
        'input',
        () => updatePreviewField(id)
    );


    colorInput.addEventListener(
        'input',
        () => updatePreviewField(id)
    );


    xInput.addEventListener(
        'input',
        () => updatePreviewField(id)
    );


    yInput.addEventListener(
        'input',
        () => updatePreviewField(id)
    );


    // ========================================================
    // CREATE PREVIEW
    // ========================================================

    createPreviewField(id);

}


// ============================================================
// CREATE PREVIEW FIELD
// ============================================================

function createPreviewField(id) {

    const wrapper =
        document.getElementById(
            'canvasWrapper'
        );


    const element =
        document.createElement('div');


    element.className =
        'text-field';


    element.id =
        'preview-' + id;


    element.textContent =
        'Sample Text';


    wrapper.appendChild(element);


    makeDraggable(
        element,
        id
    );


    updatePreviewField(id);

}


// ============================================================
// UPDATE PREVIEW
// ============================================================

function updatePreviewField(id) {

    const card =
        document.querySelector(
            `.field-card[data-id="${id}"]`
        );


    if (!card) {
        return;
    }


    const column =
        card.querySelector(
            '.field-column'
        ).value;


    const font =
        card.querySelector(
            '.field-font'
        ).value;


    const size =
        card.querySelector(
            '.field-size'
        ).value;


    const color =
        card.querySelector(
            '.field-color'
        ).value;


    const x =
        card.querySelector(
            '.field-x'
        ).value;


    const y =
        card.querySelector(
            '.field-y'
        ).value;


    const preview =
        document.getElementById(
            'preview-' + id
        );


    if (!preview) {
        return;
    }


    // ========================================================
    // TEXT
    // ========================================================

    preview.textContent =
        headers[column];


    // ========================================================
    // FONT
    // ========================================================

    preview.style.fontFamily =
        font;


    // ========================================================
    // FONT SIZE
    // ========================================================

    preview.style.fontSize =
        size + 'px';


    // ========================================================
    // COLOR
    // ========================================================

    preview.style.color =
        color;


    // ========================================================
    // POSITION
    // ========================================================

    preview.style.left =
        x + 'px';


    preview.style.top =
        y + 'px';

}


// ============================================================
// DRAGGING
// ============================================================

function makeDraggable(
    element,
    id
) {

    let dragging =
        false;


    let offsetX =
        0;


    let offsetY =
        0;


    element.addEventListener(
        'mousedown',
        function(event) {

            dragging =
                true;


            element.classList.add(
                'selected'
            );


            const rect =
                element.getBoundingClientRect();


            offsetX =
                event.clientX -
                rect.left;


            offsetY =
                event.clientY -
                rect.top;


            event.preventDefault();

        }
    );


    document.addEventListener(
        'mousemove',
        function(event) {

            if (!dragging) {
                return;
            }


            const wrapper =
                document.getElementById(
                    'canvasWrapper'
                );


            const wrapperRect =
                wrapper.getBoundingClientRect();


            let x =
                event.clientX -
                wrapperRect.left -
                offsetX;


            let y =
                event.clientY -
                wrapperRect.top -
                offsetY;


            x =
                Math.max(
                    0,
                    x
                );


            y =
                Math.max(
                    0,
                    y
                );


            element.style.left =
                x + 'px';


            element.style.top =
                y + 'px';


            const card =
                document.querySelector(
                    `.field-card[data-id="${id}"]`
                );


            if (card) {

                card.querySelector(
                    '.field-x'
                ).value =
                    Math.round(x);


                card.querySelector(
                    '.field-y'
                ).value =
                    Math.round(y);

            }

        }
    );


    document.addEventListener(
        'mouseup',
        function() {

            dragging =
                false;


            element.classList.remove(
                'selected'
            );

        }
    );

}


// ============================================================
// REMOVE FIELD
// ============================================================

function removeField(button) {

    const card =
        button.closest(
            '.field-card'
        );


    if (!card) {
        return;
    }


    const id =
        card.dataset.id;


    const preview =
        document.getElementById(
            'preview-' + id
        );


    if (preview) {

        preview.remove();

    }


    card.remove();

}


// ============================================================
// GENERATE FORM
// ============================================================

document
    .getElementById('generateForm')
    .addEventListener(
        'submit',
        function(event) {


            const cards =
                document.querySelectorAll(
                    '.field-card'
                );


            if (cards.length === 0) {

                event.preventDefault();


                alert(
                    'Please add at least one field.'
                );


                return;

            }


            const mappings =
                [];


            cards.forEach(
                function(card) {


                    mappings.push({

                        column:
                            card.querySelector(
                                '.field-column'
                            ).value,


                        font:
                            card.querySelector(
                                '.field-font'
                            ).value,


                        size:
                            parseInt(
                                card.querySelector(
                                    '.field-size'
                                ).value
                            ),


                        color:
                            card.querySelector(
                                '.field-color'
                            ).value,


                        x:
                            parseInt(
                                card.querySelector(
                                    '.field-x'
                                ).value
                            ),


                        y:
                            parseInt(
                                card.querySelector(
                                    '.field-y'
                                ).value
                            )

                    });

                }
            );


            document.getElementById(
                'mappingsInput'
            ).value =
                JSON.stringify(
                    mappings
                );

        }
    );


// ============================================================
// CREATE FIRST FIELD
// ============================================================

addField();

</script>


</body>

</html>
