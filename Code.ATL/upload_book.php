<?php
@ini_set('upload_max_filesize', '50M');
@ini_set('post_max_size', '55M');
@ini_set('memory_limit', '256M');

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['success' => false, 'message' => 'Invalid request method.']);
    exit;
}

$donorName   = trim($_POST['donorName'] ?? '');
$title       = trim($_POST['title'] ?? '');
$genre       = trim($_POST['genre'] ?? 'General');
$description = trim($_POST['description'] ?? '');

if (empty($donorName) || empty($title)) {
    echo json_encode(['success' => false, 'message' => 'Donor name and book title are required.']);
    exit;
}

if (!isset($_FILES['pdf']) || $_FILES['pdf']['error'] !== UPLOAD_ERR_OK) {
    $errCode = $_FILES['pdf']['error'] ?? 'missing';
    echo json_encode(['success' => false, 'message' => "PDF upload failed with code: $errCode. Please check file size limits."]);
    exit;
}

// Ensure Upload Folders Exist
$uploadDirCovers = __DIR__ . '/uploads/covers/';
$uploadDirPdfs   = __DIR__ . '/uploads/pdfs/';

if (!is_dir($uploadDirCovers)) {
    mkdir($uploadDirCovers, 0777, true);
}
if (!is_dir($uploadDirPdfs)) {
    mkdir($uploadDirPdfs, 0777, true);
}

// Save PDF File
$pdfFile     = $_FILES['pdf'];
$pdfExt      = strtolower(pathinfo($pdfFile['name'], PATHINFO_EXTENSION));

if ($pdfExt !== 'pdf') {
    echo json_encode(['success' => false, 'message' => 'File must be a valid PDF document.']);
    exit;
}

$pdfFilename   = 'pdf_' . time() . '_' . uniqid() . '.pdf';
$pdfTargetPath = $uploadDirPdfs . $pdfFilename;

if (!move_uploaded_file($pdfFile['tmp_name'], $pdfTargetPath)) {
    echo json_encode(['success' => false, 'message' => 'Failed to save PDF to uploads/pdfs folder.']);
    exit;
}

// Save Cover File if Provided
$coverRelPath = 'assets/images/ack/ancestors-of-rama.png';
if (isset($_FILES['cover']) && $_FILES['cover']['error'] === UPLOAD_ERR_OK) {
    $coverFile = $_FILES['cover'];
    $coverExt  = strtolower(pathinfo($coverFile['name'], PATHINFO_EXTENSION));
    $allowed   = ['jpg', 'jpeg', 'png', 'webp'];
    
    if (in_array($coverExt, $allowed)) {
        $coverFilename   = 'cover_' . time() . '_' . uniqid() . '.' . $coverExt;
        $coverTargetPath = $uploadDirCovers . $coverFilename;
        
        if (move_uploaded_file($coverFile['tmp_name'], $coverTargetPath)) {
            $coverRelPath = 'uploads/covers/' . $coverFilename;
        }
    }
}

$pdfRelPath = 'uploads/pdfs/' . $pdfFilename;

// Ensure Data Directory Exists
$dataDir = __DIR__ . '/data';
if (!is_dir($dataDir)) {
    mkdir($dataDir, 0777, true);
}

$jsonFile     = $dataDir . '/donated_books.json';
$donatedBooks = [];

if (file_exists($jsonFile)) {
    $content      = file_get_contents($jsonFile);
    $donatedBooks = json_decode($content, true) ?: [];
}

// Build Book Structure
$newBook = [
    "id"          => "donated_" . time(),
    "title"       => $title,
    "author"      => "Donated by " . $donorName,
    "genre"       => $genre,
    "category"    => "donated",
    "cover"       => $coverRelPath,
    "pdf"         => $pdfRelPath,
    "rating"      => "5.0",
    "description" => $description ?: "Donated by $donorName to ATL Digital Library.",
    "badge"       => "🎁 Donated Book",
    "badgeColor"  => "green",
    "isDonated"   => true
];

array_unshift($donatedBooks, $newBook);

// Write to JSON
if (file_put_contents($jsonFile, json_encode($donatedBooks, JSON_PRETTY_PRINT))) {
    echo json_encode([
        'success' => true,
        'message' => 'Book uploaded successfully to data/donated_books.json',
        'book'    => $newBook
    ]);
} else {
    echo json_encode([
        'success' => false, 
        'message' => 'Permission denied while writing to data/donated_books.json.'
    ]);
}
