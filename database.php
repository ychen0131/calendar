<?php
ini_set("session.cookie_httponly", 1);

$mysqli = new mysqli('localhost','YouChen','Malisuji18!!','module5');
if($mysqli->connect_errno) {
	printf("Connection Failed: %s\n", $mysqli->connect_error);
	exit;
}
?>
