<?php
// delete-event.php
ini_set("session.cookie_httponly", 1);

header("Content-Type: application/json"); 
session_start();
//if not a valid user
if (!isset($_SESSION['user_id'])){
	echo json_encode(array(
		"success" => false,
		"message" => "Sign in to delete event!"
		));
	exit;
}
//ensure the user status
$user_id = $_SESSION['user_id'];
$id = $_POST['id'];
$token = $_POST['token'];
//check token
if($_SESSION['token'] != $_POST['token']){
	die("Request forgery detected");
}
//delete from database
require 'database.php';
$stmt = $mysqli->prepare("DELETE FROM events WHERE id=? AND user_id=?");
if(!$stmt){
	printf("Query Prep Failed: %s\n", $mysqli->error);
	exit;
}
$stmt->bind_param('ss', $id, $user_id);
$stmt->execute();
$stmt->close();
//success
echo json_encode(array(
	"success" => true
	));
exit;
?>
