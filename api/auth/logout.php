<?php
session_start();
session_unset();
session_destroy();

header("Location: /tekweb_project/pages/auth/login.php");
exit;
?>