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
