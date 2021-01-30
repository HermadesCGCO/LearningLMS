<?php

ob_flush();
session_start();

$_CONFIG["SITE_LANGUAGE"] = "es";
$_CONFIG["SITE_TITLE"] = "Delta Learning";
$_CONFIG["SITE_PRODUCTION"] = false;

$_CONFIG["DB_HOST"] = "localhost";
$_CONFIG["DB_USER"] = "root";
$_CONFIG["DB_PASS"] = "";
$_CONFIG["DB_NAME"] = "delta";

if (!$_CONFIG["SITE_PRODUCTION"]) {
    ini_set("display_errors", 1);
    ini_set("display_startup_errors", 1);
    error_reporting(E_ALL);
}

include "db.php";

if (isset($_SESSION["name"]) && !defined("Class.User")) {
    include $_SERVER["DOCUMENT_ROOT"] . "/classes/User.php";

    $user = new User($conn);
    $user->linkUser($_SESSION["name"]);
}

?>
