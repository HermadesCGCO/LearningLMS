<?php

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
	}

	if ($this->nameExists($data["name"])) {
	    $canProceed = 0;
	}

	if ($canProceed) {
	    $stmt = $this->conn->prepare("INSERT INTO users(name, lastname, email, password, registrationDate) VALUES(?, ?, ?, ?, ?)");
	    $stmt->bind_param("sssss",
			      $data["name"],
			      $data["lastname"],
			      $data["email"],
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
	$stmt = $this->conn->prepare("UPDATE users SET name=?,lastname=?,email=? WHERE name=?");
	$stmt->bind_param("ssss",
			  $data["name"],
			  $data["lastname"],
			  $data["email"],
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
	    $learning .= "web,";
	}
	if (isset($data["hacking"])) {
	    $learning .= "hacking,";
	}
	if (isset($data["movil"])) {
	    $learning .= "movil,";
	}
	if (isset($data["programming"])) {
	    $learning .= "programming,";
	}
	if (isset($data["ml"])) {
	    $learning .= "ml,";
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
	$result = $stmt->get_result();
	$row = $result->fetch_array(MYSQLI_ASSOC);
	$finished = $row["finished"];
	$stmt->close();

	if ($finished == "yes") {
	    return true;
	} else {
	    return false;
	}
    }

}

?>
