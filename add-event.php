<?php
// add-event.php
    header("Content-Type: application/json"); // Since we are sending a JSON response here (not an HTML document), set the MIME Type to application/json
    ini_set("session.cookie_httponly", 1);

    session_start();
    //check if user
    if (!isset($_SESSION['user_id'])){
    	echo json_encode(array(
    		"success" => false,
    		"message" => "Sign up to add event!"
    		));
    	exit;
    }
    $user_id = $_SESSION['user_id'];
    $title = $_POST['title'];
    $content = $_POST['content'];
    $date = $_POST['date'];//yyyy-mm-dd
    $time =$_POST['time'];//HH:MM
    $category = $_POST['category'];
    $groupUser = $_POST['groupUser'];
    $token = $_POST['token'];
    
    if($_SESSION['token'] != $_POST['token']){
    	die("Request forgery detected");
    }
    
    $eventTime = $date." ".$time.":00";
    $safetime = preg_match("/^\d{4}-\d{2}-\d{2} [0-2][0-9]:[0-5][0-9]:[0-5][0-9]$/", $eventTime) ? $eventTime : "error";
    if ($safetime=="error"){
    	echo json_encode(array(
    		"success" => false,
    		"message" => "Format error in date and time."
    		));
    	exit;
    }
    require 'database.php';
    //insert into database
    $stmt = $mysqli->prepare("INSERT INTO events (title, content, time, user_id, category, groupUser ) VALUES (?, ?, ?, ?, ?, ?)");
    if(!$stmt){
    	printf("Query Prep Failed: %s\n", $mysqli->error);
    	exit;
    }
    $stmt->bind_param('sssiss', $title, $content, $safetime, $user_id, $category, $groupUser);
    $stmt->execute();
    $stmt->close();
    //success
    echo json_encode(array(
    	"success" => true
    	));
    exit;
?>
