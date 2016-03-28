<?php
// display-event.php
ini_set("session.cookie_httponly", 1);

header("Content-Type: application/json"); // Since we are sending a JSON response here (not an HTML document), set the MIME Type to application/json
session_start();
//check if valid user
if (!isset($_SESSION['user_id'])){
	echo json_encode(array(
		"success" => false
		));
	exit;
}
$user_id = $_SESSION['user_id'];
$username = $_SESSION['username'];
//check token
if($_SESSION['token'] != $_POST['token']){
	die("Request forgery detected");
}
require 'database.php';
//query from database
$stmt = $mysqli->prepare("SELECT title, content, time, id, category, groupUser 
	FROM events
	WHERE user_id=? OR groupUser=? OR groupUser LIKE ?");
if(!$stmt){
	printf("Query Prep Failed: %s\n", $mysqli->error);
	exit;
}
$stmt->bind_param('sss', $user_id, $username, $username);
$stmt->execute();
$result = $stmt->get_result();
$return_arr = array();
while($row = $result->fetch_assoc()){
	$row["title"] = htmlspecialchars($row["title"]);
	$row["content"] = htmlspecialchars($row["content"]);
	$row["id"] = htmlspecialchars($row["id"]);
	$row["category"] = htmlspecialchars($row["category"]);
	$row["groupUser"] = htmlspecialchars($row["groupUser"]);
	//escape js input
	$safetime = preg_match("/^\d{4}-\d{2}-\d{2} [0-2][0-9]:[0-5][0-9]:[0-5][0-9]$/", $row["time"]) ? $row["time"] : "0000-00-00 00:00:00";
	$detail = array(
		"title"=>addslashes($row["title"]),
		"content"=>addslashes($row["content"]),
		"event_time"=>$safetime,
		"id"=>addslashes($row["id"]),
		"category"=>addslashes($row["category"]),
		"groupUser"=>addslashes($row["groupUser"]));
	array_push($return_arr,$detail);
	
}
$stmt->close();
echo json_encode(array(
	"success" => true,
	"events" => $return_arr,
	));
exit;
?>
