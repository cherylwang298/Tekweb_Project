<?php
session_start();
require_once "db.php";

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

if(!isset($_SESSION['user_id'])) {
    echo json_encode(["success" => false, "message" => "Unauthorized"]);
    exit;
}


$user_id = $_SESSION['user_id'];


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

$defaultStatus = "reading";
$book_id = $conn->insert_id;

$stmt2 = $conn->prepare("INSERT INTO reading_lists (user_id, book_id, status) VALUES (?, ?, ?)");
$stmt2->bind_param("iis", $user_id, $book_id, $defaultStatus);
$stmt2->execute();

echo json_encode(["success" => true, "id" => $stmt->insert_id]);