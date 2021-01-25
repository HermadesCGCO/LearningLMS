<?php

define("Class.Tutor", "");

class Tutor {

    protected $conn;
    protected $name;

    public function __construct($mysql, $name) {
	$this->conn = $mysql;
	$this->name = $name;
    }

    public function getMyCourses($limit = 0) {
	// TODO:
	// Mover esto a la clase "Course" y crear una funcion llamada
	// "getCourseByID" y ejecutar toda esta funcion solo cambiando el query
	// para obtener todos estos datos de un curso cuya id sea la
	// especificada, esta funcion ahora solo devolveria las ids de los
	// cursos del tutor linkeado

	$courses = [];

	$query = "SELECT id FROM courses WHERE tutor=?";

	if ($limit > 0) {
	    $query .= " LIMIT " . $limit;
	}

	$stmt = $this->conn->prepare($query);
	$stmt->bind_param("s", $this->name);
	$stmt->execute();
	$stmt->bind_result($id);
	while ($stmt->fetch()) {
	    $courses[] = $id;
	}
	$stmt->close();

	return $courses;
    }

}

?>
