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

$course = $_GET["course"];
$content = $_GET["content"];

$stmt = $conn->prepare("INSERT INTO courses_youlearn(course, content) VALUES(?,?)");
$stmt->bind_param("is", $course, $content);
if ($stmt->execute()) {
    $stmt->close();

    $stmt = $conn->prepare("SELECT id FROM courses_youlearn WHERE course=? ORDER BY id DESC LIMIT 1");
    $stmt->bind_param("i", $course);
    $stmt->execute();
    $stmt->bind_result($createdId);
    $stmt->fetch();
    $stmt->close();

    echo $createdId;
} else {
    $stmt->close();
    echo "Error";
}

?>
