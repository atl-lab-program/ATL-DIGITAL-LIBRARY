<?php
?>
<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Certificate Generator</title>

    <style>

        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            font-family: Arial, sans-serif;
            background: #f1f5f9;
            color: #1e293b;
        }

        .container {
            max-width: 750px;
            margin: 60px auto;
            padding: 20px;
        }

        .card {
            background: white;
            padding: 40px;
            border-radius: 14px;
            box-shadow: 0 5px 25px rgba(0,0,0,0.08);
        }

        h1 {
            margin-top: 0;
            font-size: 30px;
        }

        .subtitle {
            color: #64748b;
            line-height: 1.6;
            margin-bottom: 30px;
        }

        .form-group {
            margin-bottom: 25px;
        }

        label {
            display: block;
            font-weight: bold;
            margin-bottom: 8px;
        }

        input[type="file"] {
            width: 100%;
            padding: 12px;
            border: 1px solid #cbd5e1;
            border-radius: 7px;
            background: #f8fafc;
        }

        .help {
            font-size: 13px;
            color: #64748b;
            margin-top: 6px;
        }

        button {
            width: 100%;
            padding: 15px;
            border: none;
            border-radius: 8px;
            background: #2563eb;
            color: white;
            font-size: 17px;
            cursor: pointer;
        }

        button:hover {
            background: #1d4ed8;
        }

    </style>

</head>

<body>

<div class="container">

    <div class="card">

        <h1>Certificate Generator</h1>

        <p class="subtitle">
            Upload your blank certificate and an Excel spreadsheet.
            You will then be able to place student information onto
            the certificate and generate a PDF containing all certificates.
        </p>


        <form
            action="editor.php"
            method="POST"
            enctype="multipart/form-data"
        >

            <div class="form-group">

                <label for="template_image">
                    Certificate Template
                </label>

                <input
                    type="file"
                    id="template_image"
                    name="template_image"
                    accept=".jpg,.jpeg,.png"
                    required
                >

                <div class="help">
                    Upload the blank certificate design.
                </div>

            </div>


            <div class="form-group">

                <label for="excel_file">
                    Excel Spreadsheet
                </label>

                <input
                    type="file"
                    id="excel_file"
                    name="excel_file"
                    accept=".xlsx,.xls,.csv"
                    required
                >

                <div class="help">
                    The first row should contain column names such as
                    Student Name, Prize, School, etc.
                </div>

            </div>


            <button type="submit">
                Open Certificate Editor →
            </button>

        </form>

    </div>

</div>

</body>
</html>
