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

	$query = "SELECT name,shortDesc,description,thumb,date,lastUpdated,students FROM courses WHERE tutor=?";

	if ($limit > 0) {
	    $query .= " LIMIT " . $limit;
	}

	$stmt = $this->conn->prepare($query);
	$stmt->bind_param("s", $this->name);
	$stmt->execute();

	$stmt->bind_result(
	    $name,
	    $shortDesc,
	    $description,
	    $thumb,
	    $date,
	    $lastUpdated,
	    $students
	);

	$i = 0;

	while($stmt->fetch()) {
	    $courses[$i]["name"] = $name;
	    $courses[$i]["shortDesc"] = $shortDesc;
	    $courses[$i]["description"] = $description;
	    $courses[$i]["thumb"] = $thumb;
	    $courses[$i]["date"] = $date;
	    $courses[$i]["lastUpdated"] = $lastUpdated;
	    $courses[$i]["students"] = $students;

	    $i++;
	}

	$stmt->close();

	return $courses;
    }

}

?>
