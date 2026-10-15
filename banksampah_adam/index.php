<?php
session_start();

if (!isset($_SESSION["login"])) {
    header("Location: auth/login.php");
    exit;
}

switch ($_SESSION["role"]) {
    case "admin":
        header("Location: admin/index.php");
        break;

    case "pengepul":
        header("Location: pengepul/index.php");
        break;

    default:
        header("Location: user/index.php");
        break;
}
exit;