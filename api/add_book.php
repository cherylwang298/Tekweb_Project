<?php
require_once "db.php";

// Input
$title = $_POST['title'];
$author = $_POST['author'];
$synopsis = $_POST['synopsis'];

$book_cover = null;

if (isset($_FILES['book_cover']) && $_FILES['book_cover']['error'] === 0) {

    // SERVER PATH (fisik)
    $uploadDirServer = __DIR__ . "/../uploads/covers/";

    // PATH UNTUK DATABASE & BROWSER
    $uploadDirDB = "/uploads/covers/";

    if (!is_dir($uploadDirServer)) {
        mkdir($uploadDirServer, 0777, true);
    }

    $fileTmp  = $_FILES['book_cover']['tmp_name'];
    $fileName = time() . "_" . basename($_FILES['book_cover']['name']);

    move_uploaded_file($fileTmp, $uploadDirServer . $fileName);

    // YANG DISIMPAN KE DB
    $book_cover = $uploadDirDB . $fileName;
}

// Cek duplikasi
$check = $conn->prepare("SELECT id FROM books WHERE title=? AND author=?");
$check->bind_param("ss", $title, $author);
$check->execute();
$check->store_result();

if ($check->num_rows > 0) {
    echo json_encode(["success" => false, "message" => "Book already exists"]);
    exit;
}

$stmt = $conn->prepare("INSERT INTO books (title, author, synopsis, book_cover) VALUES (?, ?, ?, ?)");
$stmt->bind_param("ssss", $title, $author, $synopsis, $book_cover);
$stmt->execute();

echo json_encode(["success" => true, "id" => $stmt->insert_id]);