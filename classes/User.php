<?php

define("Class.User", "");

class User {

    protected $conn;
    protected $user;

    public $errors;

    function __construct($db) {
	$this->conn = $db;

	$this->errors = array();
    }

    function linkUser($username) {
	$this->user = $username;
    }

    function createUser($data) {
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

    public function getUserInfo() {
	$info = [];

	$stmt = $this->conn->prepare("SELECT name,lastname,email FROM users WHERE name=?");
	$stmt->bind_param("s", $this->user);
	if ($stmt->execute()) {
	    $stmt->bind_result(
		$name,
		$lastname,
		$email
	    );
	    $stmt->fetch();

	    return array(
		"name" => $name,
		"lastname" => $lastname,
		"email" => $email
	    );
	}

	return false;
    }

    public function updateInfo($data) {
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
	$toEnrol = $this->user;

	if (!empty($user)) {
	    $toEnrol =  $user;
	}

	$stmt = $this->conn->prepare("INSERT INTO student_progress(student, courseId, lessonId, sectionId) VALUES(?,?,?,?)");
	$stmt->bind_param("siii", $toEnrol, $courseId, $lesson, $section);
	if ($stmt->execute()) {
	    $stmt->close();
	    return true;
	} else {
	    $stmt->close();
	    return false;
	}
    }

    public function getCourseProgress($courseId, $user="") {
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

}

?>
