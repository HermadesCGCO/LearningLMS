<?php

define("Class.Course", "");

// Esta clase contiene muchos metodos utiles para manipular, actualizar e
// incluso crear cursos

class Course {

    protected $conn;
    protected $id;

    function __construct($mysql) {
	// Inicializador de la clase.

	$this->conn = $mysql;
    }

    public function linkCourse($id) {
	// Enlazamos la ID de un curso a esta clase para asi evitar pasar la ID
	// de un curso cada vez que llamamos a un metodo de esta clase.

	$this->id = $id;
    }

    public function courseExists($courseId=null) {
	// Chequea si un curso ya existe.

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
	// Obtiene el ultimo "showOrder" de una seccion. Util al momento de
	// crear nuevas lecciones.

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
	// Obtiene toda la informacion util de un curso.

	$course = [];

	$query = "SELECT id,name,shortDesc,description,thumb,difficulty,category,duration,rating,ratings,lastUpdated,students,lessons,tutor FROM courses";

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
	    $rating,
	    $ratings,
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
	$course["rating"] = $rating;
	$course["ratings"] = $ratings;
	$course["lastUpdated"] = $lastUpdated;
	$course["students"] = $students;
	$course["lessons"] = $lessons;
	$course["tutor"] = $tutor;

	$stmt->close();

	return $course;

    }

    public function createCourse($data, $tutor) {
	// Crea un curso.

	$lastUpdated = date("m/Y");

	$name = htmlspecialchars($data["name"]);
	$shortDesc = htmlspecialchars($data["shortDesc"]);
	$description = htmlspecialchars($data["description"]);
	$thumb = htmlspecialchars($data["thumb"]);

	$stmt = $this->conn->prepare("INSERT INTO courses(name,shortDesc,description,thumb,difficulty,category,duration,lastUpdated,tutor) VALUES(?,?,?,?,?,?,?,?,?)");
	$stmt->bind_param("ssssssiss",
			  $name,
			  $shortDesc,
			  $description,
			  $thumb,
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
			      $name,
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
	// Actualiza la informacion de un curso.

	$toUpdateId = $this->id;

	if ($courseId != null) {
	    $toUpdateId = $courseId;
	}

	$lastUpdated = date("m/Y");

	$name = htmlspecialchars($data["name"]);
	$shortDesc = htmlspecialchars($data["shortDesc"]);
	$description = htmlspecialchars($data["description"]);
	$thumb = htmlspecialchars($data["thumb"]);


	$query = "UPDATE courses SET name=?,shortDesc=?,description=?,thumb=?,difficulty=?,category=?,duration=?,lastUpdated=? WHERE id=?";

	$stmt = $this->conn->prepare($query);
	$stmt->bind_param("ssssssisi",
			  $name,
			  $shortDesc,
			  $description,
			  $thumb,
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
	// Obtiene todos los "youlearn" de un curso en la base de datos.

	$things = [];
	$i = 0;

	$query = "SELECT id,content FROM courses_youlearn WHERE course=?";

	if ($limit > 0) {
	    $query .= " LIMIT " . $limit;
	}

	$stmt = $this->conn->prepare($query);
	$stmt->bind_param("i", $this->id);
	$stmt->execute();
	$stmt->bind_result($id, $content);
	while ($stmt->fetch()) {
	    $things[$i]["id"] = $id;
	    $things[$i]["content"] = $content;

	    $i++;
	}
	$stmt->close();

	return $things;
    }

    public function createSection($name, $courseId=null) {
	// Crea una nueva seccion.

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
	// Obtiene todas las secciones de un curso.

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
	// Obtiene el ID de un curso por medio del ID de una seccion.

	$stmt = $this->conn->prepare("SELECT courseId FROM courses_sections WHERE id=? LIMIT 1");
	$stmt->bind_param("i", $sectionId);
	$stmt->execute();
	$stmt->bind_result($courseId);
	$stmt->fetch();
	$stmt->close();

	return $courseId;
    }

    public function addLesson($data, $sectionId, $courseId=null) {
	// Crea una nueva leccion en un curso.

	$course = $this->id;

	if ($courseId != null) {
	    $course = $courseId;
	}

	$canProceed = 1;

	$order = $this->getLastLessonOrder($sectionId);

	$name = htmlspecialchars($data["name"]);
	$content = htmlspecialchars($data["content"]);
	$video = htmlspecialchars($data["video"]);

	$stmt = $this->conn->prepare("INSERT INTO courses_lessons(name,content,video,showOrder,sectionId,courseId) VALUES(?,?,?,?,?,?)");
	$stmt->bind_param("sssiii",
			  $name,
			  $content,
			  $video,
			  $order,
			  $sectionId,
			  $course);
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
	// Obtiene las lecciones de una seccion.

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
	// Obtiene los cursos destacados (se puede pasar un limite de cursos).

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

    public function getPopularCourses($limitCourses=0) {
	// Obtiene los cursos mas populares (con mas estudiantes).

	$courses = [];
	$limit = "";

	if ($limitCourses > 0) {
	    $limit = " LIMIT " . $limitCourses;
	}

	$stmt = $this->conn->prepare("SELECT id FROM courses ORDER BY students DESC" . $limit);
	$stmt->execute();
	$stmt->bind_result($id);
	while ($stmt->fetch()) {
	    $courses[] = $id;
	}
	$stmt->close();

	return $courses;
    }

    public function getCoursesFromCategory($category, $limitCourses=0) {
	// Obtiene los cursos que pertenezcan a la categoria especificada.

	$courses = [];
	$limit = "";

	if ($limitCourses > 0) {
	    $limit = " LIMIT " . $limitCourses;
	}

	$stmt = $this->conn->prepare("SELECT id FROM courses WHERE category=?" . $limit);
	$stmt->bind_param("s", $category);
	$stmt->execute();
	$stmt->bind_result($id);
	while ($stmt->fetch()) {
	    $courses[] = $id;
	}
	$stmt->close();

	return $courses;
    }

    public function deleteCourse($courseId=null) {
	// Elimina un curso junto con sus secciones y lecciones.

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

    public function getFirstLesson($courseId=null) {
	// Obtiene la primera leccion de un curso.

	$course = $this->id;

	if ($courseId != null) {
	    $course = $courseId;
	}

	$stmt = $this->conn->prepare("SELECT id,sectionID FROM courses_lessons WHERE courseId=? ORDER BY id ASC LIMIT 1");
	$stmt->bind_param("i", $course);
	$stmt->execute();
	$stmt->bind_result($id, $section);
	$stmt->fetch();
	$stmt->close();

	return array(
	    "id" => $id,
	    "section" => $section
	);
    }

    public function getFeaturedReviews($limit=0, $courseId=null) {
	// Obtiene las calificaciones destacadas de un curso.

	$course = $this->id;

	$reviews = [];
	$i = 0;

	if ($courseId != null) {
	    $course = $courseId;
	}

	$stmt = $this->conn->prepare("SELECT student,content,stars FROM courses_reviews WHERE featured='yes' AND course=?");
	$stmt->bind_param("i", $course);
	$stmt->execute();
	$stmt->bind_result($student, $content, $stars);
	while ($stmt->fetch()) {
	    $reviews[$i]["student"] = $student;
	    $reviews[$i]["content"] = $content;
	    $reviews[$i]["stars"] = $stars;

	    $i++;
	}
	$stmt->close();


	return $reviews;
    }

    public function getRating($courseId=null) {
	// Obtiene el "rating" o calificación de un curso.

	$course = $this->id;

	$numReviews = 0;
	$rating = 0;

	if ($courseId != null) {
	    $course = $courseId;
	}

	$stmt = $this->conn->prepare("SELECT stars FROM courses_reviews WHERE course=?");
	$stmt->bind_param("i", $course);
	$stmt->execute();
	$stmt->bind_result($stars);
	while ($stmt->fetch()) {
	    $rating += $stars;
	    $numReviews++;
	}
	$stmt->close();

	if ($numReviews == 0) {
	    $total = 0;
	} else {
	    $total = $rating / $numReviews;
	}

	return array(
	    "numReviews" => $numReviews,
	    "rating" => $rating,
	    "totalRating" => $total
	);
    }

    public function getRatings($limit=0, $courseId=null) {
	// Obtiene los ratings o calificaciones de un curso. Ten en cuenta que
	// de igual manera puedes especificar un limite a la cantidad de
	// calificaciones obtenidas.

	$course = $this->id;

	$ratings = [];
	$i = 0;

	if ($courseId != null) {
	    $course = $courseId;
	}

	$query = "SELECT student,content,stars FROM courses_reviews WHERE course=? ORDER BY id DESC";

	if ($limit > 0) {
	    $query .= " LIMIT " . $limit;
	}

	$stmt = $this->conn->prepare($query);
	$stmt->bind_param("i", $course);
	$stmt->execute();
	$stmt->bind_result($student, $content, $stars);
	while ($stmt->fetch()) {
	    $ratings[$i]["student"] = $student;
	    $ratings[$i]["content"] = $content;
	    $ratings[$i]["stars"] = $stars;

	    $i++;
	}
	$stmt->close();

	return $ratings;
    }

    public function getRandomReview($courseId=null) {
	// Obtiene una calificación aleatoria de un curso.

	$toGet = $this->id;

	if ($courseId != null) {
	    $toGet = $courseId;
	}

	$stmt = $this->conn->prepare("SELECT id,student,content,stars FROM courses_reviews WHERE featured='no' AND course=? AND id >= RAND() * (SELECT MAX(id) FROM courses_reviews) LIMIT 1");
	$stmt->bind_param("i", $toGet);
	$stmt->execute();
	$stmt->store_result();

	if ($stmt->num_rows == 0) {
	    return [];
	}

	$stmt->bind_result($id, $student, $content, $stars);
	$stmt->fetch();
	$stmt->close();

	return array(
	    "id" => $id,
	    "student" => $student,
	    "content" => $content,
	    "stars" => $stars
	);
    }

    public function getCategories() {
	// Devuelve todas las categorias disponibles.

	return [
	    "Hacking",
	    "Programación",
	    "Desarrollo web",
	    "Linux",
	    "Desarrollo aplicaciones móviles"
	];
    }

    public function getDifficulties() {
	// Devuelve todas las "dificultades" o niveles disponibles.

	return [
	    "Principiantes",
	    "Intermedio",
	    "Avanzado"
	];
    }

}

?>
