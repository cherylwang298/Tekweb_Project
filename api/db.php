<?php
$host = "localhost";
$user = "root";
$pass = "";
$db   = "tekweb_project";

$conn = new mysqli($host, $user, $pass, $db);

if ($conn->connect_error) {
    die(json_encode(["error" => $conn->connect_error]));
}
?>
