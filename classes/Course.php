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

    public function getLastLessonOrder($sectionId, $courseId=null) {
	$course = $this->id;

	if ($courseId != null) {
	    $course = $courseId;
	}

	$stmt = $this->conn->prepare("SELECT showOrder FROM courses_lessons WHERE sectionId=? AND courseId=? ORDER BY id DESC LIMIT 1");
	$stmt->bind_param("ii", $sectionId, $course);
	$stmt->execute();
	$stmt->store_result();
	$stmt->bind_result($order);
	$stmt->fetch();

	if ($stmt->num_rows == 0) {
	    $order = -1;
	}
	$stmt->close();

	$order += 1;

	return $order;
    }

    public function getCourse($courseId=null) {
	$course = [];

	$query = "SELECT id,name,shortDesc,description,thumb,difficulty,category,duration,lastUpdated,students,lessons,tutor FROM courses";

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
	    $lessons,
	    $tutor
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
	$course["tutor"] = $tutor;

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

    public function createSection($name, $courseId=null) {
	$course = $this->id;

	if ($courseId != null) {
	    $course = $courseId;
	}

	$stmt = $this->conn->prepare("INSERT INTO courses_sections(name,courseId) VALUES(?,?)");
	$stmt->bind_param("si", $name, $course);
	if ($stmt->execute()) {
	    $stmt->close();
	    return true;
	} else {
	    return [$this->conn->error];
	}
    }

    public function getSections($courseId=null) {
	$sections = [];

	$course = $this->id;

	if ($courseId != null) {
	    $course = $courseId;
	}

	$stmt = $this->conn->prepare("SELECT id,name FROM courses_sections WHERE courseId=? ORDER BY id ASC");
	$stmt->bind_param("i", $course);
	$stmt->execute();
	$stmt->bind_result($id, $name);
	$i = 0;

	while ($stmt->fetch()) {
	    $sections[$i]["id"] = $id;
	    $sections[$i]["name"] = $name;

	    $i++;
	}

	$stmt->close();

	return $sections;
    }

    public function getIdFromSection($sectionId) {
	$stmt = $this->conn->prepare("SELECT courseId FROM courses_sections WHERE id=? LIMIT 1");
	$stmt->bind_param("i", $sectionId);
	$stmt->execute();
	$stmt->bind_result($courseId);
	$stmt->fetch();
	$stmt->close();

	return $courseId;
    }

    public function addLesson($data, $sectionId, $courseId=null) {
	$course = $this->id;

	if ($courseId != null) {
	    $course = $courseId;
	}

	$canProceed = 1;

	$order = $this->getLastLessonOrder($sectionId);

	$stmt = $this->conn->prepare("INSERT INTO courses_lessons(name,content,video,showOrder,sectionId,courseId) VALUES(?,?,?,?,?,?)");
	$stmt->bind_param("sssiii", $data["name"], $data["content"], $data["video"], $order, $sectionId, $course);
	if ($stmt->execute()) {
	    $stmt->close();
	} else {
	    $canProceed = 0;
	}

	if ($canProceed) {
	    // Si la lección se creo correctamente, actualizar el "lastUpdated"
	    // del curso.

	    $date = date("m/Y");

	    $stmt = $this->conn->prepare("UPDATE courses SET lastUpdated=?,lessons=lessons+1 WHERE id=?");
	    $stmt->bind_param("si", $date, $course);
	    if ($stmt->execute()) {
		$stmt->close();
		return 1;
	    } else {
		$stmt->close();
		return 0;
	    }
	}

	return $canProceed;
    }

    public function getLessonsFromSection($sectionId, $depth=1, $courseId=null) {
	// La variable depth determina cuantas cosas se obtienen de la base de
	// datos, con su valor por defecto "1" solamente se obtiene el id y el
	// nombre, si su valor es "2" se obtiene el nombre, el contenido y el
	// video.

	$lessons = [];

	$course = $this->id;

	if ($courseId != null) {
	    $course = $courseId;
	}

	$select = "id,name";

	if ($select == 2) {
	    $select .= ",content,video";
	}

	$stmt = $this->conn->prepare("SELECT " . $select . " FROM courses_lessons WHERE sectionId=? AND courseId=? ORDER BY showOrder ASC");
	$stmt->bind_param("ii", $sectionId, $course);
	$stmt->execute();
	if ($depth == 1) {
	    $stmt->bind_result($id, $name);
	} else if ($depth == 2) {
	    $stmt->bind_result($id, $name, $content, $video);
	}

	$i = 0;
	while ($stmt->fetch()) {
	    $lessons[$i]["id"] = $id;
	    $lessons[$i]["name"] = $name;

	    if ($depth == 2) {
		$lessons[$i]["content"] = $content;
		$lessons[$i]["video"] = $video;
	    }

	    $i++;
	}
	$stmt->close();

	return $lessons;
    }

    public function getFeaturedCourses($limitCourses=0) {
	$courses = [];
	$limit = "";

	if ($limitCourses > 0) {
	    $limit = " LIMIT " . $limitCourses;
	}

	$stmt = $this->conn->prepare("SELECT id FROM courses WHERE featured='yes'" . $limit);
	$stmt->execute();
	$stmt->bind_result($id);
	while ($stmt->fetch()) {
	    $courses[] = $id;
	}
	$stmt->close();

	return $courses;
    }

    public function deleteCourse($courseId=null) {
	$course = $this->id;

	if ($courseId != null) {
	    $course = $courseId;
	}

	// Eliminar curso
	$stmt = $this->conn->prepare("DELETE FROM courses WHERE id=?");
	$stmt->bind_param("i", $course);
	$stmt->execute();
	$stmt->close();

	// Eliminar secciones
	$sections = $this->getSections();

	for ($i = 0; $i < sizeof($sections); $i++) {
	    $stmt = $this->conn->prepare("DELETE FROM courses_sections WHERE id=?");
	    $stmt->bind_param("i", $sections[$i]["id"]);
	    $stmt->execute();
	    $stmt->close();

	    // Eliminar lecciones
	    $lessons = $this->getLessonsFromSection($sections[$i]["id"]);

	    for ($j = 0; $j < sizeof($lessons); $j++) {
		$stmt = $this->conn->prepare("DELETE FROM courses_lessons WHERE id=?");
		$stmt->bind_param("i", $lessons[$j]["id"]);
		$stmt->execute();
		$stmt->close();
	    }
	}

	return true;
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
