<?php

if (!isset($user) || (isset($user) && !$user->isUserTutor())) {
    header("Location: /");
}

if (!isset($noIncludeTutor)) {
    include $_SERVER["DOCUMENT_ROOT"] . "/classes/Tutor.php";
}

$tutor = new Tutor($conn, $_SESSION["name"]);

include $_SERVER["DOCUMENT_ROOT"] . "/inc/head.php";

?>
