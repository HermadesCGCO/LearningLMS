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

$id = $_GET["id"];

if (!empty($id)) {
    $stmt = $conn->prepare("UPDATE courses_reviews SET featured='yes' WHERE id=?");
    $stmt->bind_param("i", $id);
    if ($stmt->execute()) {
	$stmt->close();
	echo "1";
    } else {
	$stmt->close();
	echo "0";
    }
}

?>
