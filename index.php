<?php
session_start();

$page = $_GET['page'] ?? 'home';

if (!isset($_SESSION['user_id']) && !in_array($page, ['login', 'create-account'])) {
    header("Location: index.php?page=login");
    exit;
}

switch ($page) {
    case 'login':
        require "pages/auth/login.php";
        break;

    case 'create-account':
        require "pages/auth/create-account.php";
        break;

     case 'profile':
        require "pages/users/profile.php";
        break;

    case 'home':
        require "pages/home.php";
        break;

    case 'my_library':
        require "pages/my_library.php";
        break;

    case 'statistik':
        require "pages/statistik.php";
        break;

    default:
        require "pages/home.php";
}
