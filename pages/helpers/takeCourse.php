<?php

include $_SERVER["DOCUMENT_ROOT"] . "/inc/config.php";
include $_SERVER["DOCUMENT_ROOT"] . "/classes/Course.php";

if (!isset($_SESSION["name"])) {
    header("Location: /login");
    exit();
}

if ($user->isUserEnroledInCourse($_GET["id"])) {
    header("Location: /course/" . $_GET["id"]);
    exit();
}

$course = new Course($conn);
$course->linkCourse($_GET["id"]);

if (!$course->courseExists()) {
    header("Location: /explore");
    exit();
}

$firstLesson = $course->getFirstLesson();

if ($user->enrolInCourse($_GET["id"], $firstLesson["id"], $firstLesson["section"])) {
    header("Location: /course/" . $_GET["id"]);
    exit();
} else {
    echo "Ha habido un problema, por favor intentalo mas tarde";
}

?>
