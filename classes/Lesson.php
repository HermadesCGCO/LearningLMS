<?php

define("Class.Lesson", "");

class Lesson {
    protected $conn;
    protected $id;

    public function __construct($db) {
	$this->conn = $db;
    }

    public function linkLesson($id) {
	$this->id = $id;
    }

    public function lessonExists($lessonId=null) {
	$lesson = $this->id;

	if ($lessonId != null) {
	    $lesson = $lessonId;
	}

	$stmt = $this->conn->prepare("SELECT name FROM courses_lessons WHERE id=?");
	$stmt->bind_param("i", $lesson);
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

    public function getCourse($lessonId=null) {
	$lesson = $this->id;

	if ($lessonId != null) {
	    $lesson = $lessonId;
	}

	$stmt = $this->conn->prepare("SELECT courseId FROM courses_lessons WHERE id=?");
	$stmt->bind_param("i", $lesson);
	$stmt->execute();
	$stmt->bind_result($courseId);
	$stmt->fetch();
	$stmt->close();

	return $courseId;
    }

    public function getInfo($lessonId=null) {
	$lesson = $this->id;

	if ($lessonId != null) {
	    $lesson = $lessonId;
	}

	$stmt = $this->conn->prepare("SELECT name,content,video FROM courses_lessons WHERE id=? LIMIT 1");
	$stmt->bind_param("i", $lesson);
	$stmt->execute();
	$stmt->bind_result($name, $content, $video);
	$stmt->fetch();
	$stmt->close();

	return Array(
	    "name" => $name,
	    "content" => $content,
	    "video" => $video
	);
    }

    public function updateLesson($data, $lessonId=null) {
	$lesson = $this->id;

	if ($lessonId != null) {
	    $lesson = $lessonId;
	}

	$stmt = $this->conn->prepare("UPDATE courses_lessons SET name=?,content=?,video=? WHERE id=?");
	$stmt->bind_param("sssi", $data["name"], $data["content"], $data["video"], $lesson);
	if ($stmt->execute()) {
	    $stmt->close();
	    return true;
	} else {
	    $stmt->close();
	    return false;
	}
    }

    public function deleteLesson($lessonId=null) {
	$lesson = $this->id;

	if ($lessonId != null) {
	    $lesson = $lessonId;
	}

	$stmt = $this->conn->prepare("DELETE FROM courses_lessons WHERE id=?");
	$stmt->bind_param("i", $lesson);
	if ($stmt->execute()) {
	    $stmt->close();
	    return true;
	} else {
	    $stmt->close();
	    return false;
	}
    }
}

?>
