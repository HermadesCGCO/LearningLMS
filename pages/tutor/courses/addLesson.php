<?php

include $_SERVER["DOCUMENT_ROOT"] . "/inc/config.php";
include $_SERVER["DOCUMENT_ROOT"] . "/classes/Course.php";

if (empty($_GET["course"]) || empty($_GET["section"])) {
    header("Location: /tutor/courses");
}

?>
