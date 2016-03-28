<?php
// login_ajax.php
    header("Content-Type: application/json"); // Since we are sending a JSON response here (not an HTML document), set the MIME Type to application/json
	ini_set("session.cookie_httponly", 1);

	session_start();
    $username = $_POST['username'];
    $password = $_POST['password'];
    // Check to see if the username and password are valid.  (You learned how to do this in Module 3.)
    if( !preg_match('/^[\w_\-]+$/', $username) ){
    	$_SESSION['message'] = "login_fail";
    	echo json_encode(array(
    		"success" => false,
    		"message" => "Invalid Username"
    		));
    	exit;
    }
    require 'database.php';
    // Use a prepared statement
    $stmt = $mysqli->prepare("SELECT COUNT(*), user_id, crypted_password FROM users WHERE username=?");
    if(!$stmt){
    	printf("Query Prep Failed: %s\n", $mysqli->error);
    	exit;
    }
    // Bind the parameter
    $stmt->bind_param('s', $username);
    $stmt->execute();
    // Bind the results
    $stmt->bind_result($cnt, $user_id, $pwd_hash);
    $stmt->fetch();
    $stmt->close(); 
    /* valid username and password */
    if( $cnt == 1 && crypt($password,$pwd_hash)==$pwd_hash ){
    	$_SESSION['username'] = $username;
    	$_SESSION['user_id'] = $user_id;
    	$_SESSION['token'] = substr(md5(rand()), 0, 10);
    	
    	echo json_encode(array(
    		"success" => true,
    		"test" => "test",
    		"username" => $_SESSION['username'],
    		"token" => $_SESSION['token']
    		));
    	exit;
    }else{
    	echo json_encode(array(
    		"success" => false,
    		"message" => "Incorrect Username or Password"
    		));
    	exit;
    }
?>
