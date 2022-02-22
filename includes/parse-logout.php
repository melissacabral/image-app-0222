<?php 
//url will look like login.php?action=logout
if( isset( $_GET['action'] ) AND $_GET['action'] == 'logout' ){
	//remove the access token from the DB
	$user_id = 0;
	if(isset($logged_in_user['user_id'])){
		$user_id = $logged_in_user['user_id'];
	}
	$access_token = '';
	if(isset($_COOKIE['access_token'])){
		$access_token = $_COOKIE['access_token'];
	}elseif(isset($_SESSION['access_token'])){
		$access_token = $_SESSION['access_token'];
	}
	
	//Nullify the access token from this user's DB row
   	$result = $DB->prepare( 'UPDATE users 
							SET
							access_token = NULL
							WHERE user_id = :id OR access_token = :token
							LIMIT 1' );
	$result->execute( array(
						'token' => $access_token,
						'id' => $user_id,
					) );
	

	//invalidate all cookies and session vars
	setcookie('access_token', 0, time() - 9999);
	setcookie('user_id', 0, time() - 9999);

	$_SESSION = array();

	//from php.net
	//php.net/manual/en/function.session-destroy.php
	if (ini_get("session.use_cookies")) {
	    $params = session_get_cookie_params();
	    setcookie(session_name(), '', time() - 42000,
	        $params["path"], $params["domain"],
	        $params["secure"], $params["httponly"]
	    );
	}

	session_destroy();
}//end logout logic