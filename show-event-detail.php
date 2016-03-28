<?php
// show-event-detail.php
ini_set("session.cookie_httponly", 1);

header("Content-Type: application/json"); // Since we are sending a JSON response here (not an HTML document), set the MIME Type to application/json
session_start();
//check if user has logged in
if (!isset($_SESSION['user_id'])){
	echo json_encode(array(
		"success" => false
		));
	exit;
}
$user_id = $_SESSION['user_id'];
//check token
if($_SESSION['token'] != $_POST['token']){
	die("Request forgery detected");
}
require 'database.php';
//query from database
$event_id = $_POST['event_id'];
$stmt = $mysqli->prepare("SELECT title, content, time, category, groupUser
	FROM events
	WHERE id=?");
if(!$stmt){
	printf("Query Prep Failed: %s\n", $mysqli->error);
	exit;
}
$stmt->bind_param('s', $event_id);
$stmt->execute();
$result = $stmt->get_result();
if($row = $result->fetch_assoc()){
	$row["title"] = htmlspecialchars($row["title"]);
	$row["content"] = htmlspecialchars($row["content"]);
	$event_id = htmlspecialchars($event_id);
	$row["category"] = htmlspecialchars($row["category"]);
	$row["groupUser"] = htmlspecialchars($row["groupUser"]);
	$safetime = preg_match("/^\d{4}-\d{2}-\d{2} [0-2][0-9]:[0-5][0-9]:[0-5][0-9]$/", $row["time"]) ? $row["time"] : "0000-00-00 00:00:00";
	$detail = array(
		"title"=>addslashes($row["title"]),
		"content"=>addslashes($row["content"]),
		"event_time"=>$safetime,
		"id"=>addslashes($event_id),
		"category"=>addslashes($row["category"]),
		"groupUser"=>addslashes($row["groupUser"]));
}
$stmt->close();
echo json_encode(array(
	"success" => true,
	"detail" => $detail,
	));
exit;
?>
