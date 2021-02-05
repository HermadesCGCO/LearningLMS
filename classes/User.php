<?php

define("Class.User", "");

// Esta clase contiene muchos metodos utiles al momento de crear, modificar,
// eliminar, actualizar u obtener informacion de algun usuario.

class User {

    protected $conn;
    protected $user;

    public $errors;

    function __construct($db) {
	// inicializacion de la clase.

	$this->conn = $db;

	$this->errors = array();
    }

    function linkUser($username) {
	// Enlaza un usuario con esta clase, para no pasar su nombre cada vez
	// que llamamos un metodo.

	$this->user = $username;
    }

    function createUser($data) {
	// Crea un usuario. Ten en cuenta que esto encripta las contrasenas y
	// chequea que ni el E-mail o el nombre introducido ya existan.

	if (
	    (!isset($data["name"]) || empty($data["name"])) ||
	    (!isset($data["lastname"]) || empty($data["lastname"])) ||
	    (!isset($data["email"]) || empty($data["email"])) ||
	    (!isset($data["pass1"]) || empty($data["pass1"])) ||
	    (!isset($data["pass2"]) || empty($data["pass2"]))
	) {
	    $this->errors[] = "Por favor rellena todos los campos";
	    return false;
	}

	if ($data["pass1"] !== $data["pass2"]) {
	    $this->errors[] = "Las contraseñas no coinciden";
	    return false;
	}

	$finalPass = password_hash($data["pass1"], PASSWORD_BCRYPT);
	$date = date("d-m-Y H:i:s");

	$canProceed = 1;

	if ($this->emailExists($data["email"])) {
	    $canProceed = 0;
	    $this->errors[] = "El email ingresado ya existe";
	}

	if ($this->nameExists($data["name"])) {
	    $canProceed = 0;
	    $this->errors[] = "El nombre ingresado ya existe";
	}

	if ($canProceed) {

	    $name = htmlspecialchars($data["name"]);
	    $lastname = htmlspecialchars($data["lastname"]);
	    $email = htmlspecialchars($data["email"]);

	    $email = strtolower($email);

	    $stmt = $this->conn->prepare("INSERT INTO users(name, lastname, email, password, registrationDate) VALUES(?, ?, ?, ?, ?)");
	    $stmt->bind_param("sssss",
			      $name,
			      $lastname,
			      $email,
			      $finalPass,
			      $date);

	    if ($stmt->execute()) {
		$stmt->close();
		return true;
	    } else {
		$stmt->close();

		$this->errors[] = "Ha habido un problema al insertar tu usuario en nuestra base de datos";
		return false;
	    }
	}
    }

    protected function emailExists($email) {
	// Chequea si una direccion de correo electronico ya existe. Ten en
	// cuenta que este metodo solo puede ser accedido por esta clase o
	// derivados.

	$stmt = $this->conn->prepare("SELECT id FROM users WHERE email=?");
	$stmt->bind_param("s", $email);
	$stmt->execute();
	$stmt->store_result();
	if ($stmt->num_rows > 0) {
	    $stmt->close();
	    return true;
	}

	$stmt->close();
	return false;
    }

    protected function nameExists($name) {
	// Chequea si un nombre de usuario ya existe. Ten en cuenta que este
	// metodo solo puede ser accedido por esta clase o derivados.

	$stmt = $this->conn->prepare("SELECT id FROM users WHERE name=?");
	$stmt->bind_param("s", $name);
	$stmt->execute();
	$stmt->store_result();
	if ($stmt->num_rows > 0) {
	    $stmt->close();
	    return true;
	}

	$stmt->close();
	return false;
    }

    public function getUserInfo($userName="") {
	// Obtiene la informacion de un usuario.

	$info = [];
	$user = $this->user;

	if (!empty($userName)) {
	    $user = $userName;
	}

	$stmt = $this->conn->prepare("SELECT name,lastname,email,unreadNotifications,wantlearn FROM users WHERE name=?");
	$stmt->bind_param("s", $user);
	if ($stmt->execute()) {
	    $stmt->bind_result(
		$name,
		$lastname,
		$email,
		$unreadNotifications,
		$wantlearn
	    );
	    $stmt->fetch();

	    return array(
		"name" => $name,
		"lastname" => $lastname,
		"email" => $email,
		"unreadNotifications" => $unreadNotifications,
		"wantlearn" => $wantlearn
	    );
	}

	return false;
    }

    public function updateInfo($data) {
	// Actualiza la informacion de un usuario.

	$name = htmlspecialchars($data["name"]);
	$lastname = htmlspecialchars($data["lastname"]);
	$email = htmlspecialchars($data["email"]);

	$email = strtolower($email);

	$stmt = $this->conn->prepare("UPDATE users SET name=?,lastname=?,email=? WHERE name=?");
	$stmt->bind_param("ssss",
			  $name,
			  $lastname,
			  $email,
			  $this->user
	);
	if ($stmt->execute()) {
	    $stmt->close();
	    return true;
	} else {
	    $stmt->close();
	    return false;
	}
    }

    public function setLearning($data) {
	// Esta funcion establece los intereses del usuario. Util para un
	// sistema de recomendados basado en los intereses del usuario.

	$learning = "";

	if (isset($data["web"])) {
	    $learning .= "Desarrollo web,";
	}
	if (isset($data["hacking"])) {
	    $learning .= "Hacking,";
	}
	if (isset($data["movil"])) {
	    $learning .= "Desarrollo aplicaciones móviles,";
	}
	if (isset($data["programming"])) {
	    $learning .= "Programación,";
	}
	if (isset($date["linux"])) {
	    $learning .="Linux,";
	}

	$stmt = $this->conn->prepare("UPDATE users SET wantlearn=? WHERE name=?");
	$stmt->bind_param("ss", $learning, $this->user);
	if ($stmt->execute()) {
	    $stmt->close();
	    $finished = "yes";
	}

	if (isset($finished)) {
	    $stmt = $this->conn->prepare("UPDATE users SET finished=? WHERE name=?");
	    $stmt->bind_param("ss", $finished, $this->user);
	    $stmt->execute();
	    $stmt->close();

	    return true;
	}
    }

    public function hasFinished() {
	// Chequea si un usuario ya termino de completar su perfil o no.

	$stmt = $this->conn->prepare("SELECT finished FROM users WHERE name=?");
	$stmt->bind_param("s", $this->user);
	$stmt->execute();
	$stmt->bind_result($finished);
	$stmt->fetch();
	$stmt->close();

	if ($finished == "yes") {
	    return true;
	} else {
	    return false;
	}
    }

    public function logIn($mail, $pass) {
	// Metodo usuado para inciar sesion.

	$mail = strtolower($mail);

	$stmt = $this->conn->prepare("SELECT name,password FROM users WHERE email=? LIMIT 1");
	$stmt->bind_param("s", $mail);
	$stmt->execute();

	$stmt->store_result();
	if ($stmt->num_rows == 0) {
	    $stmt->close();
	    $this->errors[] = "El email ingresado no existe";

	    return false;
	} else {
	    $stmt->bind_result($name, $password);
	    $stmt->fetch();

	    if (password_verify($pass, $password)) {
		$stmt->close();

		return $name;
	    } else {
		$stmt->close();
		$this->errors[] = "Contraseña incorrecta";

		return false;
	    }
	}
    }

    public function isUserTutor($user = "") {
	// Chequea si un usuario es un tutor, o no.

	$toCheck = $this->user;

	if (!empty($user)) {
	    $toCheck = $user;
	}

	$stmt = $this->conn->prepare("SELECT isTutor FROM users WHERE name=?");
	$stmt->bind_param("s", $toCheck);
	$stmt->execute();
	$stmt->bind_result($isTutor);
	$stmt->fetch();

	if ($isTutor == "yes") {
	    $stmt->close();
	    return true;
	} else {
	    $stmt->close();
	    return false;
	}
    }

    public function isUserEnroledInCourse($courseId, $user="") {
	// Chequea si un usuario esta tomando un curso.

	$toCheck = $this->user;

	if (!empty($user)) {
	    $toCheck = $user;
	}

	$stmt = $this->conn->prepare("SELECT id FROM student_progress WHERE courseId=? AND student=?");
	$stmt->bind_param("is", $courseId, $toCheck);
	$stmt->execute();
	$stmt->store_result();
	if ($stmt->num_rows > 0) {
	    $stmt->close();
	    return true;
	} else {
	    $stmt->close();
	    return false;
	}
    }

    public function enrolInCourse($courseId, $lesson, $section, $user="") {
	// Ingresa el estudiante a un curso.

	$toEnrol = $this->user;

	if (!empty($user)) {
	    $toEnrol =  $user;
	}

	$stmt = $this->conn->prepare("INSERT INTO student_progress(student, courseId, lessonId, sectionId) VALUES(?,?,?,?)");
	$stmt->bind_param("siii", $toEnrol, $courseId, $lesson, $section);
	if ($stmt->execute()) {
	    $stmt->close();
	} else {
	    $stmt->close();
	    return false;
	}

	$stmt = $this->conn->prepare("UPDATE courses SET students=students+1 WHERE id=?");
	$stmt->bind_param("i", $courseId);
	$stmt->execute();
	$stmt->close();

	return true;

    }

    public function getCourseProgress($courseId, $user="") {
	// Devuelve el progreso de un estudiante en el curso especificado.

	$toGet = $this->user;

	if (!empty($user)) {
	    $toGet = $user;
	}

	$stmt = $this->conn->prepare("SELECT lessonId,sectionId FROM student_progress WHERE student=? AND courseId=? LIMIT 1");
	$stmt->bind_param("si", $toGet, $courseId);
	$stmt->execute();
	$stmt->bind_result($lessonId, $sectionId);
	$stmt->fetch();
	$stmt->close();

	return array(
	    "lesson" => $lessonId,
	    "section" => $sectionId
	);
    }

    public function updateCourseProgress($courseId, $lessonId, $sectionId, $user="") {
	// Actualiza el progreso de un estudiante en el curso especificado.

	$toUpdate = $this->user;

	if (!empty($user)) {
	    $toUpdate = $user;
	}

	$stmt = $this->conn->prepare("UPDATE student_progress SET courseId=?,lessonId=?,sectionId=?,completedLessons=completedLessons+1 WHERE student=?");
	$stmt->bind_param("iiis", $courseId, $lessonId, $sectionId, $toUpdate);
	if ($stmt->execute()) {
	    $stmt->close();
	    return 1;
	} else {
	    $stmt->close();
	    return 0;
	}
    }

    public function hasReviewedCourse($courseId, $user="") {
	// Chequea si un usuario ha dejado o no una calificación en el curso
	// especificado.

	$toCheck = $this->user;

	if (!empty($user)) {
	    $toCheck = $user;
	}

	$stmt = $this->conn->prepare("SELECT id FROM courses_reviews WHERE course=? AND student=? LIMIT 1");
	$stmt->bind_param("is", $courseId, $toCheck);
	$stmt->execute();
	$stmt->store_result();
	if ($stmt->num_rows > 0) {
	    $stmt->close();
	    return true;
	} else {
	    $stmt->close();
	    return false;
	}
    }

    public function getCourseReview($courseId, $user="") {
	// Obtiene la calificación que un usuario dejo en el curso especificado.

	$toGet = $this->user;

	if (!empty($user)) {
	    $toGet = $user;
	}

	$stmt = $this->conn->prepare("SELECT content,stars FROM courses_reviews WHERE course=? AND student=? LIMIT 1");
	$stmt->bind_param("is", $courseId, $toGet);
	$stmt->execute();
	$stmt->bind_result($content, $stars);
	$stmt->fetch();
	$stmt->close();

	return array(
	    "content" => $content,
	    "stars" => $stars
	);
    }

    public function reviewCourse($courseId, $stars, $content, $course, $notifications, $user="") {
	// Le crea una calificación al estudiante en el curso especificado.

	$toPost = $this->user;

	if (!empty($user)) {
	    $toPost = $user;
	}

	$stmt = $this->conn->prepare("INSERT INTO courses_reviews(student, content, stars, course) VALUES(?,?,?,?)");
	$stmt->bind_param("ssii", $toPost, $content, $stars, $courseId);
	if ($stmt->execute()) {
	    $stmt->close();

	    $ratings = $course->getRating();

	    $stmt = $this->conn->prepare("UPDATE courses SET rating=?,ratings=ratings+1 WHERE id=?");
	    $stmt->bind_param("di", $ratings["totalRating"], $courseId);
	    if ($stmt->execute()) {
		$stmt->close();

		$courseInfo = $course->getCourse();

		$notifData = array(
		    "sender" => $toPost,
		    "receiver" => $courseInfo["tutor"],
		    "courseId" => $courseId
		);

		$notifications->createNotification(0, $notifData);

		return true;
	    }

	    return false;
	} else {
	    $stmt->close();
	    return false;
	}
    }

    public function updateReview($courseId, $stars, $content, $course, $user="") {
	// Actualiza la calificación de un estudiante en el curso especificado.

	$toUpdate = $this->user;

	if (!empty($user)) {
	    $toUpdate = $user;
	}

	$stmt = $this->conn->prepare("UPDATE courses_reviews SET content=?,stars=? WHERE student=? AND course=?");
	$stmt->bind_param("sisi", $content, $stars, $toUpdate, $courseId);
	if ($stmt->execute()) {
	    $stmt->close();

	    $ratings = $course->getRating();

	    $stmt = $this->conn->prepare("UPDATE courses SET rating=? WHERE id=?");
	    $stmt->bind_param("di", $ratings["totalRating"], $courseId);
	    if ($stmt->execute()) {
		$stmt->close();
		return true;
	    }

	    return true;
	} else {
	    $stmt->close();
	    return false;
	}
    }

}

?>
