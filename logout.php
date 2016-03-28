<?php
// logout.php
header("Content-Type: application/json");
ini_set("session.cookie_httponly", 1);

session_start();
if (!isset($_SESSION['user_id'])){
	echo json_encode(array(
		"success" => false,
		"message" => "You haven't logged in!"
		));
	session_destroy();
	exit;
}
if(session_destroy()){
	echo json_encode(array(
		"success" => true
		));
	exit; 
}else{
	echo json_encode(array(
		"success" => false,
		"message" => "Not Logged Out"
		));
	exit;
}
?>
