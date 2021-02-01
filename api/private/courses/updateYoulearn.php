<?php

// Este archivo se encarga de actualizar un "youlearn" en la base de datos

include $_SERVER["DOCUMENT_ROOT"] . "/inc/config.php";

if (!isset($_SESSION["name"])) {
    die("Necesitas iniciar sesion");
}

$user = new User($conn);
$user->linkUser($_SESSION["name"]);

if (!$user->isUserTutor()) {
    die("Necesitas ser un tutor para realizar este cambio.");
}

$id = $_GET["id"];
$content = htmlspecialchars($_GET["content"]);

$stmt = $conn->prepare("UPDATE courses_youlearn SET content=? WHERE id=?");
$stmt->bind_param("si", $content, $id);
if ($stmt->execute()) {
    $stmt->close();
    echo "1";
} else {
    $stmt->close();
    echo "0";
}

?>
