<?php
require_once "db.php";

$book_id = $_GET["id"] ?? 0;

if (!$book_id) {
    echo json_encode(["error" => "Book ID missing"]);
    exit;
}

$sql = "
    SELECT 
        b.id,
        b.title,
        b.author,
        b.synopsis,
        b.book_cover,
        IFNULL(AVG(r.rating), 0) AS avg_rating
    FROM books b
    LEFT JOIN ratings r ON b.id = r.book_id
    WHERE b.id = ?
    GROUP BY b.id
";

$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $book_id);
$stmt->execute();
$book = $stmt->get_result()->fetch_assoc();

if (!$book) {
    echo json_encode(["error" => "Book not found"]);
    exit;
}

$sqlReviews = "
    SELECT 
        r.rating,
        r.review,
        r.created_at,
        u.username
    FROM ratings r
    JOIN users u ON r.user_id = u.id
    WHERE r.book_id = ?
    ORDER BY r.created_at DESC
";

$stmt2 = $conn->prepare($sqlReviews);
$stmt2->bind_param("i", $book_id);
$stmt2->execute();
$resultReviews = $stmt2->get_result();

$reviews = [];
while ($row = $resultReviews->fetch_assoc()) {
    $reviews[] = $row;
}

$book["reviews"] = $reviews;

// Force numeric values
$book["avg_rating"] = floatval($book["avg_rating"]);

header("Content-Type: application/json");
echo json_encode($book);
