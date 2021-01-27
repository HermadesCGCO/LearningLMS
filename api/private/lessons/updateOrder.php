<?php

include $_SERVER["DOCUMENT_ROOT"] . "/inc/config.php";

if (!isset($_SESSION["name"])) {
    die("Necesitas iniciar sesion");
}

$user = new User($conn);
$user->linkUser($_SESSION["name"]);

if (!$user->isUserTutor()) {
    die("Necesitas ser un tutor para realizar este cambio.");
}

$lesson = $_GET["lesson"];
$order = $_GET["order"];

$stmt = $conn->prepare("UPDATE courses_lessons SET showOrder=? WHERE id=?");
$stmt->bind_param("ii", $order, $lesson);
if ($stmt->execute()) {
    $stmt->close();
    echo "1";
} else {
    $stmt->close();
    echo "2";
}

?>
