<?php

include $_SERVER["DOCUMENT_ROOT"] . "/inc/config.php";
include $_SERVER["DOCUMENT_ROOT"] . "/classes/Lesson.php";

if (!isset($_GET["id"]) || empty($_GET["id"])) {
    die("Por favor introduce una ID valida");
}

$lessonId = $_GET["id"];

$lesson = new Lesson($conn);
$lesson->linkLesson($lessonId);

$courseId = $lesson->getCourse();

if (!isset($_SESSION["name"])) {
    die("Necesitas iniciar sesion");
}

$nextLesson = $lesson->getNextLesson($courseId);
$lesson->linkLesson($nextLesson);
$nextSection = $lesson->getSection();

$progress = $user->getCourseProgress($courseId);

if ($nextLesson > $progress["lesson"]) {
    if ($user->updateCourseProgress($courseId, $nextLesson, $nextSection)) {
	echo $nextLesson;
    } else {
	echo "-1";
    }
}

?>
