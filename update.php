<?php

// Este script se encarga de actualizar la base de datos

// TODO: This

include "inc/config.php";

echo "Actualizando \"courses_lessons\"<br>";
$conn->query("ALTER TABLE courses_lessons ADD showOrder INT(255) NOT NULL AFTER video");

?>
