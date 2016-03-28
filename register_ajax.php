<?php
// register_ajax.php
    header("Content-Type: application/json"); // Since we are sending a JSON response here (not an HTML document), set the MIME Type to application/json
	ini_set("session.cookie_httponly", 1);

    session_start();
    $username = $_POST['username'];
    $password = $_POST['password'];
    // Check to see if the username and password are valid.  (You learned how to do this in Module 3.)
    if( !preg_match('/^[\w_\-]+$/', $username) ){
    	$_SESSION['message'] = "register_fail";
    	echo json_encode(array(
    		"success" => false,
    		"message" => "Invalid Username"
    		));
    	exit;
    }
    require 'database.php';
    $stmt = $mysqli->prepare("SELECT COUNT(*) FROM users WHERE username=?");
    if(!$stmt){
    	printf("Query Prep Failed: %s\n", $mysqli->error);
    	exit;
    }
    $stmt->bind_param('s', $username);
    $stmt->execute();
    $stmt->bind_result($cnt);
    $stmt->fetch();
    if( $cnt != 0 ) {
    	//send out error message
    	$_SESSION['message'] = "register_fail";
    	echo json_encode(array(
    		"success" => false,
    		"message" => "Username Exists!"
    		));
    	exit;
    }
    $stmt->close();
    //encrypt password
    $crypted_pass = crypt($password);
    $stmt2 = $mysqli->prepare("insert into users (username, crypted_password) values (?, ?)");
    if(!$stmt2){
    	printf("Query Prep Failed: %s\n", $mysqli->error);
    	exit;
    }
    $stmt2->bind_param('ss', $username, $crypted_pass);
    $stmt2->execute();
    $stmt2->close();
    //success
    $_SESSION['message'] = "register_success";
    echo json_encode(array(
    	"success" => true
    	));
    exit;
?>