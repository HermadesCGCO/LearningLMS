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

    public function courseExists($courseId=null) {
	$id = $this->id;

	if ($courseId != null){
	    $id = $courseId;
	}

	$stmt = $this->conn->prepare("SELECT id FROM courses WHERE id=?");
	$stmt->bind_param("i", $id);
	$stmt->execute();
	$stmt->store_result();
	$total = ($stmt->num_rows > 0) ? true : false;
	$stmt->close();

	return $total;
    }

    public function getCourse($courseId=null) {
	$course = [];

	$query = "SELECT id,name,shortDesc,description,thumb,difficulty,category,duration,lastUpdated,students,lessons FROM courses";

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
	    $category,
	    $duration,
	    $lastUpdated,
	    $students,
	    $lessons
	);

	$stmt->fetch();

	$course["id"] = $id;
	$course["name"] = $name;
	$course["shortDesc"] = $shortDesc;
	$course["description"] = $description;
	$course["thumb"] = $thumb;
	$course["difficulty"] = $difficulty;
	$course["category"] = $category;
	$course["duration"] = $duration;
	$course["lastUpdated"] = $lastUpdated;
	$course["students"] = $students;
	$course["lessons"] = $lessons;

	$stmt->close();

	return $course;

    }

    public function createCourse($data, $tutor) {
	$lastUpdated = date("m/Y");

	$stmt = $this->conn->prepare("INSERT INTO courses(name,shortDesc,description,thumb,difficulty,category,duration,lastUpdated,tutor) VALUES(?,?,?,?,?,?,?,?,?)");
	$stmt->bind_param("ssssssiss",
			  $data["name"],
			  $data["shortDesc"],
			  $data["description"],
			  $data["thumb"],
			  $data["difficulty"],
			  $data["category"],
			  $data["duration"],
			  $lastUpdated,
			  $tutor
	);

	if ($stmt->execute()) {
	    $stmt->close();

	    $stmt = $this->conn->prepare("SELECT id FROM courses WHERE name=? AND tutor=? ORDER BY id DESC LIMIT 1");
	    $stmt->bind_param("ss",
			      $data["name"],
			      $tutor
	    );
	    $stmt->execute();
	    $stmt->bind_result($id);
	    $stmt->fetch();
	    $stmt->close();

	    return $id;
	} else {
	    return [$this->conn->error];
	    $stmt->close();
	}
    }

    public function updateCourse($data, $courseId=null) {
	$toUpdateId = $this->id;

	if ($courseId != null) {
	    $toUpdateId = $courseId;
	}

	$lastUpdated = date("m/Y");

	$query = "UPDATE courses SET name=?,shortDesc=?,description=?,thumb=?,difficulty=?,category=?,duration=?,lastUpdated=? WHERE id=?";

	$stmt = $this->conn->prepare($query);
	$stmt->bind_param("ssssssisi",
			  $data["name"],
			  $data["shortDesc"],
			  $data["description"],
			  $data["thumb"],
			  $data["difficulty"],
			  $data["category"],
			  $data["duration"],
			  $lastUpdated,
			  $toUpdateId);
	if ($stmt->execute()) {
	    $stmt->close();
	    return true;
	} else {
	    return [$this->conn->error];
	    $stmt->close();
	}
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

    public function getCategories() {
	return [
	    "Hacking",
	    "Programación",
	    "Desarrollo web",
	    "Linux",
	    "Desarrollo aplicaciones móviles"
	];
    }

    public function getDifficulties() {
	return [
	    "Principiantes",
	    "Intermedio",
	    "Avanzado"
	];
    }

}

?>
