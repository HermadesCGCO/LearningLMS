><?php

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
