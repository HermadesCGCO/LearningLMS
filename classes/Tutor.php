<?php

define("Class.Tutor", "");

// Esta clase contiene muchos metodos utiles para obtener informacion de los
// tutores.

class Tutor {

    protected $conn;
    protected $name;

    public function __construct($mysql, $name) {
	// Inicializacion de la clase.

	$this->conn = $mysql;
	$this->name = $name;
    }

    public function getMyCourses($limit = 0) {
	// Obtiene las IDs de los cursos de este tutor. Ten en cuenta que puedes
	// especificar un limite.

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

    public function hasInfo() {
	// Chequea si un tutor ha proporcionado informacion sobre el o no.

	$stmt = $this->conn->prepare("SELECT id FROM tutorInfo WHERE tutor=? LIMIT 1");
	$stmt->bind_param("s", $this->name);
	$stmt->execute();
	$stmt->store_result();
	if ($stmt->num_rows > 0) {
	    $stmt->close();
	    return 1;
	} else {
	    $stmt->close();
	    return 0;
	}
    }

    public function getInfo() {
	// Obtiene la informacion de un tutor.

	$info = [];

	$stmt = $this->conn->prepare("SELECT * FROM tutorInfo WHERE tutor=? LIMIT 1");
	$stmt->bind_param("s", $this->name);
	$stmt->execute();
	$stmt->bind_result(
	    $id,
	    $tutor,
	    $shortDesc,
	    $description
	);
	$stmt->fetch();
	$stmt->close();

	$info["id"] = $id;
	$info["tutor"] = $tutor;
	$info["shortDesc"] = $shortDesc;
	$info["description"] = $description;

	return $info;
    }

    public function updateInfo($info) {
	// Actualiza la informacion de un tutor.

	$shortDesc = htmlspecialchars($info["shortDesc"]);
	$description = htmlspecialchars($info["description"]);

	$stmt = $this->conn->prepare("UPDATE tutorInfo SET shortDesc=?,description=? WHERE tutor=?");
	$stmt->bind_param("sss", $shortDesc, $description, $this->name);
	$stmt->execute();
	$stmt->close();
    }

    public function createInfo($info) {
	// Inserta la informacion de este tutor en la base de datos.

	$shortDesc = htmlspecialchars($info["shortDesc"]);
	$description = htmlspecialchars($info["description"]);

	$stmt = $this->conn->prepare("INSERT INTO tutorInfo(tutor,shortDesc,description) VALUES(?,?,?)");
	$stmt->bind_param("sss", $this->name, $shortDesc, $description);
	$stmt->execute();
	$stmt->close();
    }

}

?>
