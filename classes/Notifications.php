<?php

define("Classess.Notifications", "");

class Notifications {

    protected $conn;
    protected $user;

    public function __construct($db, $user) {
	$this->conn = $db;
	$this->user = $user;
    }

    public function createNotification($type, $data) {
	// NOTA: Ten en cuenta que $data SIEMPRE debe tener "receiver" y
	// "sender". Receiver es la persona que recibe la notificacion, por
	// ejemplo, un tutor cuyo curso recibio una calificacion y "sender"
	// siguiendo este ejemplo seria el estudiante que creo la notificacion.

	// Tipos de notificaciones:
	// 0 - Un usuario posteo una review en tu curso, para ello, $data debe
	// tener "student" y "courseId".

	$date = date("d-m-Y H:i:s");

	$stmt = $this->conn->prepare("INSERT INTO notifications(type,receiver,sender,date,dataInt) VALUES(?,?,?,?,?)");

	if ($type == 0) {
	    $stmt->bind_param("isssi", $type, $data["receiver"], $data["sender"], $date, $data["courseId"]);
	}

	if ($stmt->execute()) {
	    $stmt->close();

	    $stmt = $this->conn->prepare("UPDATE users SET unreadNotifications=unreadNotifications+1 WHERE name=?");
	    $stmt->bind_param("s", $data["receiver"]);
	    $stmt->execute();
	    $stmt->close();

	    return true;
	} else {
	    $stmt->close();
	    return false;
	}
    }

    public function getNotifications($limit = 0) {

	$query = "SELECT * FROM notifications WHERE receiver=?";

	$notifications = [];
	$i = 0;

	if ($limit > 0) {
	    $query .= " LIMIT " . $limit;
	}

	$stmt = $this->conn->prepare($query);
	$stmt->bind_param("s", $this->user);
	$stmt->execute();
	$stmt->bind_result($id, $type, $receiver, $sender, $date, $dataInt, $hasRead);

	while ($stmt->fetch()) {
	    $notifications[$i]["id"] = $id;
	    $notifications[$i]["type"] = $type;
	    $notifications[$i]["receiver"] = $receiver;
	    $notifications[$i]["sender"] = $sender;
	    $notifications[$i]["date"] = $date;
	    $notifications[$i]["dataInt"] = $dataInt;
	    $notifications[$i]["hasRead"] = $hasRead;

	    $i++;
	}

	$stmt->close();

	// TODO: Mover esto a una API que sera llamada una vez el usuario haga
	// clic en la campanita de notificaciones.

	$stmt = $this->conn->prepare("UPDATE notifications SET hasRead='yes' WHERE receiver=?");
	$stmt->bind_param("s", $this->user);
	$stmt->close();

	$stmt = $this->conn->prepare("UPDATE users SET unreadNotifications=0 WHERE name=?");
	$stmt->bind_param("s", $this->user);
	$stmt->execute();

	return $notifications;
    }

}

?>
