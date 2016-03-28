<?php
// update-event.php
ini_set("session.cookie_httponly", 1);

header("Content-Type: application/json"); // Since we are sending a JSON response here (not an HTML document), set the MIME Type to application/json
session_start();
//check if valid user
if (!isset($_SESSION['user_id'])){
	echo json_encode(array(
		"success" => false,
		"message" => "Sign in to update events!"
		));
	exit;
}


$user_id = $_SESSION['user_id'];
$id = $_POST['id'];
$title = $_POST['title'];
$content = $_POST['content'];
$date = $_POST['date'];//yyyy-mm-dd
$time =$_POST['time'];//HH:MM
$category = $_POST['category'];
$groupUser = $_POST['groupUser'];
$token = $_POST['token'];


//check token
if($_SESSION['token'] != $_POST['token']){	
	die("Request forgery detected");
}
//check input 
//YYYY-MM-DD HH:MM:SS
$eventTime = $date." ".$time.":00";
$safetime = preg_match("/^\d{4}-\d{2}-\d{2} [0-2][0-9]:[0-5][0-9]:[0-5][0-9]$/", $eventTime) ? $eventTime : "error";
if ($safetime=="error"){
	echo json_encode(array(
		"success" => false,
		"message" => "Format error in date and time"
		));
	exit;
}
require 'database.php';
//change in the database
$stmt = $mysqli->prepare("UPDATE events SET title=?, content=?, time=?, category=?, groupUser=? WHERE id =?");
if(!$stmt){
	printf("Query Prep Failed: %s\n", $mysqli->error);
	exit;
}
$stmt->bind_param('sssssi', $title, $content, $safetime, $category, $groupUser, $id);
$stmt->execute();
$stmt->close();
//success
echo json_encode(array(
	"success" => true
	));
exit;
?>
