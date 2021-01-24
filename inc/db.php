<?php

$conn = mysqli_connect(
    $_CONFIG["DB_HOST"],
    $_CONFIG["DB_USER"],
    $_CONFIG["DB_PASS"],
    $_CONFIG["DB_NAME"]
);

if (!$conn) {
    die("There's been an error trying to connect to the database");
}

?>
