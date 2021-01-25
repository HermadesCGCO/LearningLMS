<?php

define("Class.Course", "");

class Course {

    protected $conn;
    protected $id;

    function __construct($mysql) {
	$this->conn = $mysql;
    }

    public function linkCourse($id) {
	$this->id = $id;
    }

    public function getCourse($courseId=null) {
	$course = [];

	$query = "SELECT id,name,shortDesc,description,thumb,difficulty,date,duration,lastUpdated,students,lessons FROM courses";

	if ($courseId == null && !empty($this->id)) {
	    $query .= " WHERE id=" . $this->id;
	} else {
	    $query .= " WHERE id=" . $courseId;
	}

	$stmt = $this->conn->prepare($query);
	$stmt->execute();

	$stmt->bind_result(
	    $id,
	    $name,
	    $shortDesc,
	    $description,
	    $thumb,
	    $difficulty,
	    $date,
	    $duration,
	    $lastUpdated,
	    $students,
	    $lessons
	);

	while($stmt->fetch()) {
	    $course["id"] = $id;
	    $course["name"] = $name;
	    $course["shortDesc"] = $shortDesc;
	    $course["description"] = $description;
	    $course["thumb"] = $thumb;
	    $course["difficulty"] = $difficulty;
	    $course["date"] = $date;
	    $course["duration"] = $duration;
	    $course["lastUpdated"] = $lastUpdated;
	    $course["students"] = $students;
	    $course["lessons"] = $lessons;
	}

	$stmt->close();

	return $course;

    }

    public function getYouLearn($limit = 0) {
	$things = [];

	$query = "SELECT content FROM courses_youlearn WHERE course=?";

	if ($limit > 0) {
	    $query .= " LIMIT " . $limit;
	}

	$stmt = $this->conn->prepare($query);
	$stmt->bind_param("i", $this->id);
	$stmt->execute();
	$stmt->bind_result($content);
	while ($stmt->fetch()) {
	    $things[] = $content;
	}
	$stmt->close();

	return $things;
    }

}

?>
