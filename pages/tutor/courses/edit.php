<?php

include $_SERVER["DOCUMENT_ROOT"] . "/inc/config.php";
include $_SERVER["DOCUMENT_ROOT"] . "/classes/Course.php";

if (!isset($_GET["id"]) || empty($_GET["id"])) {
    header("Location: /tutor/courses");
}

$id = $_GET["id"];

$course = new Course($conn);
$course->linkCourse($id);

$pageTitle = " - Editar";

?>
