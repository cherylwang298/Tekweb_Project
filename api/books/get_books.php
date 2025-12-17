<?php
require_once "../../config/koneksi.php";

$sql = "SELECT 
            b.id,
            b.title,
            b.author,
            b.synopsis,
            b.book_cover,
            IFNULL(AVG(r.rating), 0) AS avg_rating
        FROM books b
        LEFT JOIN ratings r ON r.book_id = b.id
        GROUP BY b.id
        ORDER BY b.created_at DESC";

$result = $conn->query($sql);

$books = [];
while ($row = $result->fetch_assoc()) {
    $books[] = $row;
}

echo json_encode($books);   
?>